<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\Messages;

use App\Models\TelegramUser;
use App\Telegram\Services\Support\ButtonService;
use Illuminate\Support\Carbon;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Media\Document;
use SergiX44\Nutgram\Telegram\Types\Media\Video;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use SergiX44\Nutgram\Telegram\Types\Message\ReplyParameters;
use SergiX44\Nutgram\Telegram\Types\User\User;


final class MessageHandler
{
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

        $message = $bot->update()->message;
        $messageId = $message->message_id;
        $messageDate = Carbon::createFromTimestamp($message->date)->format('d.m.Y H:i:s');
        $chatId = $message->chat->id;
        $user = $message->from;

        $messageText = $message->text;  // message text

        $messagePhotosArray = $message->photo; // array of Photo objects [{},{},{}]
        $messageCaption = $message->caption; // caption text

        $messageVideo = $message->video; // video
        $messageDocument = $message->document; // document

        $replyToMessage = $message->reply_to_message;

        $groupChatMessageText =
            'Date: [' . $messageDate . ']' . PHP_EOL
            . 'Message ID: ' . $messageId . PHP_EOL
            . 'User ID: ' . $user->id . PHP_EOL
            . 'User: ' . $user->first_name . ' ' . $user->last_name . PHP_EOL
            . 'Username: @' . $user->username . PHP_EOL
            . PHP_EOL
        ;

        // if a message doesn't contain a reply
        if (empty($replyToMessage)) {
            $this->handleMessages(
                $bot,
                $buttonService,
                $tgUser,
                $chatId,
                $user,
                $groupChatMessageText,
                $messageText,
                $messagePhotosArray,
                $messageCaption,
                $messageVideo,
                $messageDocument,
            );
            return;
        }

