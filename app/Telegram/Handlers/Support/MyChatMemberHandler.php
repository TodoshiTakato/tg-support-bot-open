<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support;

use App\Enums\Telegram\MarkdownEscapedCharsEnum;
use App\Enums\Telegram\MarkdownSpecialCharsEnum;
use App\Models\TelegramUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ChatMemberStatus;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;


final class MyChatMemberHandler
{
    public function __invoke(Nutgram $bot): void
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

        $chatMember = $bot->chatMember();

        $chat = $chatMember->chat;
        $from = $chatMember->from;
        $date = $chatMember->date;

        $oldChatMember = $chatMember->old_chat_member;
        $newChatMember = $chatMember->new_chat_member;

        $dateTime = Carbon::createFromTimestamp($date)->toDateTimeString();

        $oldChatMemberStatus = $oldChatMember->status;
        $newChatMemberStatus = $newChatMember->status;
        $botUsername = $newChatMember->user->username;

        $chatId = $chat->id;
        $chatTitle = $chat->title;
        $chatType = $chat->type->value;
        $userId = $from->id;
        $userFirstName = $from->first_name;
        $userLastName  = $from->last_name;
        $userUserName  = $from->username;

        $data = [
            'dateTime' => $dateTime,
            'botUsername' => $newChatMember->user->username,

            'chatId' => $chat->id,
            'chatTitle' => $chat->title,
            'chatType' => $chat->type->value,

            'userId' => $from->id,
            'userFirstName' => $from->first_name,
            'userLastName' => $from->last_name,
            'userUserName' => $from->username,
            'oldChatMemberStatus' => $oldChatMember->status,
        ];


        // private chat
        if ($data['chatId'] === $data['userId']) {
            $this->handlePrivateChats($bot, $newChatMemberStatus, $data, $tgUser);
            return;
        }

        if (
            ($chatId !== config('telegram.bots.support.chats.group_chat'))
            && (
                (ChatMemberStatus::ADMINISTRATOR === $newChatMemberStatus)
                || (ChatMemberStatus::MEMBER === $newChatMemberStatus)
            )
        ) {
            $this->handleExitGroupChats($bot, $data, $tgUser);
            return;
        }

