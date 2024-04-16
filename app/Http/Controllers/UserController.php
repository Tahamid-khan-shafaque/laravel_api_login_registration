<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Add this line to import the Hash class

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            //'password' => 'required|confirmed',
            'password' => 'required|confirmed',
            'tc'=> 'required'
        ]); 
        $userCheck = User::where('email', $request->email)->first();
        if ($userCheck)
            return response()->json(['message' => 'Email already exist'], 409);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
          //  'password' => Hash::make($request->password),
            'password' => $request->password,
            'tc' => json_decode($request->tc),
        ]);
        $token=$user->createToken($request->email)->plainTextToken;
        return response()->json(['token' => $token, 'status' => 'success','message' => 'User created', 'user' => $user], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || $request->password !== $user->password) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token=$user->createToken($request->email)->plainTextToken;
        return response()->json(['token' => $token, 'status' => 'success','message' => 'User logged in', 'user' => $user], 201);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success','message' => 'User logged out'], 200);
    }

    public function logged_user(Request $request){
        if (Auth::check()) {
            return response()->json(['status' => 'success', 'message' => 'Logged User', 'user' => $request->user()], 200);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'User not logged in'], 401);
        }

        
    }

    public function change_password(Request $request){
        if(!Auth::check())
            return response()->json(['message' => 'Unauthorized'], 401);

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();
        $user->password = $request->password;
        $user->save();

        return response()->json(['status' => 'success','message' => 'password changed'], 201);
    }
}
