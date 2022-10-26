<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Logout all other sessions
        // if ($request->has('logout_other_devices')) $user->tokens()->delete();
        $user->tokens()->delete();

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        return new UserResource($user);
    }

    public function logout()
    {
        $user = User::find(auth()->user()->id);

        $user->tokens()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
