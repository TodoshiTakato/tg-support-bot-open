<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Support;


use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

use Throwable;

use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\Log;


final class OnExceptionAction
{
    private const NOT_REPORTABLE = [
        UnauthorizedException::class
    ];

    public function __invoke(Nutgram $bot, Throwable $exception): void
    {
        if (!in_array($exception::class, self::NOT_REPORTABLE)) {

            $errorMessage = 'Ошибка OnExceptionAction' . PHP_EOL
                . '<b>user_id: ' . $bot->userId() . '</b>' . PHP_EOL
                . '<b>Exception: ' . $exception->getMessage() . '</b>'
            ;

            Log::channel('support_bot_errors')->error(
                str_replace(['<b>','</b>'], '', $errorMessage) . PHP_EOL
                . 'Exception stack trace: ' . $exception->getTraceAsString() . PHP_EOL
            );

            $bot->sendMessage(
                text: $errorMessage,
                chat_id: config('telegram.bots.support.chats.error_reports_channel'),
                parse_mode: ParseMode::HTML,
            );

        }
    }
}
