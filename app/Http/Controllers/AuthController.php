<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('passportToken')->accessToken
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('passportToken')->accessToken;

            return response()->json([
                'user' => Auth::user(), 
                'token' => $token
            ], 200);
        }

        return response()->json([
            'error' => 'Unauthorised'
        ], 401);
    }
}
