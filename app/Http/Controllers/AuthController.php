<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return response()->json(['message' => 'ثبت‌نام با موفقیت انجام شد.']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'ورود موفقیت‌آمیز بود.']);
        }

        return response()->json(['message' => 'اطلاعات وارد شده نادرست است.'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'خروج با موفقیت انجام شد.']);
    }
}
