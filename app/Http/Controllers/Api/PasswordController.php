<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function sendEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users']);

        $user = User::query()->where('email', '=', $request->input('email'))->first();
        $otp = rand(100000, 999999);
        Mail::to($user)->send(new ResetPassword($otp));
        PasswordReset::query()->create([
            'email' => $user->email,
            'OTP' => $otp,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        return response()->json([
            'Message' => 'Email sent'
        ]);
    }

    public function checkOTP(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'email' => 'required|email'
        ]);
        $result = PasswordReset::query()->where('OTP', '=', $request->input('otp'))
            ->where('email', '=', $request->input('email'))
            ->where('created_at', '>', Carbon::now()->subHour())->first();

        if ($result->exists()) {
            $otp = rand(100000, 999999);
            $result->update([
                'OTP' => $otp,
                'created_at' => Carbon::parse($result->created_at)->addMinutes(5)
            ]);
            return response()->json([
                'secretOTP' => $otp,
                'message' => 'Confirmed'
            ]);
        } else
            return response()->json([
                'message' => 'OTP invalid or Timeout'
            ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'secret_otp' => 'required|digits:6',
            'email' => 'required|email|exists:users',
            'password' => 'required|alpha_num|min:8'
        ]);
        $result = PasswordReset::query()->where('OTP', '=', $request->input('secret_otp'))
            ->where('email', '=', $request->input('email'));
        if ($result->exists()) {
            $result->delete();
            User::query()->where('email', '=', $request->input('email'))
                ->update(['password' => Hash::make($request->input('password'))]);
            return response()->json([
                'message' => 'Password Reset Successfully'
            ]);
        } else
            return response()->json([
                'message' => 'OTP invalid or Timeout'
            ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|alpha_num|min:8',
        ]);
        if (Hash::check($request->input('current_password'), auth()->user()->getAuthPassword())) {
            auth()->user()->update(['password' => Hash::make($request->input('new_password'))]);
            return response()->json([
                'message' => 'Password Updated Successfully'
            ]);
        } else
            return response()->json([
                'message' => 'Wrong Password'
            ]);

    }
}
