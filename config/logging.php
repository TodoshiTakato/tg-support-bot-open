<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

//use Monolog\Handler\FilterHandler;
//use Monolog\Handler\TelegramBotHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => LOG_USER,
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'nutgram' => [ // Логи nutgram
            'driver' => 'daily',
            'path' => storage_path('logs/nutgram/nutgram.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        'support_bot' => [ // Логи бота для support
            'driver' => 'daily',
            'path' => storage_path('logs/support_bot/support_bot.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        'support_bot_errors' => [ // Логи ошибок бота для support
            'driver' => 'daily',
            'path' => storage_path('logs/support_bot_errors/support_bot_errors.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        'clients_bot' => [ // Логи бота для клиентов
            'driver' => 'daily',
            'path' => storage_path('logs/clients_bot/clients_bot.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        'clients_bot_errors' => [ // Логи бота для клиентов
            'driver' => 'daily',
            'path' => storage_path('logs/clients_bot/clients_bot_errors.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        'sms' => [ // Логи SMS
            'driver' => 'daily',
            'path' => storage_path('logs/sms/sms.log'),
            'level' => 'debug',
            'days' => 365,
        ],
        'sms_errors' => [ // Логи SMS
            'driver' => 'daily',
            'path' => storage_path('logs/sms/sms_errors.log'),
            'level' => 'debug',
            'days' => 365,
        ],

        // Нативный логгер от пакета Nutgram оказался нам не подходит, так мы оперируем несколькими ботами
//        'telegram' => [
//            'driver' => 'custom',
//            'via' => \Nutgram\Laravel\Log\NutgramLogger::class,
//            'level' => 'debug',
//            'chat_id' => env('NUTGRAM_LOG_CHAT_ID'), // any chat_id where bot can write messages
//        ],

//        // Поэтому используем нативный Laravel'овский логгер Monolog, у которого появилась поддержка телеграм ботов
//        "clients_bot_errors_logger_bot" => [ // Логи ошибок бота для клиентов, отправляемые через другой бот в приватный канал с ошибками
//            'driver'  => 'monolog',
//            'handler' => FilterHandler::class,
//            'level' => 'debug',
//            'with' => [
//                'handler' => new TelegramBotHandler(
//                    $apiKey = env('UZUM_NASIYA_REPORTS_BOT_TOKEN'),
//                    $channel = env('CLIENTS_BOT_ERROR_REPORTS_CHANNEL_CHAT_ID'),
//                )
//            ]
//        ],

        'tokens_v1' => [ // Логи (генератора) токенов
            'driver' => 'daily',
            'path' => storage_path('logs/api/v1/tokens.log'),
            'level' => 'info',
            'days' => 365,
        ],
    ],

];
