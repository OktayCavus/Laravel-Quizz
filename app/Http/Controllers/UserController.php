<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    // public function create_user(CreateUserRequest $request)
    // {
    //     $user = User::create(
    //         [
    //             'username' => $request->username,
    //             'password' => Hash::make($request->password),
    //             'email' => $request->email,
    //             'name' => $request->name,
    //             'lastname' => $request->lastname,
    //             'is_active' => 2,
    //             'role_id' => 2,
    //         ]
    //     );

    //     Mail::to($request->email)->send(new WelcomeMail([
    //         'name' => $request->name,
    //     ]));
    //     return $this->apiResponse('Kullanıcı başarıyla oluşturuldu', $user,  true, 200);
    // }

    public function create_user(CreateUserRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'is_active' => 2,
            'role_id' => 2,
        ]);

        event(new Registered($user));

        return $this->apiResponse('Kullanıcı başarıyla oluşturuldu', true, 200, $user);
    }

    public function delete_user(int $id)
    {
        $userToDelete = User::find($id);
        if (!$userToDelete) {
            return $this->apiResponse('Kullanıcı bulunamadı', false, 404);
        }
        $userToDelete->delete();
        return $this->apiResponse('Kullanıcı silme işlemi başarılı',  true, 200, $userToDelete);
    }


    public function get_user(int $id = 0)
    {
        if ($id != 0) {
            $user = User::find($id);
        } else {
            $user = User::withTrashed()->get();
        }

        if (!$user) {
            return $this->apiResponse('Kullanıcı bulunamadı', false, 404);
        }
        return $this->apiResponse('Kullanıcılar başarıyla getirildi', true, 200, $user);
    }

    public function reset_password(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Bu e-postaya ilişkin kullanıcı bulunamadı'
            ], 404);
        }

        $new_password = $request->new_password;
        $user->password = Hash::make($new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Şifre sıfırlama işlemi başarılı'
        ], 200);
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        $userToUpdate = User::find(auth()->user()->id);

        if (!$userToUpdate) {
            return $this->apiResponse('Kullanıcı bulunamadı', false, 404);
        }

        $userToUpdate->update($request->only(['name', 'lastname', 'username']));

        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $userToUpdate->password)) {
                return $this->apiResponse('Eski şifre hatalı.', false, 400);
            }
            $userToUpdate->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return $this->apiResponse('Profil başarıyla güncellendi', true, 200, $userToUpdate);
    }

    public function update_from_admin(UpdateProfileRequest $request)
    {
        $userToUpdate = User::find($request->id);
        if (!$userToUpdate) {
            return $this->apiResponse('Kullanıcı bulunamadı', false, 404);
        }
        $userToUpdate->update($request->only(['name', 'lastname', 'username']));
        if ($request->filled('current_password') && $request->filled('new_password')) {
            $userToUpdate->update([
                'password' => Hash::make($request->new_password),
            ]);
        }
        return $this->apiResponse('Profil başarıyla güncellendi', true, 200, $userToUpdate);
    }


    public function restore_user(int $id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return $this->apiResponse('Kullanıcı bulunamadı', false, 404);
        }
        $user->restore();
        return $this->apiResponse('Kullanıcı başarıyla geri getirildi', true, 200, $user);
    }


    public function role_name()
    {

        // $roleName = auth()->user()->role->role_name;
        // return $roleName;

        // $user = User::find(1);
        // $roleName = $user->role->role_name;
        // return $roleName;
    }
}
