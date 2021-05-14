<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(CreateUser $request)
    {
        $validateData = $request->validated();
        $user = new User();
        $res = $user->signup($validateData);

        if ($res === TRUE) {
            return response('success', 201);
        }
    }

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($validateData)) {
            return response(['登入失敗', 401]);
        }

        // $user = Auth::user();
        $user = $request->user();
        $tokenResult = $user->createToken('token');
        $tokenResult->token->save();

        return response(['token' => $tokenResult->accessToken]);

    }

    public function user(Request $request)
    {
        return response(
            $request->user()
        );
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response(
            ['message' => '登出成功']
        );
    }
}
