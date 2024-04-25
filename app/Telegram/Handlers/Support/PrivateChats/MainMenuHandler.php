<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\PrivateChats;


use App\Enums\Telegram\Support\Callbacks\MainMenuCallbackDataEnum;
use App\Enums\Telegram\Locale;
use App\Telegram\Handlers\WithPattern;
use App\Telegram\Services\Support\ButtonService;
use JetBrains\PhpStorm\NoReturn;
use SergiX44\Nutgram\Nutgram;


final class MainMenuHandler implements WithPattern
{
    /**
     * @return string
     */
    public static function pattern(): string
    {
        return trans(key: 'support.main_menu', locale: Locale::RU->value)
            . '|' . trans(key: 'support.main_menu', locale: Locale::UZ->value);
    }

    /**
     * @param Nutgram $bot
     * @param ButtonService $buttonService
     * @return void
     */
    #[NoReturn]
    public function __invoke(Nutgram $bot, ButtonService $buttonService): void
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
            );
            return;
        }

        $bot->sendMessage(
            text: trans('support.main_menu'),
            reply_markup: $buttonService->menu()
        );
    }


    /**
     * @return string
     */
    public static function backReplyPattern(): string
    {
        return trans(key: 'support.back', locale: Locale::RU->value)
            . '|' . trans(key: 'support.back', locale: Locale::UZ->value);
    }

    /**
     * @param Nutgram $bot
     * @param ButtonService $buttonService
     * @return void
     */
    #[NoReturn]
    public function backReplyHandler(Nutgram $bot, ButtonService $buttonService): void
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
            );
            return;
        }

        $bot->sendMessage(
            text: trans('support.main_menu'),
            reply_markup: $buttonService->menu()
        );
    }


    /**
     * @return string
     */
    public static function callbackPattern(): string
    {
        return MainMenuCallbackDataEnum::BACK_TO_MAIN_MENU->value;
    }

    /**
     * @param Nutgram $bot
     * @param ButtonService $buttonService
     * @return void
     */
    #[NoReturn]
    public function backToMainMenuCallbackHandler(Nutgram $bot, ButtonService $buttonService): void
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
            );
            return;
        }

        $bot->answerCallbackQuery($bot->callbackQuery()->id);

        $bot->sendMessage(
            text: trans('support.main_menu'),
            reply_markup: $buttonService->menu()
        );
    }

    #[NoReturn]
    public static function helper(Nutgram $bot, ButtonService $buttonService): void
    {
        $bot->sendMessage(
            text: trans('support.main_menu'),
            reply_markup: $buttonService->menu()
        );
    }
}
