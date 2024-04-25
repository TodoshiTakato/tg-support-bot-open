<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Support;


use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;

use Bschmitt\Amqp\Exception\Configuration;

use App\Telegram\Services\Clients\ResenderService;
use App\Telegram\Services\Support\ButtonService;


final class FallbackAction
{
    /**
     * @param Nutgram $bot
     * @param Request $request
     * @param ButtonService $buttonService
     * @param ResenderService $resenderService
     * @return void
     */
    public function __invoke(Nutgram $bot, Request $request, ButtonService $buttonService, ResenderService $resenderService): void
    {
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

        $errorMessage = "Error! No match found for Update in route list!" . PHP_EOL
            . "Was catched in FallbackAction." . PHP_EOL
            . "user: " . $bot->user()?->first_name . " " . $bot->user()?->last_name . PHP_EOL
            . "user_id: " . $bot->userId() . PHP_EOL
            . "username: @" . $bot->user()?->username . PHP_EOL
            . "chat_id: " . $bot->chatId() . PHP_EOL
            . "text: \"" . $bot->message()?->text . "\"" . PHP_EOL
            . "callback_data: \"" . $bot->callbackQuery()?->data . "\"";
        Log::channel('support_bot_errors')->error($errorMessage . PHP_EOL);

        $bot->sendMessage(
            text: $errorMessage,
            chat_id: config('telegram.bots.support.chats.error_reports_channel'),
            parse_mode: ParseMode::HTML,
        );

//        $bot->sendMessage(
//            text: trans('support.error_fallback'),
//            chat_id: config('telegram.bots.support.chats.group_chat'),
//            parse_mode: ParseMode::HTML,
//        );
    }
}
