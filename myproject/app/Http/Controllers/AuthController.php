<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Mail\OTPVerification; 
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => null,
            'api_token' => null 
        ]);
    
        $otp = rand(100000, 999999);
    
        $user->otp = $otp;
        $user->save();

        try {
            Mail::to($user->email)->send(new OTPVerification($otp));
        } catch (\Exception $e) {
            return response()->json(['message' => 'User created, but email failed to send.'], 500);
        }
    
        return response()->json([
            'message' => 'User registered successfully. Please check your email for the OTP code.',
            'email' => $user->email 
        ], 201);
    }
    public function verifyOtp(Request $request) {
        
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->otp == $request->otp) {
            
            $token = Str::random(60);

            $user->otp = null; 
            $user->email_verified_at = now();
            $user->api_token = $token;
            $user->save();

            return response()->json([
                'message' => 'Account verified successfully!',
                'token' => $token,
                'user' => new UserResource($user)
            ], 200);
        }

        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }

    public function login(LoginRequest $request) {
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401); 
        }

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Please verify your email first.'], 403);
        }
        
        $newToken = Str::random(60);
        $user->update(['api_token' => $newToken]);
        
        return response()->json([
            'message' => 'Login successful',
            'token' => $newToken, 
            'user' => new UserResource($user)
        ], 200);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['email' => $request->email, 'token' => $token, 'created_at' => now()]
        );

        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return response()->json(['message' => 'Password reset link sent!']);
    }
    public function logout(Request $request)
    {
        $user = auth()->user(); 

        if ($user) {
            $user->api_token = null;
            $user->save();

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'User not found'], 401);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid token or email'], 400);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
             return response()->json(['message' => 'User does not exist'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password has been reset successfully!']);
    }
}