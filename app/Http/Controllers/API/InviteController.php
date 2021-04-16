<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvite;

class InviteController extends Controller
{
    public function invite(Request $request) {
        $validatedData = $request->validate([
            'emails.*' => 'required|email|min:1',
        ]);
        $user = $request->user();
        if($user->role == 'user') {
            return response()->json([
                'message' => 'You do not have permission'
            ], 403);
        }
        foreach ($validatedData['emails'] as $email) {
            $i = Invitation::firstOrCreate([
                'email' => $email
            ]);
            $i->generateInvitationToken();
            $i->save();

            Mail::to($email)->send(new SendInvite($i));
        }
        return response()->json([
            'message' => 'Invite has been sent'
        ], 403);
    }
}
