<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'date_visited' => 'required|date',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }

        // Find the user by username
        $user = User::where('username', $request->input('username'))->first();

        // If the user exists, update their information
        if ($user) {
            $user->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'User information updated successfully',
                'data' => new UserResource($user),
            ], 200);
        }

        // If the user does not exist, create a new one
        $newUser = User::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User logged successfully',
            'data' => new UserResource($newUser),
        ], 200);
    }
}
