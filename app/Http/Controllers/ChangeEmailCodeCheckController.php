<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailCodeRequest;
use App\Models\ChangeEmailModel;

class ChangeEmailCodeCheckController extends Controller
{
    public function __invoke(ChangeEmailCodeRequest $request)
    {
        $passwordReset = ChangeEmailModel::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return $this->apiResponse('Kullanılmış veya süresi dolmuş kod', false, 422);
        }

        return $this->apiResponse('Kullanılabilir kod', true, 200, ['code' => $passwordReset->code]);
    }
}
