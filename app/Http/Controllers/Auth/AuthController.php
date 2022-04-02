<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken(uniqid())->plainTextToken,
        ];
    }

    public function user(Request $request)
    {
        return response()->json(auth()->user());
    }

    /**
     * Accepts either logout_all input for logout
     * or leave empty for logout current token. (need to be sent from header)
     */
    public function logout(Request $request)
    {
        if (!empty($request->logout_all)) {
            auth()?->user()?->tokens()?->delete();

            return response()->noContent();
        }

        // Get Bearer Token from header
        $token = $request->header('Authorization');
        $tokenId = "";

        // Replace "Bearer TOKEN" to just "TOKEN"
        if (!empty($token)) {
            $tokenId = trim(str_replace("Bearer", "", $token));
        }

        auth()->user()->tokens()->where('id', $tokenId)->delete();

        return response()->noContent();
    }
}
