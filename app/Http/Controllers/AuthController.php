<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if (!$user) {
            return $this->apiResponse('Kullanıcı bulunamadı.', false, 404);
        }

        if ($user->is_active == 2) {
            return $this->apiResponse('Hesap aktif değil.', false, 403);
        }

        $credentials = $request->has('email') ? request(['email', 'password']) : request(['username', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->apiResponse('E-posta adresi veya şifre hatalı.', false, 401);
        }

        return $this->apiResponse(
            'Kullanıcı başarıyla giriş yaptı',
            true,
            200,
            [
                'user' => Auth::user(),
                'token' => $token
            ]
        );
    }

    public function logout()
    {

        JWTAuth::invalidate(JWTAuth::getToken());

        Auth::logout();

        return $this->apiResponse('Kullanıcı başarıyla çıkış yaptı');
    }


    public function me()
    {
        return response()->json(auth()->user());
    }
}
