<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $role_id = Role::where("key", "user")->pluck("id")->first();

        $request["role_id"] = $role_id;
        $validator = Validator::make($request->all(), [
            "email" => ['required', 'email', 'unique:users'],
            "role_id" => ['required'],
            "username" => ['required', 'unique:users', 'min:5'],
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        try {
            $user = User::create([
                'role_id' => $role_id,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
        } catch (Exception $exception) {
            Log::error("Error while Creating an Account" . $exception->getMessage());
        } finally {
            return response()->json([$user], 201);
        }
    }


    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "email" => ['required', 'email'],
            "password" => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken(env("APP_NAME"));
            $user['token'] = $token->plainTextToken;
            return response()->json(["user" => $user], 200);
        } else {
            return response()->json(["error" => "User Not Authorised"], 401);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json(["Logged Out"], 200);
    }
}