        // if a reply message
        $this->handleReplyMessages(
            $replyToMessage,
            $bot,
            $buttonService,
            $tgUser,
            $messageId,
            $chatId,
            $user,
            $groupChatMessageText,
            $messageText,
            $messagePhotosArray,
            $messageCaption,
            $messageVideo,
            $messageDocument,
        );
        return;
    }

    private function handleMessages(
        Nutgram $bot,
        ButtonService $buttonService,
        TelegramUser $tgUser,
        int $chatId,
        User $user,
        string $groupChatMessageText,
        ?string $messageText = null,
        ?array $messagePhotosArray = null,
        ?string $messageCaption = null,
        ?Video $messageVideo = null,
        ?Document $messageDocument = null,
    ): void
    {
        // if not a private chat, and bot is admin in group chat
        if ($chatId !== $user->id) {
//                $bot->sendMessage(
//                    text: trans('support.errors.unauthorized_access'),
//                    parse_mode: ParseMode::HTML,
//                );
            return;
        }

        if (true !== $tgUser->is_chatting) { // if user is not chatting with operator
            $bot->sendMessage(
                text: trans('support.want_to_start_chat_with_operator'),
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->askStartChatWithOperator(),
            );
            return;
        }

        $bot->sendMessage(
            text: trans('support.we_got_your_message'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->endChatWithOperator(),
        );

        if ($messagePhotosArray !== null) {
            $maxFileSize = 0;
            $maxPhotoObject = null;
            foreach ($messagePhotosArray as $photoObject) {
                if ($photoObject->file_size > $maxFileSize) {
                    $maxFileSize = $photoObject->file_size;
                    $maxPhotoObject = $photoObject;
                }
            }
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendPhoto(
                photo: $maxPhotoObject->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        if ($messageVideo !== null) {
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendVideo(
                video: $messageVideo->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        if ($messageDocument !== null) {
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendVideo(
                video: $messageDocument->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        $groupChatMessageText .= 'Text: "' . $messageText . '"' . PHP_EOL;

        $bot->sendMessage(
            text: $groupChatMessageText,
            chat_id: config('telegram.bots.support.chats.group_chat'),
            parse_mode: ParseMode::HTML,
        );
        // simple message ends here
        return;
    }

    private function handleReplyMessages(
        $replyToMessage,
        Nutgram $bot,
        ButtonService $buttonService,
        TelegramUser $tgUser,
        int $messageId,
        int $chatId,
        User $user,
        string $groupChatMessageText,
        ?string $messageText = null,
        ?array $messagePhotosArray = null,
        ?string $messageCaption = null,
        ?Video $messageVideo = null,
        ?Document $messageDocument = null,
    ): void
    {
        if ($replyToMessage->text === null) {
            $replyText = $replyToMessage->caption;
        } else {
            $replyText = $replyToMessage->text;
        }
//        dump($replyToMessage);
//        dd($replyToMessage->from);
        $replyFrom = $replyToMessage->from;

//        dump($chatId === config('telegram.bots.support.chats.group_chat'));
//        dump($replyFrom === config('telegram.bots.support.chats.self_chat_id'));
//        dd($chatId === config('telegram.bots.support.chats.group_chat') && $replyFrom === config('telegram.bots.support.chats.self_chat_id'));

//        dd($chatId, config('telegram.bots.support.chats.group_chat'));
//        dd($replyFrom->id, config('telegram.bots.support.chats.self_chat_id'));

        // if in a target group chat
        if (
            ($chatId === config('telegram.bots.support.chats.group_chat'))
            && ($replyFrom->id === config('telegram.bots.support.chats.self_chat_id'))
        ) {
            // OPERATOR'S REPLY TO USER'S MESSAGE starts here
            // reply to operator's reply parameter, to say that we sent operator's message successfully
            $replyToOperatorParameters = ReplyParameters::make(
                message_id: $messageId, // in this case operator's message, which is replying to user's message
                chat_id: $chatId, // in this case target group chat
                allow_sending_without_reply: true // send even if the operator's message was deleted
            );

            if ($replyText === null) {
                $bot->sendMessage( // ❌ fail
                    text: trans("support.couldn't_find_user_id"),
                    chat_id: $chatId, // in this case target group chat
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToOperatorParameters,
                );
                return;
            }

            $userIdExists = preg_match('/(?:User ID: )(\d+?)(?:\D)/u', $replyText, $userIdMatches);
            // $matches array:2 [ // app/Telegram/Handlers/Support/PrivateChats/Messages/TextHandler.php:83
            //   0 => "User ID: 305036511\n"
            //   1 => "305036511"
            // ]

            if (!$userIdExists) {
                $bot->sendMessage( // ❌ fail
                    text: trans("support.couldn't_find_user_id"),
                    chat_id: $chatId, // in this case target group chat
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToOperatorParameters,
                );
                return;
            }

            $userChatId = $userIdMatches[1];

            $dbUser = TelegramUser::find($userChatId);
            if ( empty($dbUser) || !$dbUser->is_chatting ) {
                $bot->sendMessage( // ✅ success
                    text: trans('support.reply_wasn\'t_sent'),
                    chat_id: $chatId, // in this case target group chat
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToOperatorParameters,
                );
                return;
            }

            $messageIdExists = preg_match('/(?:Message ID: )(\d+?)(?:\D)/u', $replyText, $messageIdMatches);
            if ($messageIdExists) {
                // reply to user's message
                $replyToUserParameters = ReplyParameters::make(
                    message_id: (int) $messageIdMatches[1], // in this case operator's message, reply to user's message
                    chat_id: $userChatId, // in this case target group chat
                    allow_sending_without_reply: true // send even if the operator's message was deleted
                );
            }

            if ($messagePhotosArray !== null) {
                $maxFileSize = 0;
                $maxPhotoObject = null;
                foreach ($messagePhotosArray as $photoObject) {
                    if ($photoObject->file_size > $maxFileSize) {
                        $maxFileSize = $photoObject->file_size;
                        $maxPhotoObject = $photoObject;
                    }
                }
                $bot->sendPhoto(
                    photo: $maxPhotoObject->file_id,
                    chat_id: $userChatId,
                    caption: $messageCaption,
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToUserParameters ?? null,
                );
                return;
            }

            if ($messageVideo !== null) {
                $bot->sendVideo(
                    video: $messageVideo->file_id,
                    chat_id: $userChatId,
                    caption: $messageCaption,
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToUserParameters ?? null,
                );
                return;
            }

            if ($messageDocument !== null) {
                $bot->sendVideo(
                    video: $messageDocument->file_id,
                    chat_id: $userChatId,
                    caption: $messageCaption,
                    parse_mode: ParseMode::HTML,
                    reply_parameters: $replyToUserParameters ?? null,
                );
                return;
            }

            $bot->sendMessage(
                text: $messageText,
                chat_id: $userChatId, // in this case user's private chat
                parse_mode: ParseMode::HTML,
                reply_parameters: $replyToUserParameters ?? null,
            );

            $bot->sendMessage( // ✅ success
                text: trans('support.reply_was_sent'),
                chat_id: $chatId, // in this case target group chat
                parse_mode: ParseMode::HTML,
                reply_parameters: $replyToOperatorParameters,
            );
            // operator's reply ends here
            return;
        }
        // In other usual private chats with users.
        // Users' replies start here.

        // if a reply in any other group chat
        if ($chatId !== $user->id) {
//            $bot->sendMessage(
//                text: trans('support.errors.unauthorized_access'),
//                parse_mode: ParseMode::HTML,
//            );
            return;
        }


        if (true !== $tgUser->is_chatting) { // if user is not chatting with operator
            $bot->sendMessage(
                text: trans('support.want_to_start_chat_with_operator'),
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->askStartChatWithOperator(),
            );
            return;
        }

        $bot->sendMessage(
            text: trans('support.we_got_your_message'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->endChatWithOperator(),
        );

        if ($messagePhotosArray !== null) {
            $maxFileSize = 0;
            $maxPhotoObject = null;
            foreach ($messagePhotosArray as $photoObject) {
                if ($photoObject->file_size > $maxFileSize) {
                    $maxFileSize = $photoObject->file_size;
                    $maxPhotoObject = $photoObject;
                }
            }
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendPhoto(
                photo: $maxPhotoObject->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        if ($messageVideo !== null) {
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendVideo(
                video: $messageVideo->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        if ($messageDocument !== null) {
            $groupChatMessageText .= 'Caption: "' . $messageCaption . '"' . PHP_EOL;
            $bot->sendVideo(
                video: $messageDocument->file_id,
                chat_id: config('telegram.bots.support.chats.group_chat'),
                caption: $groupChatMessageText,
                parse_mode: ParseMode::HTML,
            );
            return;
        }

        $groupChatMessageText .= 'Text: "' . $messageText . '"' . PHP_EOL;

        $bot->sendMessage(
            text: $groupChatMessageText,
            chat_id: config('telegram.bots.support.chats.group_chat'),
            parse_mode: ParseMode::HTML,
        );
        // users' replies ends here
    }
}
