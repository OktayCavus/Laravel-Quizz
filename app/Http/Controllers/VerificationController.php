<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    public function verify($user_id, Request $request)
    {
        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->is_active = 1;
            $user->save();
            $user->markEmailAsVerified();
            return view('email_verified');
        } else {
            return view('email_not_verified');
        }
    }
}
