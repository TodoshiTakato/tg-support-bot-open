<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares\Support;


use App\Enums\Telegram\Locale;
use SergiX44\Nutgram\Nutgram;


final class SetLocale
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $tgUser = $bot->get('user');
        if ($tgUser !== null) {
            app()->setLocale($tgUser->locale?->value ?? Locale::UZ->value);
        } else {
            app()->setLocale(Locale::UZ->value);
        }

        $next($bot);
    }
}
