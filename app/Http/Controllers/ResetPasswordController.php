<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return $this->apiResponse(
                'Kodun süresi doldu',
                false,
                422
            );
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code
        $passwordReset->delete();

        return $this->apiResponse(
            'Şifre başarıyla değiştirildi'
        );
    }
}
