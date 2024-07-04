<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\LoginFormRequest;

class LoginRegisterController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = $this->createUser($request);

        $token = $user->createToken('token')->accessToken;

        $data['user'] = $user;

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
            'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function login(LoginFormRequest $request)
    {
        $user = $this->attemptLogin($request);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken($request->email)->accessToken;
        $data['token'] = $token;
        $data['user'] = $user;

        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
        ], 200);
    }

    private function createUser(RegisterFormRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    private function attemptLogin(LoginFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
