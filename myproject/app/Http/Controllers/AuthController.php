<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        
        $token = Str::random(60);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'api_token' => $token,
            'email_verified_at' => null 
        ]);
    
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', 
            Carbon::now()->addMinutes(60), 
            ['id' => $user->_id]
        );
    
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));
    
        return response()->json([
            'message' => 'User registered. Please check your email to verify.',
            'token' => $token,
            'user' => new UserResource($user)
        ], 201);
    }
    
    public function login(LoginRequest $request) {
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401); 
        }
        
        $newToken = Str::random(60);
        
        $user->update(['api_token' => $newToken]);
        
        return response()->json([
            'message' => 'Login successful',
            'token' => $newToken, 
            'user' => new UserResource($user)
        ], 200);
    }
    public function verifyEmail(Request $request, $id) {
        
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired URL'], 401);
        }
    
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        if (!$user->email_verified_at) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }
    
        return response()->json(['message' => 'Email verified successfully!']);
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
}