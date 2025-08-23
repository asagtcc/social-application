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
            'otp_expires_at' => now()->addMinutes(3),
        ]);
        Mail::to($user->email)->send(new OtpMail($user, $otp));

        return response()->json([
            'message' => 'OTP has been sent to your email',
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);

        $user = User:: where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }

        if ($user->otp_expires_at->isPast()) {
            return response()->json(['message' => 'OTP has expired'], 422);
        }
         $newPassword = Str::random(10);
        $user->update( [
        'otp' => null,
        'otp_expires_at' => null,
        'password' => bcrypt($newPassword),
        ]);
         Mail::to($user->email)->send(new NewPasswordMail($user, $newPassword));

        return response()->json(['message' => 'the new password sent to your email'], 200);
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

        $user = User::where('email', $request->email)
        ->with('organisations')
        ->first();

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
