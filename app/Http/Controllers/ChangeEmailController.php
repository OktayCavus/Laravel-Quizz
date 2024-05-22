<?php

namespace App\Http\Controllers;

use App\Mail\ChangeEmail;
use App\Models\ChangeEmailModel;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ChangeEmailController extends Controller
{
    public function changeEmailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $userToUpdateEmail = User::find(auth()->user()->id);

        ChangeEmailModel::where('email', auth()->user()->email)->delete();

        $codeData = ChangeEmailModel::create([
            'email' => auth()->user()->email,
            'code' => Str::random(6),
        ]);

        Mail::to(auth()->user()->email)->send(new ChangeEmail(
            auth()->user()->email,
            $codeData->code,
            $request->email
        ));

        return $this->apiResponse('E-mail değiştirmek için e-postanıza kod yolladık', true, 200, $userToUpdateEmail);
    }

    public function changeEmail(Request $request)
    {
        $changeEmail = ChangeEmailModel::firstWhere('code', $request->code);

        if (!$changeEmail) {
            return $this->apiResponse(
                'Kod bulunamadı',
                false,
                404
            );
        }

        if ($changeEmail->created_at < now()->subHours(3)) {
            $changeEmail->delete();
            return $this->apiResponse(
                'Kodun süresi doldu',
                false,
                422
            );
        }

        $user = User::firstWhere('email', $changeEmail->email);

        if (!$user) {
            return $this->apiResponse(
                'Kullanıcı bulunamadı',
                false,
                404
            );
        }

        $user->update([
            'email' => $request->email
        ]);

        $changeEmail->delete();

        return $this->apiResponse(
            'Email başarıyla değiştirildi',
            true,
            200
        );
    }
}
