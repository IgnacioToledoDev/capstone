<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryPasswordMailable;
use App\Models\User;
use App\Helper\UserHelper;
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
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private UserHelper $userHelper;
    public function __construct(UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
    }

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

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = User::where('email', $request->email)->first();
            $roles = User::with('roles')->find($user->id);
            $user->roles = $roles->roles[0]->name;
            unset($user->password);

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
            $user = $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->sendError('Email does not exist.');
            }

            $token = Password::createToken($user);
            Mail::to($email)->send(new RecoveryPasswordMailable($token));

            return $this->sendResponse([], 'send link successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to send recovery email.', 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users/reset",
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
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

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

    /**
     * @OA\Post(
     *     path="/api/users/client/register",
     *     summary="Register a new client",
     *     tags={"Clients"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "password_confirmation", "name", "lastname", "rut"},
     *             @OA\Property(property="email", type="string", format="email", example="client@example.com", description="Client's email address"),
     *             @OA\Property(property="name", type="string", example="John", description="Client's first name"),
     *             @OA\Property(property="lastname", type="string", example="Doe", description="Client's last name"),
     *             @OA\Property(property="rut", type="string", example="12345678-9", description="Client's RUT")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="client", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="client@example.com"),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="rut", type="string", example="12345678-9"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-17T02:42:18Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-17T02:42:18Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You need to sign in first.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Permission denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Permission denied.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict due to existing email or invalid RUT",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email already registered or Rut invalid.")
     *         )
     *     )
     * )
     */
    public function registerClient(Request $request): JsonResponse
    {
        if (auth()->check() === false) {
            return $this->sendError('You need to sign in first.');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'rut' => 'required|string',
        ]);

        $user = auth()->user();
        $RolesAllowed = [
            User::SUPER_ADMIN,
            User::MECHANIC,
            User::COMPANY_ADMIN
        ];

        if (!in_array($user->roles[0]->name, $RolesAllowed)) {
            return $this->sendError('Permission denied.');
        }

        $isRutValid = $this->userHelper->validateRut($validator->getValue('rut'));
        if (!$isRutValid) {
            return $this->sendError('Rut invalid.');
        }

        $emailIsInUsed = User::where(['email' => $validator->getValue('email')])->first();
        if ($emailIsInUsed) {
            return $this->sendError('Email already registered.');
        }

        $client = new User();
        $client->username = $validator->getValue('name') . ' ' . $validator->getValue('lastname');;
        $client->email = $validator->getValue('email');
        $client->name  = $validator->getValue('name');
        $client->lastname = $validator->getValue('lastname');
        $client->password = Hash::make($validator->getValue('rut'));
        $client->rut = $validator->getValue('rut');
        $client->save();

        $client->assignRole(User::CLIENT);
        $roles = User::with('roles')->find($client->id);
        $client->roles = $roles->roles[0]->name;
        unset($client->password);


        $success['client'] = $client;

        return $this->sendResponse($success, 'client registered successfully.');
    }

    /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     summary="Logout the authenticated user",
     *     description="Invalidates the user's JWT token, effectively logging them out from the system.",
     *     tags={"Users"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User logged out successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to log out.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to log out."
     *             )
     *         )
     *     )
     * )
     **/
    public function logout(Request $request): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->sendResponse([], 'User logged out successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to log out.', 400);
        }
    }
}
