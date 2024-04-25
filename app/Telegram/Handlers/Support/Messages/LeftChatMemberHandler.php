<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\Messages;

use SergiX44\Nutgram\Nutgram;


final class LeftChatMemberHandler
{
    public function __invoke(Nutgram $bot): void
    {
        // Bot was kicked from or left group chat
        // Or some user or bot was kicked from or left group chat
        return;
    }
}
