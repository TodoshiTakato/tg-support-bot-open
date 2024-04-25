<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Support\WebhookController as SupportWebhookController;

use App\Http\Controllers\Api\v1\TokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// support webhook
// http://localhost:8666/api/support/{$botToken}/webhook
// https://haddock-cheerful-weekly.ngrok-free.app/api/support/{$botToken}/webhook
Route::post(config('telegram.bots.support.webhook-uri'), SupportWebhookController::class)
    ->middleware(['verifyWebHookSecretToken', 'throttle:90,1'])
;

// domain/api/v1
Route::namespace('App\Http\Controllers\Api\v1')->prefix('/v1')->group(function () { // ->middleware(['auth',])

    Route::controller(TokenController::class)->group(function () {
        Route::prefix('tokens')->group(function () { // domain/api/v1/tokens
            Route::prefix('generate')->group(function () { // domain/api/v1/tokens/generate
                Route::prefix('secret_token')->group(function () { // domain/api/v1/tokens/generate/secret_token
                    // domain/api/v1/tokens/generate/secret_token/for_telegram_webhook
                    Route::get('/for_telegram_webhook', 'generateSecretTokenForTelegramBotWebHook');
                });
            });
        });
    });

});
