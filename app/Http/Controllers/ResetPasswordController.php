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

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return $this->apiResponse(
                'Kodun süresi doldu',
                false,
                422
            );
        }

        $user = User::firstWhere('email', $passwordReset->email);

        if (!$user) {
            return $this->apiResponse(
                'Kullanıcı bulunamadı',
                false,
                404
            );
        }

        // update user password
        $user->update($request->only('password'));

        // delete current code
        $passwordReset->delete();

        return $this->apiResponse(
            'Şifre başarıyla değiştirildi'
        );
    }
}
