<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PasswordResetController extends Controller
{
    public function send_reset_email(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user)
            return response()->json(['message' => 'User not found'], 404);

  
//generate token 
        $token = Str::random(60);
//saving data to password reset table
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mail.password_reset', ['token' => $token], function(Message $message) use ($request){
            $message->to($request->email);
            $message->subject('Password Reset');
        });
}
}