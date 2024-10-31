<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $token = auth('api')->attempt($request->all(['email', 'password']));

        if(!$token){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['token' => $token]);

    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
