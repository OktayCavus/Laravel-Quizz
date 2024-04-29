<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'user'
    ],
    function () {
        Route::post('create', [UserController::class, 'create_user']);
        Route::post('login', [AuthController::class, 'login']);
        // Route::post('reset-password', [UserController::class, 'reset_password']);
        Route::get('aa', [UserController::class, 'role_name']);
        Route::post('password/email',  ForgotPasswordController::class);
        Route::post('password/reset', ResetPasswordController::class);
    }
);

Route::prefix('auth')->middleware(['check'])->group(
    function () {
        Route::post('me', [AuthController::class, 'me']);
        Route::post('update', [UserController::class, 'update_profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    }
);

Route::prefix('admin')->middleware(['is_admin'])->group(
    function () {
        Route::get('restore/{id}', [UserController::class, 'restore_user']);
        Route::get('delete-user/{id}', [UserController::class, 'delete_user']);
        Route::get('get-user/{id?}', [UserController::class, 'get_user']);
        Route::post('store-test', [CategoriesController::class, 'store']);
        Route::get('get-category/{id}', [CategoriesController::class, 'show']);
        Route::get('get-all-category', [CategoriesController::class, 'index']);
        Route::get('delete-category/{id}', [CategoriesController::class, 'destroy']);
        Route::post('update-user-from-admin', [UserController::class, 'update_from_admin']);
        Route::post('store-test', [TestsController::class, 'store']);
        Route::get('delete-test', [TestsController::class, 'destroy']);
        Route::post('update-test', [TestsController::class, 'update']);
        Route::post('create-question', [QuestionController::class, 'store']);
        Route::put('update-question/{id}', [QuestionController::class, 'update']);
        Route::get('delete-question/{id}', [QuestionController::class, 'destroy']);
    }
);

Route::prefix('tests')->middleware(['check'])->group(
    function () {
        Route::get('get-test/{id}', [TestsController::class, 'show']);
    }
);

Route::prefix('questions')->middleware(['check'])->group(
    function () {
        // Route::get('get-question-with-test/{id?}', [QuestionController::class, 'index']);
        Route::get('get-question-with-test', [QuestionController::class, 'index']);
        Route::get('get-selected-question/{id}', [QuestionController::class, 'show']);
    }
);
