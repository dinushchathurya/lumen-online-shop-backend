<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Please fix these errors', 
                'errors' => $validator->errors()
            ], 500);
        }

        try {
            $token = $this->jwt->attempt($request->only('email', 'password'));
            if(!$token) {
                return response()->json([
                    'success' => false, 
                    'message' => 'user not found'
                ], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'token expired'
            ], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'token invalid'
            ], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'unknown error'
            ], 500);
        }

        // if everything ok
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $token
        ]);
    }

    function userDetails()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user
        ]);
    }

    function logout()
    {
        $token = auth()->tokenById(auth()->user()->id);
        $this->jwt->setToken($token)->invalidate();
        \auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Signed out successfully!'
        ]);
    }

    function checkLogin() {
        if(Auth::user()) {
            return response()->json([
                'success' => 1
            ]);
        }
        return response()->json([
            'success' => 0
        ]);
    }
}