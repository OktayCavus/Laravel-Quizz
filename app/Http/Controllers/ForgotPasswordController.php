<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Mail\WelcomeMail;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        try {

            ResetCodePassword::where('email', $request->email)->delete();

            $codeData = ResetCodePassword::create($request->data());

            Mail::to($request->email)->send(new SendCodeResetPassword(
                $request->email,
                $codeData->code,
            ));
            return $this->apiResponse('Parola sıfırlama kodu ' . $request->email . ' adresine gönderildi.', true, 200);
        } catch (\Exception $e) {
            return $this->apiResponse('Mail gönderilirken bir hata oluştu.', false, 500);
        }
    }
}
