<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\PrivateChats;


use App\Enums\Telegram\Locale;
use App\Telegram\Handlers\WithPattern;
use App\Telegram\Services\Support\ButtonService;
use JetBrains\PhpStorm\NoReturn;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

// Attributes php8

// interface


final class LanguageHandler implements WithPattern
{
    /**
     * @return string
     */
    public static function pattern(): string
    {
        return
            trans(key: 'support.change_language', locale: Locale::UZ->value)
            . '|' .
            trans(key: 'support.change_language', locale: Locale::RU->value)
        ;
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
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        $message = trans('support.current_language') . PHP_EOL . PHP_EOL;
        $message .= trans('support.select_language');

        $bot->sendMessage(
            text: $message,
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->languages()
        );
    }


    /**
     * @return string
     */
    public static function langPattern(): string
    {
        return trans(key: 'support.language', locale: Locale::RU->value)
            . '|' . trans(key: 'support.language', locale: Locale::UZ->value);
    }


    /**
     * @param Nutgram $bot
     * @param ButtonService $buttonService
     * @return void
     */
    #[NoReturn]
    public static function langHandler(Nutgram $bot, ButtonService $buttonService): void
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

        $localeRu = trans(key: 'support.language', locale: Locale::RU->value);
        $localeUz = trans(key: 'support.language', locale: Locale::UZ->value);

        $locale = $bot->message()->text;
        if ($locale === $localeRu) {
            $tgUser->locale = Locale::RU->value;
        } elseif ($locale === $localeUz) {
            $tgUser->locale = Locale::UZ->value;
        } else {
            $bot->sendMessage(
                text: trans(key: 'support.error_fallback'),
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->menu()
            );
            return;
        }

        $tgUser->save();

        app()->setLocale($tgUser->locale->value);

        $bot->sendMessage(
            text: trans(key: 'support.language_changed'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->menu()
        );

    }

}
