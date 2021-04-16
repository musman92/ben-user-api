<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPin;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:4|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'invite' => 'required',
        ]);

        //check invite is correct
        $invite = Invitation::where([
            'email' => $validatedData['email'],
            'invitation_token' => $validatedData['invite'],
        ]);

        if(!$invite) {
            return response()->json([
                'message' => 'Invalid invite token'
            ], 400);
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'registered_at' => date('Y-m-d H:i:s'),
        ]);
        $user->pin = rand ( 100000 , 999999 );
        $user->save();

        Mail::to($validatedData['email'])->send(new SendPin($user));

        return response()->json([
            'message' => 'Check your email we sent you 6 digit pin'
        ]);
    }

    public function verifyPin(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|exists:users',
            'pin' => 'required',
        ]);
        $user = User::where([
            'email' => $validatedData['email'],
            'pin' => $validatedData['pin'],
        ])->first();

        if(!$user) {
            return response()->json([
                'message' => 'token is invalid'
            ], 400);
        }
        $user->pin = null;
        $user->verified = 1;
        $user->save();

        Invitation::where('email', $validatedData['email'])->delete();
        return response()->json([
            'message' => 'Registered Successfully.'
        ], 200);
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $payload = [
            $fieldType => $input['username'], 
            'password' => $input['password'], 
            'verified' => 1
        ];
        // return $payload;
        if (!Auth::attempt($payload)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where($fieldType, $input['username'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

}