        // group chat
        $this->handleGroupChats($bot, $newChatMemberStatus, $data, $tgUser);
        return;
    }

    private function sendMessageAndLog(Nutgram $bot, string $message): void
    {
        $bot->sendMessage(
            text: '<b>' . $message . '</b>',
            chat_id: config('telegram.bots.support.chats.error_reports_channel'),
            parse_mode: ParseMode::HTML,
        );
//        Log::channel('support_bot')->info('3) ' . $message . PHP_EOL);
    }

    private function handlePrivateChats(Nutgram $bot, ChatMemberStatus $status, array $data, TelegramUser $tgUser): void
    {
        $message = 'Bot ';

        if (ChatMemberStatus::KICKED === $status) // Bot stopped at
        {
            $message .= 'stopped';
            $tgUser->is_active = TelegramUser::BLOCKED;
        }
        elseif (ChatMemberStatus::MEMBER === $status) // Bot activated
        {
            $message .= 'activated';
            $tgUser->is_active = TelegramUser::ACTIVE;
        }
        else // Unhandleable case
        {
            $message = 'Unhandleable case in private chat on MyChatMember type Update! Happened' . PHP_EOL;
        }

        $message .= ' at [' . $data['dateTime'] . '] by:' . PHP_EOL
            . 'User ID: ' . $data['userId'] . PHP_EOL
            . 'User: ' . $data['userFirstName'] . ' ' . $data['userLastName'] . PHP_EOL
            . 'Username: @' . $data['userUserName']
        ;

        if ($tgUser->isDirty('is_active')) {
            $tgUser->save();
        }

        $this->sendMessageAndLog($bot, $message);
    }

    private function handleGroupChats(Nutgram $bot, ChatMemberStatus $status, array $data, TelegramUser $tgUser): void
    {
        $message = 'Bot ';

        if (ChatMemberStatus::KICKED === $status) // Bot was kicked out from group
        {
            $message .= 'was kicked out from group';
            $tgUser->is_active = TelegramUser::BLOCKED;
        }
        elseif (ChatMemberStatus::LEFT === $status) // Bot has left from group
        {
            $message .= 'has left group';
            $tgUser->is_active = TelegramUser::BLOCKED;
        }
        elseif (ChatMemberStatus::MEMBER === $status) // Bot activated at
        {
            $statusMessage = 'activated';
            if (ChatMemberStatus::ADMINISTRATOR === $data['oldChatMemberStatus']) // Bot was stripped off admin rights
            {
                $statusMessage = 'was stripped off admin rights';
            }
            $message .= $statusMessage;
            $tgUser->is_active = TelegramUser::ACTIVE;
        }
        elseif (ChatMemberStatus::ADMINISTRATOR === $status) // Bot got admin rights
        {
            $adminRightsString = prettyPrintObjectProperties($bot->chatMember()->new_chat_member);
            $message .= 'became admin in group with rights:' . $adminRightsString . PHP_EOL; // Bot became admin
            $tgUser->is_active = TelegramUser::ACTIVE;
        }
        else // Unhandleable case
        {
            $message = 'Unhandleable case in group chat on MyChatMember type Update! Happened' . PHP_EOL;
        }

        $message .= ' at [' . $data['dateTime'] . ']' . PHP_EOL
            . 'in Chat:' . PHP_EOL
            . 'chatId: ' . $data['chatId'] . PHP_EOL
            . 'chatTitle: ' . $data['chatTitle'] . PHP_EOL
            . 'chatType: ' . $data['chatType'] . PHP_EOL
            . PHP_EOL
            . 'by:' . PHP_EOL
            . 'User ID: ' . $data['userId'] . PHP_EOL
            . 'User: ' . $data['userFirstName'] . ' ' . $data['userLastName'] . PHP_EOL
            . 'Username: @' . $data['userUserName']
        ;

        if ($tgUser->isDirty('is_active')) {
            $tgUser->save();
        }

        $this->sendMessageAndLog($bot, $message);
    }

    private function handleExitGroupChats(Nutgram $bot, array $data, TelegramUser $tgUser): void
    {
        $chatInfo = $bot->getChat(chat_id: $data['chatId']);

        $specialChars = MarkdownSpecialCharsEnum::values(); // array_column(MarkdownSpecialCharsEnum::cases(), 'value')
        $escapedChars = MarkdownEscapedCharsEnum::values(); // array_column(MarkdownEscapedCharsEnum::cases(), 'value')

        $msg = 'Bot @' . $data['botUsername'];
        $msg .= ' was added into an unauthorized group chat.' . PHP_EOL;

        $bot->sendMessage(
            text: $msg . 'Exiting...',
            chat_id: $data['chatId'],
            parse_mode: ParseMode::HTML,
        );
        $bot->leaveChat(chat_id: $data['chatId']);
        $tgUser->is_active = TelegramUser::BLOCKED;
        $tgUser->save();

        $msg .= 'MyChatMember Update info:' . PHP_EOL;
        $msg = str_replace($specialChars, $escapedChars, $msg);
        $msg .= '```' . PHP_EOL;
        $code = prettyPrintObjectProperties($bot->chatMember()) . PHP_EOL;
        $msg .= str_replace($specialChars, $escapedChars, $code);
        $msg .= '```' . PHP_EOL;

        $msg .= 'Chat info from `getChat()` method:' . PHP_EOL;
        $msg .= '```' . PHP_EOL;
        $code = prettyPrintObjectProperties($chatInfo) . PHP_EOL;
        $msg .= str_replace($specialChars, $escapedChars, $code);
        $msg .= '```';

        $bot->sendMessage(
            text: $msg,
            chat_id: config('telegram.bots.support.chats.error_reports_channel'),
            parse_mode: ParseMode::MARKDOWN,
        );

        return;
    }
}
