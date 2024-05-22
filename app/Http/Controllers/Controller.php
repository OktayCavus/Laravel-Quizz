<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function apiResponse(string $message = "",  bool $status = true, int $statusCode = 200, $data = null)
    {
        return response()->json(
            [
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ],
            $statusCode
        );
    }

    protected function is_admin()
    {
        $user = Auth::user();
        if ($user->role_id != 1) {
            return false;
        } else {
            return true;
        }
    }
}
