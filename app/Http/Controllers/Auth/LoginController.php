<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dataUser = $request->only(['email', 'password']);

        if($user = User::where('email', $dataUser['email'])->first()) {

            $expires_in = now()->addHour()->diff(now());

            if(Hash::check($dataUser['password'], $user->password)) {
                return response()->json([
                    'data' => $user->createToken(now()->getTimestamp())->accessToken,
                    'expires_in' => $expires_in
                ]);
            }
        }

        return response()->json(['error' => 'authentication error'], 401);
    }

    public function register(Request $request)
    {
        $dataUser = $request->only(['username', 'password', 'email']);

        $expires_in = now()->addHour()->diff(now());

        if($user = User::where('email', $dataUser['email']))
            return response()->json(['error' => 'you\'re registered already'], 401);

        if($user = User::create($dataUser)) {
            return  response()->json([
                'data' => $user->createToken(now()->getTimestamp())->accessToken,
                'expires_in' => $expires_in
            ]);
        }

        return response()->json(['error' => 'error on register user'], 500);
    }
}
