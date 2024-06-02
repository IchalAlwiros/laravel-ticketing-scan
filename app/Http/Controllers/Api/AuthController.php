<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        //check user
        if (!$user) {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'User not found'
            // ], 404);
            return ResponseHelper::sendErrorResponse('User not found', null);
        }

        //check password
        if (!Hash::check($request->password, $user->password)) {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Password is not match'
            // ], 404);
            return ResponseHelper::sendErrorResponse('Password is not match', null);
        }

        //generate token
        $token = $user->createToken('token')->plainTextToken;

        // return response()->json([
        //     'token' => $token,
        //     'user' => $user
        // ]);

        return ResponseHelper::sendSuccessResponse('User authenticated successfully', ['profile' => $user, 'token' => $token]);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Logout successfully'
        // ]);
        return ResponseHelper::sendSuccessResponse('Logout successfully', null);
    }
}
