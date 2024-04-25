<?php

declare(strict_types=1);

namespace App\Telegram\Services\Support;


use App\Enums\Telegram\Locale;
use App\Enums\Telegram\Support\Callbacks\MainMenuCallbackDataEnum;
use App\Telegram\Handlers\Support\PrivateChats\ChatWithOperatorHandler;
use App\Telegram\Handlers\Support\PrivateChats\MainMenuHandler;
use App\Telegram\Handlers\Support\PrivateChats\PhoneNumberHandler;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;


final class ButtonService
{
    public function languagesFirstTime(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)
            ->addRow(
                KeyboardButton::make(text: trans(key: 'support.language', locale: Locale::UZ->value))
            )
            ->addRow(
                KeyboardButton::make(text: trans(key: 'support.language', locale: Locale::RU->value)),
            )
        ;
    }

    public function languages(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)
            ->addRow(
                KeyboardButton::make(text: trans(key: 'support.language', locale: Locale::UZ->value))
            )
            ->addRow(
                KeyboardButton::make(text: trans(key: 'support.language', locale: Locale::RU->value)),
            )
            ->addRow(
                KeyboardButton::make(text: trans(key: 'support.back'))
            )
        ;
    }

    public function menu(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)
            ->addRow(
                KeyboardButton::make(trans('support.online_chat_with_operator')),
            )
            ->addRow(
                KeyboardButton::make(trans('support.change_language')),
            )
        ;
    }

    public function backButton(?string $callbackDataRoute): InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    text: trans('support.back'),
                    callback_data: $callbackDataRoute
                )
            )
        ;
    }

    public function replyBackButton(?string $keyboardButtonTranslatedText): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)
            ->addRow(KeyboardButton::make(text: $keyboardButtonTranslatedText))
        ;
    }

//    public function sendPhone(): ReplyKeyboardMarkup
//    {
//        return ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
//            KeyboardButton::make(
//                trans('support.send_phone_button'),
//                request_contact: true
//            ),
//        );
//    }

//    public function changePhoneConfirm(int $correctPhone): InlineKeyboardMarkup
//    {
//        return InlineKeyboardMarkup::make()
//            ->addRow(
//                InlineKeyboardButton::make(
//                    text: trans('support.yes'),
//                    callback_data: PhoneNumberHandler::changeConfirmCallbackPattern($correctPhone)
//                )
//            )
//            ->addRow(
//                InlineKeyboardButton::make(
//                    text: trans('support.no'),
//                    callback_data: PhoneNumberHandler::changeDeclineCallbackPattern()
//                )
//            )
//        ;
//    }

    public function askStartChatWithOperator(): InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    text: trans('support.yes'),
                    callback_data: ChatWithOperatorHandler::callbackPattern()
                ),
                InlineKeyboardButton::make(
                    text: trans('support.no'),
                    callback_data: MainMenuHandler::callbackPattern()
                ),
            )
        ;
    }

    public function endChatWithOperator(): InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    text: trans('support.end_chat_with_operator'),
                    callback_data: ChatWithOperatorHandler::endChatCallbackPattern()
                )
            )
        ;
    }

}
