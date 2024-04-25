<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares\Support\PrivateChats;

use App\Telegram\Middlewares\Abstract\PrivateChatsOnlyMiddleware;
use SergiX44\Nutgram\Nutgram;

final class PrivateChatMiddleware extends PrivateChatsOnlyMiddleware
{
    protected function handle(Nutgram $bot, $next): void
    {
        // do smth only in private chats
    }
}
