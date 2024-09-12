<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryPasswordMailable;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use OpenApi\Annotations as OA;
use Psy\Util\Str;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     summary="Log in a user in the app",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = User::where('email', $request->email)->first();

            $success['access_token'] = $token;
            $success['token_type'] = 'bearer';
            $success['user'] = $user;
            $success['expires_in'] = JWTAuth::factory()->getTTL() * 525600;
            $success['status'] = 200;

            return $this->sendResponse($success, 'User login successfully.');
        } catch (JWTException $e) {
            return $this->sendError('Unauthorized', [], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users/recovery",
     *     summary="Send a email to recovery password",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
    public function recoveryPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        try {
            $status = Password::sendResetLink(['email' => $email]);
            var_dump($status);

            if ($status === Password::RESET_LINK_SENT) {
                return $this->sendResponse(['email' => $email], 'We have sent you a link to reset your password.');
            } else {
                return $this->sendError('Unable to send recovery email. Please try again later.', '', 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to send recovery email.', 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users/reset-password",
     *     summary="Reset user's password",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "c_password", "token"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="c_password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="token", type="string", example="abcdef123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="user@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'c_password' => 'required|same:password',
            'token' => 'required|string',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if ($status !== Password::PASSWORD_RESET) {
            return $this->sendError('Password reset failed.');
        }

        return $this->sendResponse([], 'Password reset successfully.');
    }
}
