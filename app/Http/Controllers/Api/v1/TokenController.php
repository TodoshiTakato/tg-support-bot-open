<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class TokenController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function generateSecretTokenForTelegramBotWebHook()
    {
        $secretToken = Str::ulid();
        $date = Carbon::createFromId((string) Str::ulid())->tz('Asia/Tashkent');

        Log::stack(['tokens_v1', 'support_bot'])->info(
            "Generated new secret_token for Telegram-bot webhook protection:" . PHP_EOL
            . $secretToken->toRfc4122() . PHP_EOL
            . 'ULID time: ' . $date . PHP_EOL
        );

        return response()->json([ "secret_token" => $secretToken->toRfc4122() ]);
    }
}
