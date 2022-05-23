<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public function forgetPassword()
    {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
        ]);

        Password::sendResetLink($credentials);
        return response()->json(['message' => 'Reset password link has been sent to your email.']);

        // if ($credentials['email'] == User::where('email', $credentials['email'])->first()->email) {
        //     $user = User::where('email', $credentials['email'])->first();
        //     $user->sendPasswordResetNotification($user->getEmailForPasswordReset());
        // return response()->json(['message' => 'Reset password link has been sent to your email.']);
        // }
    }

    public function resetPassword()
    {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:25', 'confirmed'],
            'token' => ['required', 'string'],
        ]);
        $email_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        if ($email_password_status == Password::INVALID_TOKEN) {
            return $this->response()->json(['message' => 'INVALID RESET_PASSWORD_TOKEN']);
        }
        return response()->json(['message' => 'Password successfully changed']);
    }

    public function changePassword(Request $request)
    {

        // $request->validate([
        //     'current_password'  => ['required', 'current_password'],
        //     'password'          => ['required', 'confirmed', 'min:6'],
        // ]);

        // auth()->user()->update([
        //     'password' => bcrypt($request->password),
        // ]);
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->update();
            return response()->json(['message' => 'Password successfully changed']);
        }
        return response()->json(['message' => 'Old password is incorrect']);
    }
}
