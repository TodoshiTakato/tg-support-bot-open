<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class TelegramServiceProvider extends ServiceProvider
{
    protected const HANDLERS_PATH = 'routes/telegram';
    protected string $handlers;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Nutgram::class, function (Application $app) {
            $request = $app->get(Request::class);
            $defaultBotToken = config('telegram.bots.support.token');
            $defaultHandler = config('telegram.bots.support.handler');

            if ($request->is('*/webhook')) {
                foreach (config('telegram.bots') as $bot) {
                    if ($request->path() === "api/{$bot['webhook-uri']}") {
                        $botToken = $bot['token'];
                        $botHandler = $bot['handler'];
                    }
                }
            }

            $this->setHandlers($botHandler ?? $defaultHandler);
            ;
            $config = new Configuration(
                logger: Log::channel('nutgram'),
                pollingAllowedUpdates: [
                    "message" => "message",
                    "edited_message" => "edited_message",
//                    "channel_post" => "channel_post",
//                    "edited_channel_post" => "edited_channel_post",
//                    "message_reaction" => "message_reaction",
//                    "message_reaction_count" => "message_reaction_count",
//                    "inline_query" => "inline_query",
//                    "chosen_inline_result" => "chosen_inline_result",
                    "callback_query" => "callback_query",
//                    "shipping_query" => "shipping_query",
//                    "pre_checkout_query" => "pre_checkout_query",
//                    "poll" => "poll",
//                    "poll_answer" => "poll_answer",
                    "my_chat_member" => "my_chat_member",
//                    "chat_member" => "chat_member",
//                    "chat_join_request" => "chat_join_request",
//                    "chat_boost" => "chat_boost",
//                    "removed_chat_boost" => "removed_chat_boost"
                ]
            );

            $bot = new Nutgram($botToken ?? $defaultBotToken, $config);

//            $webhook = new Webhook(secretToken: config('telegram.bots.support.webhook_secret_token'));
//            $webhook->setSafeMode(true);
//            $bot->setRunningMode($webhook);
            $bot->setRunningMode(new Webhook(fn() => $request?->ip()));

            return $bot;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->get(Request::class)->is('*/webhook')) {
            $bot = $this->app->get(Nutgram::class);
            require base_path(self::HANDLERS_PATH . '/' . $this->getHandlers() . '.php');
        }
    }

    protected function setHandlers(string $handlers): void
    {
        $this->handlers = $handlers;
    }

    protected function getHandlers(): string
    {
        return $this->handlers;
    }
}
