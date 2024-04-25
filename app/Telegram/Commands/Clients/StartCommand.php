<?php

declare(strict_types=1);

namespace App\Telegram\Commands\Clients;


use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

use App\Models\TelegramUser;
use App\Telegram\Services\Support\ButtonService;


class StartCommand extends Command
{
    protected string $command = 'start';

    protected ?string $description = "/start command";

    public function handle(Nutgram $bot, ButtonService $buttonService): void
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

        if ($tgUser->locale !== null) { // if user have already chosen language
            $bot->sendMessage(
                text: trans('support.main_menu'),
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->menu()
            );
            return;
        }

        $bot->sendMessage( // choose language
            text: trans('support.greetings_language', ['name' => $tgUser->name]),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->languagesFirstTime()
        );
    }
}
