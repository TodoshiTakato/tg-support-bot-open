<?php

declare(strict_types=1);

namespace App\Telegram\Handlers\Support\PrivateChats;


use App\Enums\Telegram\Locale;
use App\Telegram\Handlers\WithPattern;
use App\Telegram\Services\Support\ButtonService;
use Illuminate\Support\Carbon;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;


final class ChatWithOperatorHandler implements WithPattern
{

    /**
     * @return string
     */
    public static function pattern(): string
    {
        return
            trans(key: 'support.online_chat_with_operator', locale: Locale::RU->value)
            . '|' .
            trans(key: 'support.online_chat_with_operator', locale: Locale::UZ->value)
        ;
    }

    public function __invoke(Nutgram $bot, ButtonService $buttonService): void
    {
        $message = $bot->message(); // cause telegram update type is message
        $chatId = $message->chat->id;
        $user = $message->from;

        if ($chatId !== $user->id) {
            $bot->sendMessage(
                text: '<b>' . trans('support.errors.unauthorized_access') . '</b><br>' . trans('support.can_chat_only_in_private'),
                parse_mode: ParseMode::HTML,
            );
            return;
        }

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

        if ($tgUser->is_chatting) {
            $bot->sendMessage(
                text: '<b>' . trans('support.is_chatting_with_operator') . '</b>',
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->endChatWithOperator()
            );
            return;
        }

        $tgUser->is_chatting = true;
        $tgUser->save();

        $bot->sendMessage(
            text: '<b>' . trans('support.online_chat_with_operator') . '</b>' . PHP_EOL
            . PHP_EOL . trans('support.please_ask_your_question'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->endChatWithOperator()
        );
        // user's part ended

        // operators' group chat part started
        $dateTime = Carbon::createFromTimestamp($message->date)->format('d.m.Y H:i:s');

        $userMessage =
            'Date: [' . $dateTime . ']' . PHP_EOL
            . 'Message ID: ' . $message->message_id . PHP_EOL
            . 'User ID: ' . $user->id . PHP_EOL
            . 'User: ' . $user->first_name . ' ' . $user->last_name . PHP_EOL
            . 'Username: @' . $user->username . PHP_EOL
            . PHP_EOL
            . 'Text: "' . trans(key: 'support.online_chat_with_operator') . '"'
        ;

        $bot->sendMessage(
            text: $userMessage,
            chat_id: config('telegram.bots.support.chats.group_chat'),
            parse_mode: ParseMode::HTML,
        );
    }


    /**
     * @return string
     */
    public static function callbackPattern(): string
    {
        return 'support:start_chat_with_operator';
    }

    public function callbackHandler(Nutgram $bot, ButtonService $buttonService): void
    {
        $callbackQuery = $bot->callbackQuery(); // cause telegram update type is callbackQuery
        $bot->answerCallbackQuery($callbackQuery->id);

        $message = $callbackQuery->message;
        $chatId = $message->chat->id;
        $user = $callbackQuery->from;

        if ($chatId !== $user->id) {
            $bot->sendMessage(
                text: '<b>' . trans('support.errors.unauthorized_access') . '</b><br>' . trans('support.can_chat_only_in_private'),
                parse_mode: ParseMode::HTML,
            );
            return;
        }

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

        if ($tgUser->is_chatting) {
            $bot->sendMessage(
                text: '<b>' . trans('support.is_chatting_with_operator') . '</b>',
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->endChatWithOperator()
            );
            return;
        }

        $tgUser->is_chatting = true;
        $tgUser->save();

        $bot->sendMessage(
            text: '<b>' . trans('support.online_chat_with_operator') . '</b>' . PHP_EOL
            . PHP_EOL . trans('support.please_ask_your_question'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->endChatWithOperator()
        );
        // user's part ended

        // operators' group chat part started
        $dateTime = Carbon::now()->format('d.m.Y H:i:s'); // cause there's no $callbackQuery->date field


        $groupChatMessage =
            'Date: [' . $dateTime . ']' . PHP_EOL
            . 'CallbackQuery ID: ' . $callbackQuery->id . PHP_EOL
            . 'User ID: ' . $user->id . PHP_EOL
            . 'User: ' . $user->first_name . ' ' . $user->last_name . PHP_EOL
            . 'Username: @' . $user->username . PHP_EOL
            . PHP_EOL
            . 'Команда: "' . trans('support.want_to_start_chat_with_operator') . '"' . PHP_EOL // cause [$callbackQuery->data === self::callbackPattern()]
            . 'Ответ: "' . trans('support.yes') . '"'
        ;

        $bot->sendMessage(
            text: $groupChatMessage,
            chat_id: config('telegram.bots.support.chats.group_chat'),
            parse_mode: ParseMode::HTML,
        );
    }


    /**
     * @return string
     */
    public static function endChatCallbackPattern(): string // Callback data (route)
    {
        return 'support:end_chat_with_operator';
    }

    public function endChatCallbackHandler(Nutgram $bot, ButtonService $buttonService): void
    {
        $bot->answerCallbackQuery($bot->callbackQuery()->id);

        $callbackQuery = $bot->callbackQuery(); // cause telegram update type is callbackQuery

        $message = $callbackQuery->message;
        $chatId = $message->chat->id;
        $user = $callbackQuery->from;

        if ($chatId !== $user->id) {
            $bot->sendMessage(
                text: '<b>' . trans('support.errors.unauthorized_access') . '</b><br>' . trans('support.can_end_chat_only_in_private'),
                parse_mode: ParseMode::HTML,
            );
            return;
        }

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

        if (!$tgUser->is_chatting) {
            $bot->sendMessage(
                text: '<b>' . trans('support.is_chatting_with_operator') . '</b>',
                parse_mode: ParseMode::HTML,
                reply_markup: $buttonService->endChatWithOperator()
            );
            return;
        }

        $tgUser->is_chatting = false;
        $tgUser->save();

        $bot->sendMessage(
            text: trans('support.chat_with_operator_ended'),
            parse_mode: ParseMode::HTML,
            reply_markup: $buttonService->menu()
        );
        // user's part ended

        // operators' group chat part started
        $dateTime = Carbon::now()->format('d.m.Y H:i:s'); // cause there's no $callbackQuery->date field

        $groupChatMessage =
            'Date: [' . $dateTime . ']' . PHP_EOL
            . 'CallbackQuery ID: ' . $callbackQuery->id . PHP_EOL
            . 'User ID: ' . $user->id . PHP_EOL
            . 'User: ' . $user->first_name . ' ' . $user->last_name . PHP_EOL
            . 'Username: @' . $user->username . PHP_EOL
            . PHP_EOL
            . 'Команда: "' . trans('support.end_chat_with_operator') . '"' // cause [$callbackQuery->data === self::endChatCallbackPattern()]
        ;

        $bot->sendMessage(
            text: $groupChatMessage,
            chat_id: config('telegram.bots.support.chats.group_chat'),
            parse_mode: ParseMode::HTML,
        );

    }

}
