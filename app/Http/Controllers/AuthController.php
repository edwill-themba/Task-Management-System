<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:191',
            'email' => 'email|required|unique:users|unique:supervisors',
            'password' => 'required|min:6|max:191|confirmed',
            'user_type' => 'required'
        ]);


        // gets the user type
        $user_type = $request->input('user_type');
        // checks if user is a subordinate
        if ($user_type == 'subordinate') {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->type = 'subordinate';
            $user->password = bcrypt($request->input('password'));
            $user->save();
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 201);
        } elseif ($user_type == 'supervisor') {
            $user = new Supervisor;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->type = 'supervisor';
            $user->password = bcrypt($request->input('password'));
            $user->save();
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 201);
        } else {
            return response()->json(['message' => 'incorrect user:users are supervisors or
            subordinates'], 404);
        }
    }

    /**
     * logs the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
            'user_type' => 'required'
        ]);

        // gets the user type
        $user_type = $request->input('user_type');
        // checks if user is a subordinate
        if ($user_type == 'subordinate') {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials:please check your email and password.'],
                ]);
            }
            return $user->createToken($request->device_name)->plainTextToken;
        } elseif ($user_type == 'supervisor') {
            $supervisor = Supervisor::where('email', $request->email)->first();
            if (!$supervisor || !Hash::check($request->password, $supervisor->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials:please check your email and password.'],
                ]);
            }
            return $supervisor->createToken($request->device_name)->plainTextToken;
        } else {
            return response()->json(['message' => 'incorrect user:users are supervisors or
            subordinates'], 404);
        }
    }

    /**
     * Logs the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'you are successfully logged out'], 200);
    }

}
