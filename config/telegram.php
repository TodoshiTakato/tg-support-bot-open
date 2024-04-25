<?php

return [
    'bots' => [
        'support' => [
            'handler' => 'support',
            'token' => env('SUPPORT_BOT_TOKEN'),
            'webhook-uri' => 'support/' . env('SUPPORT_BOT_TOKEN') . '/webhook',
            'webhook_secret_token' => env('SUPPORT_BOT_WEBHOOK_SECRET_TOKEN'),
            'chats' => [
                'error_reports_channel' => (int) env('SUPPORT_BOT_ERROR_REPORTS_CHANNEL_CHAT_ID'), // negative number
                'group_chat' => (int) env('SUPPORT_BOT_GROUP_CHAT_ID'), // negative number
                'self_chat_id' => (int) env('SUPPORT_BOT_CHAT_ID'),
            ],
            'links' => [
                'tg_getFile' => 'https://api.telegram.org/bot' . env('SUPPORT_BOT_TOKEN') . '/getFile',
                'tg_file_download_http' => 'http://api.telegram.org/file/bot' . env('SUPPORT_BOT_TOKEN') . '/',
                'tg_file_download_https' => 'https://api.telegram.org/file/bot' . env('SUPPORT_BOT_TOKEN') . '/',
            ],
        ],
    ]
];
