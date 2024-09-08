<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
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
            $success['expires_in'] = JWTAuth::factory()->getTTL() * 525600;
            $success['status'] = 200;

            return $this->sendResponse($success, 'User login successfully.');
        } catch (JWTException $e) {
            return $this->sendError('Unauthorized', [], 400);
        }
    }
}
