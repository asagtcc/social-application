<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\Auth\OtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Mail\Auth\NewPasswordMail;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function sendOpt(Request $request): JsonResponse
    {
          $otp = rand(100000, 999999);
           $user = User::where('email', $request->email)->first();
            $user->update([
            'otp' => $otp,
        ]);
        Mail::to($user->email)->send(new OtpMail($user, $otp));

        return response()->json([
            'message' => 'OTP has been sent to your email',
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $user = User::where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }
         $newPassword = Str::random(10);
        $user->update( [
        'otp' => null,
        'password' => bcrypt($newPassword),
        ]);
         Mail::to($user->email)->send(new NewPasswordMail($user, $newPassword));

        return response()->json(['message' => 'OTP verified successfully'], 200);
    }

    public function logout()
    {
        auth()->user('sanctum')->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    
    public function register(AuthRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
            'fcm_token' => $request->fcm_token
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
               'message' => 'User registered successfully',
                'token' => $token, 
                'user' => new UserResource($user),
                'error' => ''
            ], 200);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.']
            ]);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
}
