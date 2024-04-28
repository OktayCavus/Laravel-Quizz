<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Mail\WelcomeMail;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            // Delete all old code that user sent before.
            ResetCodePassword::where('email', $request->email)->delete();

            // Generate random code
            $data['code'] = mt_rand(100000, 999999);

            // Create a new code
            $codeData = ResetCodePassword::create($data);

            // Send email to user
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
