<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('auth/register', [RegisteredUserController::class, 'create'])
        ->name('register-form');

    Route::post('auth/register', [RegisteredUserController::class, 'store'])
    ->name('register');

    Route::get('auth/login', [AuthenticatedSessionController::class, 'create'])
        ->name('auth.login.form');

    Route::post('auth/login', [AuthenticatedSessionController::class, 'store'])
    ->name('auth.login');

    Route::get('auth/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('auth.forgot-password.form');

    Route::post('auth/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('auth.forgot-password');

    Route::get('auth/reset-password', [NewPasswordController::class, 'create'])
        ->name('auth.password.reset.form');

    Route::post('auth/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('auth/verify-email', [EmailVerificationPromptController::class, ' __invoke'])
        ->name('verification.notice');

    Route::get('auth/email/verify/' ,[VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('auth.email.verify');

    Route::get('auth/email/send-verification', [EmailVerificationNotificationController::class, 'send'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send.email');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('auth.logout');
});
