<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'string|required|email',
            'password' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized"
            ], 401);
        }

        return response()->json([
            "status" => true,
            "message" => "Login Success",
            "data" => [
                'accessToken' => $token,
            ]
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed:password_confirmation',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            "status" => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function me()
    {
        $user = Auth::user();

        return response()->json([
            "status" => true,
            'message' => 'Success',
            'data' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            "status" => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            "status" => true,
            "message" => "Refresh Token Success",
            "data" => [
                'accessToken' => Auth::refresh(),
            ]
        ]);
    }
}
