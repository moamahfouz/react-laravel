<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends BaseController
{

    /**
     * Login api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken($user->email)->plainTextToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function verifyToken(Request $request): \Illuminate\Http\JsonResponse
    {

        $token = PersonalAccessToken::findToken($request->token);

        if (!$token) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $user = $token->tokenable;
        $success['user'] = $user;
        $success['token'] = $request->token;

        return $this->sendResponse($success, 'User verified successfully.');
    }

}
