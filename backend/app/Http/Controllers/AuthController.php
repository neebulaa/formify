<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|min:5"
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "Invalid fields",
                "errors" => $validator->errors()
            ], 422);
        }

        $vd = $validator->validated();
        if (!Auth::attempt($vd)) {
            return response([
                "message" => "Email or password incorrect"
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken(uniqid())->plainTextToken;
        return response([
            "message" => "Login success",
            "user" => [
                "name" => $user->name,
                "email" => $user->email,
                "accessToken" => $token
            ]
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            "message" => "Logout success"
        ]);
    }

    public function me()
    {
        return response([
            "message" => "Get user success",
            "user" => auth()->user()
        ]);
    }
}
