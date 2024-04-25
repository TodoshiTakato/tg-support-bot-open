<?php

use App\Enums\Telegram\Emoji;

return [
    "greetings_language" => "Assalomu alaykum :name!" . PHP_EOL
        . Emoji::UZBEK_FLAG->value   . " Tilni tanlang:" . PHP_EOL . PHP_EOL
        . "Здравствуйте :name!" . PHP_EOL
        . Emoji::RUSSIAN_FLAG->value . " Выберите язык:" . PHP_EOL . PHP_EOL
    ,

    "language"         => "Русский язык " . Emoji::RUSSIAN_FLAG->value,
    "language_changed" => "Язык был изменен на <b>Русский</b> " . Emoji::RUSSIAN_FLAG->value,

    "current_language" => "Текущий язык: " . "Русский " . Emoji::RUSSIAN_FLAG->value,
    "select_language"  => "Выберите язык:",

    "yes" => "Да",
    "no"  => "Нет",

    // menu
    "online_chat_with_operator" => "Онлайн-чат с оператором " . Emoji::TELEPHONE_RECEIVER->value,
    "change_language" => "Изменить язык " . Emoji::RUSSIAN_FLAG->value,

    "back"  => "Назад " . Emoji::BACK->value,
    "main_menu" => "Главное меню",

    "rate_operator" => "Оценка оператора",

    "error_fallback" => "Пожалуйста, попробуйте ввести что-нибудь другое или нажать на одну из кнопок в меню",

    "validation" => [
        "phone_number" => [
            "required"  => "Нам нужен ваш номер телефона!",
            "incorrect" => "Введён неправильный номер телефона!",
            "user_not_found" => "Пользователя с таким номером телефона не существует!",
        ],
    ],

    "please_ask_your_question" => "Пожалуйста, опишите вашу проблему или задайте интересующий вас вопрос." . PHP_EOL
        . "Нажмите на кнопку ниже, если передумали."
    ,
    "is_chatting_with_operator" => "Вы уже общаетесь с оператором, пожалуйста подождите!",
    "want_to_end_chat_with_operator" => "Вы хотите завершить диалог с оператором?",
    "end_chat_with_operator"   => "Завершить диалог с оператором " . Emoji::BLACK_LARGE_SQUARE->value,
    "chat_with_operator_ended" => "Вы завершили диалог с оператором.",
    "chat_with_operator_already_ended" => "Вы уже завершили диалог с оператором!",
    "can_chat_only_in_private" => "Вы можете пообщаться с оператором только в чате с ботом!",
    "can_end_chat_only_in_private" => "Вы можете завершить диалог с оператором только в чате с ботом!",
    "we_got_your_message" => "Мы получили ваше сообщение! Пожалуйста дождитесь ответа оператора!",
    "want_to_start_chat_with_operator" => "Вы хотите начать диалог с оператором?",
    "couldn't_find_user_id" => "Ошибка! Не удалось определить UserID клиента.",
    "reply_was_sent" => "Ответ успешно отправлен клиенту " . Emoji::CHECK_MARK_BUTTON->value,
    "reply_wasn't_sent" => "Ответ не доставлен клиенту.",

    "file_type" => [
        "audio" => "аудиофайл",
        "document" => "документ",
        "photo" => "изображение",
        "video_note" => "круглое видео сообщение",
        "video" => "видеофайл",
        "voice" => "голосовое сообщение",
    ],
    "errors" => [
        "global" => "В нашей системе наблюдаются проблемы, пожалуйста попробуйте повторить запрос чуть позже!",
        "general" => "Похоже, что-то пошло не так, пожалуйста попробуйте поменять запрос!",
        "unauthorized_access"  => "Несанкционированный доступ. Доступ запрещен.",


        "file_upload_and_save" => [
            "resend_file_pls" => "Система: Произошла ошибка, пожалуйста отправьте :file_type ещё раз!",
//            "resend_file_pls" => "Система: Произошла ошибка! Не удалось сохранить изображение!"
//                . "Пожалуйста, попробуйте отправить изображение ещё раз.",
        ],
    ],
];
