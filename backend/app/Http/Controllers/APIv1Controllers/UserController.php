<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryPasswordMailable;
use App\Models\Car;
use App\Models\MechanicScore;
use App\Models\User;
use App\Helper\UserHelper;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
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
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $roles = User::with('roles')->find($user->id);
        $user->roles = $roles->roles[0]->name;
        unset($user->password);

        $success = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
            'expires_in' => JWTAuth::factory()->getTTL() * 525600,
            'status' => 200
        ];

        return $this->sendResponse($success, 'User login successfully.');
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
            'password' => 'required|min:8',
            'c_password' => 'required',
            'token' => 'required|string',
        ]);
        $password = $validator->getValue('password');
        $c_password = $validator->getValue('c_password');

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        if($password != $c_password) {
            return $this->sendError('Password does not match.');
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
     *     path="/api/jwt/client/register",
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
     *             @OA\Property(property="rut", type="string", example="12345678-9", description="Client's RUT"),
     *             @OA\Property(property="phone", type="string", example="986415709", description="Client's phone")
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
            'phone' => 'required|string',
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
        $defaultPasswordClient = trim(str_replace("-", "", $validator->getValue('rut')));

        $client = new User();
        $client->username = $validator->getValue('name') . ' ' . $validator->getValue('lastname');;
        $client->email = $validator->getValue('email');
        $client->name  = $validator->getValue('name');
        $client->lastname = $validator->getValue('lastname');
        $client->password = Hash::make($defaultPasswordClient);
        $client->rut = $validator->getValue('rut');
        $client->phone = $validator->getValue('phone');
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

    /**
     * @OA\Post(
     *     path="/api/jwt/mechanic/{mechanicId}/setScore",
     *     summary="Asigna una puntuación y un comentario a un mecánico",
     *     description="Permite que un usuario autenticado asigne una puntuación de 1 a 5 y un comentario a un mecánico específico.",
     *     tags={"Mechanic"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\Parameter(
     *         name="mechanicId",
     *         in="path",
     *         required=true,
     *         description="ID del mecánico al cual se le va a asignar la puntuación.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Puntuación y comentario para el mecánico",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="score",
     *                 type="integer",
     *                 description="Puntuación a asignar al mecánico (debe estar entre 1 y 5).",
     *                 minimum=1,
     *                 maximum=5,
     *                 example=4
     *             ),
     *             @OA\Property(
     *                 property="comment",
     *                 type="string",
     *                 description="Comentario opcional sobre el servicio del mecánico.",
     *                 example="Excelente trabajo y muy profesional."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Puntuación y comentario asignados con éxito",
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
     *                 example="Mechanic score updated successfully."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="mechanic",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="score", type="integer", example=4),
     *                     @OA\Property(property="comment", type="string", example="Excelente trabajo y muy profesional.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado",
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
     *                 example="You need to sign in first."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud incorrecta",
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
     *                 example="Score must be between 1 and 5."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El usuario no es un mecánico",
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
     *                 example="User is not mechanic."
     *             )
     *         )
     *     )
     * )
     **/
    public function setMechanicScore(int $mechanicId, Request $request): JsonResponse
    {
        if (auth()->check() === false) {
            return $this->sendError('You need to sign in first.');
        }

        $score = $request->input("score");
        $comment = $request->input("comment");
        $client = auth()->user();

        if ($score < 1 || $score > 5) {
            return $this->sendError('Score must be between 1 and 5.');
        }

        $mechanic = User::whereId($mechanicId)->first();
        if(!$mechanic->hasRole(User::MECHANIC)) {
            return $this->sendError('user is not mechanic.');
        }

        $mechanicScore = new MechanicScore();
        $mechanicScore->mechanic_id = $mechanic->id;
        $mechanicScore->user_id = $client->id;
        $mechanicScore->score = $score;
        $mechanicScore->comment = $comment ?? null;
        $mechanicScore->save();
        $success['mechanicScore'] = $mechanicScore;

        return $this->sendResponse($success, 'Mechanic score updated successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/mechanic/all",
     *     summary="Obtener todos los mecánicos",
     *     description="Este endpoint permite obtener una lista de todos los usuarios con el rol de mecánico. El usuario debe estar autenticado para acceder a esta información.",
     *     tags={"Mechanic"},
     *     *     security={{
     * *         "bearerAuth": {}
     * *     }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todos los mecánicos obtenida con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="mechanics",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="John Doe"
     *                         ),
     *                         @OA\Property(
     *                             property="email",
     *                             type="string",
     *                             example="johndoe@example.com"
     *                         ),
     *                         @OA\Property(
     *                             property="phone",
     *                             type="string",
     *                             example="+123456789"
     *                         ),
     *                         @OA\Property(
     *                             property="role",
     *                             type="string",
     *                             example="mechanic"
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="All mechanics retrieved successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado",
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
     *                 example="You need to sign in first."
     *             )
     *         )
     *     )
     * )
     */
    public function getAllMechanics(): JsonResponse
    {
        if (auth()->check() === false) {
            return $this->sendError('You need to sign in first.');
        }

       $success['mechanics'] = $this->userHelper->getMechanicUsers();
        return $this->sendResponse($success, 'All mechanics retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/client/information",
     *     summary="Obtiene la información del usuario autenticado",
     *     description="Devuelve la información del usuario autenticado y el ID del carro asociado",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario obtenidos exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="john.doe@example.com"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="car_id",
     *                     type="integer",
     *                     example=5
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="user data retrieved successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado",
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
     *                 example="You need to sign in first."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario o carro no encontrado",
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
     *                 example="User or car not found."
     *             )
     *         )
     *     )
     * )
     */
    public function getUserInformation(): JsonResponse
    {
        try {
            if(auth()->check() === false) {
                return $this->sendError('You need to sign in first.');
            }
            $userJWT = auth()->user();

            $user = User::whereId($userJWT->id)->first();
            $car = Car::where('owner_id', $userJWT->id)->first();

            if (!$user || !$car) {
                return response()->json([
                    'success' => false,
                    'message' => 'User or car not found.'
                ], 404);
            }

            $success['user'] = $user;
            $success['car_id'] = $car->id;

            return $this->sendResponse($success, 'user data retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
