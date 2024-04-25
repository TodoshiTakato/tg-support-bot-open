<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares\Support;

use App\Enums\Telegram\Locale;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Properties\UpdateType;


final class AttachUser
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $update = $bot->update();
        $updateType = $update->getType();

        $user = $bot->user();
        $chat = $bot->chat();

        $userId = $user->id;
//        $userIsBot = $user->is_bot;
        $userFirstName = $user->first_name;
        $userLastName = $user->last_name;
        $userUsername = $user->username;
//        $userIsPremium = $user->is_premium;
//        $userAddedToAttachmentMenu = $user->added_to_attachment_menu;
        $chatId = $chat->id;
        $chatType = $chat->type;
        $chatTitle = $chat->title;
        $chatUsername = $chat->username;
//        $chatFirstName = $chat->first_name;
//        $chatLastName = $chat->last_name;
//        $chatIsForum = $chat->is_forum;

        $dbUser = TelegramUser::find($userId);

        if (empty($dbUser)) {
            $dbUser = TelegramUser::create([
                'id'                  => $userId,
                'chat_id'             => ($chatId > 0) ? $chatId : null,
                'group_chat_id'       => ($chatId < 0) ? (-$chatId) : null, // save Negative group_chat_id in UNSIGNED BIGINT (from 0 to 18446744073709551615)
                'is_active'           => false,
                'is_chatting'         => false,
//                'locale'              => Locale::RU->value, // must be null for first time language selecting by user
//                'locale'              => Locale::UZ->value, // must be null for first time language selecting by user
                'phone_number'        => null,
                'name'                => $userFirstName . ' ' . $userLastName,
                'username'            => $userUsername,
                'group_chat_name'     => ($chatId < 0) ? $chatTitle : null,
                'group_chat_username' => ($chatId < 0) ? $chatUsername : null,
                'chat_type'           => $chatType,
            ]);
        }

        if ( // group chats other than target group
            ($chatId < 0) // if group chat
            && ($chatId !== config('telegram.bots.support.chats.group_chat')) // and not target group chat
            && (UpdateType::MY_CHAT_MEMBER !== $updateType) // and update type is not MY_CHAT_MEMBER (when the bot was just added)
        ) {
            $chatInfo = $bot->getChat(chat_id: $chatId);
            $bot->leaveChat(chat_id: $chatId);
            $dbUser->is_active = TelegramUser::BLOCKED;
            $dbUser->save();
            $bot->sendMessage(
                text: 'Chat info from `getChat()` method:' . PHP_EOL . prettyPrintObjectProperties($chatInfo),
                chat_id: config('telegram.bots.support.chats.error_reports_channel'),
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        $bot->set('user', $dbUser);

        $next($bot);
    }

}
