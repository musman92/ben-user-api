<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function me(Request $request)
    {
        return $request->user();
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = $request->user();
        $user->name = $validatedData['name'];
        if (request()->file('avatar')) {
            $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();
            $request->avatar->storeAs('public/avatars',$avatarName);
            $user->avatar = $avatarName;
        }
        $user->save();

        return response()->json([
            'message' => 'user updated sucessfully',
            'data' => $user
        ], 400);
    }
}
