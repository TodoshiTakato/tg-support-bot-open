<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\Messages;

use SergiX44\Nutgram\Nutgram;


final class NewChatMembersHandler
{
    public function __invoke(Nutgram $bot): void
    {
        // Bot was added into group chat
        // Or some user or bot was added into group chat
        return;
    }
}
