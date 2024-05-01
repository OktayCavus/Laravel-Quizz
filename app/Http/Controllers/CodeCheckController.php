<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeCheckRequest as RequestsCodeCheckRequest;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{

    public function __invoke(RequestsCodeCheckRequest $request)
    {
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return $this->apiResponse('Kullanılmış veya süresi dolmuş kod', false, 422);
        }

        return $this->apiResponse('Kullanılabilir kod', true, 200, ['code' => $passwordReset->code]);
    }
}
