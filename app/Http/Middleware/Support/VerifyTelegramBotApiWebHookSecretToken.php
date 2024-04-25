<?php

namespace App\Http\Middleware\Support;

use Closure;
use Illuminate\Http\Request;

class VerifyTelegramBotApiWebHookSecretToken
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if( !$request->hasHeader('x-telegram-bot-api-secret-token') ) {
            return response()->json([
                'error' => 'Access denied'
            ], 403);
        }

        if($request->header('x-telegram-bot-api-secret-token') !== config('telegram.bots.support.webhook_secret_token')) {
            return response()->json([
                'error' => "Incorrect value of 'X-Telegram-Bot-Api-Secret-Token' header"
            ], 401);
        }

        return $next($request);
    }

}
