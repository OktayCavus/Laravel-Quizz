<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChangeEmailCodeCheckController;
use App\Http\Controllers\ChangeEmailController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'user'
    ],
    function () {
        Route::post('create', [UserController::class, 'create_user']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('aa', [UserController::class, 'role_name']);
        Route::post('password/email',  ForgotPasswordController::class);
        Route::post('password/reset', ResetPasswordController::class);
        Route::post('code/check', CodeCheckController::class);
        Route::post('change/email/code', [ChangeEmailController::class, 'changeEmailCode']);
        Route::post('change/email', [ChangeEmailController::class, 'changeEmail']);
        Route::post('code/email/check', ChangeEmailCodeCheckController::class);
    }
);

Route::prefix('auth')->middleware(['check'])->group(
    function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('update', [UserController::class, 'update_profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    }
);

Route::prefix('admin')->middleware(['is_admin'])->group(
    function () {
        Route::get('restore/{id}', [UserController::class, 'restore_user']);
        Route::get('delete-user/{id}', [UserController::class, 'delete_user']);
        Route::get('get-user/{id?}', [UserController::class, 'get_user']);
        Route::post('store-category', [CategoriesController::class, 'store']);
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
        Route::get('get-all-category', [CategoriesController::class, 'index']);
        Route::get('get-category/{id}', [CategoriesController::class, 'show']);

        Route::get('show-user-test/{id}', [AnswerController::class, 'show']);
    }
);

Route::prefix('questions')->middleware(['check'])->group(
    function () {
        Route::get('get-question-with-test', [QuestionController::class, 'index']);
        Route::get('get-selected-question/{id}', [QuestionController::class, 'show']);
        Route::post('submit-answer', [AnswerController::class, 'submitAnswer']);
        Route::get('get-test-answer/{id?}', [AnswerController::class, 'userTestAnswers']);
        Route::get('get-test-category-answer/{category_id?}/{test_id?}', [AnswerController::class, 'userTestCategoryAnswers']);
        Route::get('get_test_with_category', [AnswerController::class, 'userTestsWithCategory']);
    }
);
