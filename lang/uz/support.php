<?php

use App\Enums\Telegram\Emoji;

return [
    "greetings_language" => "Assalomu alaykum :name!" . PHP_EOL
        . Emoji::UZBEK_FLAG->value   . " Tilni tanlang:" . PHP_EOL . PHP_EOL
        . "Здравствуйте :name!" . PHP_EOL
        . Emoji::RUSSIAN_FLAG->value . " Выберите язык:" . PHP_EOL . PHP_EOL
    ,

    "language"         => "O'zbek tili " . Emoji::UZBEK_FLAG->value,
    "language_changed" => "Til <b>O'zbek</b> " . Emoji::UZBEK_FLAG->value . " tiliga o'zgartirildi",

    "current_language" => "Joriy til: " . "O'zbek tili " . Emoji::UZBEK_FLAG->value,
    "select_language"  => "Tilni tanlang:",

    "yes" => "Ha",
    "no"  => "Yoq",

    // menu
    "online_chat_with_operator" => "Operator bilan onlayn suhbat " . Emoji::TELEPHONE_RECEIVER->value,
    "change_language" => "Tilni o'zgartirish " . Emoji::UZBEK_FLAG->value,

    "back"  => "Orqaga " . Emoji::BACK->value,
    "main_menu" => "Asosiy menyu",

    "rate_operator" => "Operatorni baholash",

    "error_fallback" => "Iltimos, boshqa biror narsani kiriting yoki menyudagi tugmalarning birini bosing",

    "validation" => [
        "phone_number" => [
            "required"  => "Bizga telefon raqamingiz kerak!",
            "incorrect" => "Noto‘g‘ri telefon raqami kiritildi!",
            "user_not_found" => "Kiritilgan telefon raqamiga ega foydalanuvchi topilmadi!",
        ],
    ],

    "please_ask_your_question" => "Iltimos, muammoingizni tasvirlab bering yoki sizni qiziqtirgan savolni bering." . PHP_EOL
        . "Agar fikringizni o'zgartirsangiz, quyidagi tugmani bosing."
    ,
    "is_chatting_with_operator" => "Siz allaqachon operator bilan aloqadasiz, iltimos kuting!",
    "want_to_end_chat_with_operator" => "Operator bilan suhbatni tugatmoqchimisiz?",
    "end_chat_with_operator"   => "Operator bilan suhbatni tugatish " . Emoji::BLACK_LARGE_SQUARE->value,
    "chat_with_operator_ended" => "Siz operator bilan suhbatni tugatdingiz.",
    "chat_with_operator_already_ended" => "Siz operator bilan suhbatni allaqachon tugatgansiz!",
    "can_chat_only_in_private" => "Siz operator bilan faqat bot bilan shaxsiy suhbat chat orqali bog'lanishingiz mumkin!",
    "can_end_chat_only_in_private" => "Operator bilan suhbatni faqat bot bilan shaxsiy suhbat chatda tugatishingiz mumkin!",
    "we_got_your_message" => "Biz sizning xabaringizni oldik! Iltimos, operatorning javobini kuting!",
    "want_to_start_chat_with_operator" => "Operator bilan suhbatni boshlamoqchimisiz?",
    "couldn't_find_user_id" => "Xatolik! Mijozning foydalanuvchi identifikatorini (UserID) aniqlab bo'lmadi.",
    "reply_was_sent" => "Javob mijozga muvaffaqiyatli yuborildi " . Emoji::CHECK_MARK_BUTTON->value,
    "reply_wasn't_sent" => "Javob mijozga yetkazilmadi.",

    "file_type" => [
        "audio" => "audio fayl",
        "document" => "dokument",
        "photo" => "rasm",
        "video_note" => "dumaloq video xabar",
        "video" => "video fayl",
        "voice" => "ovozli xabar",
    ],
    "errors" => [
        "global" => "Sistemamizda muammolar kuzatilmoqda, iltimos birozdan keyin qayta urinib ko'ring!",
        "general" => "Xatolik yuz berdi, so‘rovingizni o‘zgartirib ko‘ring!",
        "unauthorized_access"  => "Ruxsatsiz kirish. Ruxsat berilmadi.",

        "file_upload_and_save" => [
            "resend_file_pls" => "Tizim: Xatolik yuz berdi, :file_type ni qayta yuboring!",
//            "resend_file_pls" => "Tizim: xatolik yuz berdi! Rasm saqlanmadi! Iltimos, rasmni qayta yuborishga harakat qiling.",
        ],
    ],
];
