<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Support;


use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\TelegramUser;


final class OnApiErrorAction
{
    public function __invoke(Nutgram $bot, TelegramException $exception, Request $request): void
    {
        if ($exception->getCode() === 403) {
            if ("Forbidden: bot was blocked by the user" === $exception->getMessage()) {
                $tgUser = $bot->get('user');
                if (empty($tgUser)) {
                    $txt = "There's no tgUser (likely wasn't attached)." . PHP_EOL
                        . 'Class name: ' . self::class . PHP_EOL
                        . 'Line: ' . __LINE__
                    ;
                    $bot->sendMessage(
                        text: $txt,
                        chat_id: config('telegram.bots.support.chats.error_reports_channel'),
                        parse_mode: ParseMode::HTML,
                    );
                    return;
                }
                $tgUser->is_active = TelegramUser::BLOCKED;
                $tgUser->save();
                return;
            } elseif ("Forbidden: bot is not a member of the group chat" === $exception->getMessage()) {
                return;
            }
        }

//        $errorMessage = $exception->getParameters();
//        dd($errorMessage);
//        array:1 [ // app/Telegram/Actions/Support/OnApiErrorAction.php:41
//            "migrate_to_chat_id" => -1002047229319
//        ]

        $errorMessage = "Ошибка при отправке request'a в Телеграм!" . PHP_EOL
            . 'onApiErrorAction' . PHP_EOL
            . 'user: '       . $bot->user()?->first_name . " " . $bot->user()?->last_name . PHP_EOL
            . 'user_id: '    . $bot->userId() . PHP_EOL
            . "username: @"  . $bot->user()?->username . PHP_EOL
            . 'chat_id: '    . $bot->chatId() . PHP_EOL
            . 'http_code('   . $exception->getCode() . ')' . PHP_EOL
            . 'Message: '    . $exception->getMessage() . PHP_EOL
            . "text: \""     . $bot->message()?->text . "\"" . PHP_EOL
            . "Parameters:"  . PHP_EOL
            . json_encode($exception->getParameters(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE |
                JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE
            )
        ;
        Log::channel('support_bot_errors')->error($errorMessage . PHP_EOL);

        $bot->sendMessage(
            text: $errorMessage,
            chat_id: config('telegram.bots.support.chats.error_reports_channel'),
            parse_mode: ParseMode::HTML,
        );
    }
}
