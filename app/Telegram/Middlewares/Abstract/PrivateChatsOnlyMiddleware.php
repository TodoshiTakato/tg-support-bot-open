<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares\Abstract;

use Exception;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use Throwable;

abstract class PrivateChatsOnlyMiddleware
{
    public function __invoke(Nutgram $bot, $next): void
    {
        if ($bot->chat()?->isPrivate()) {
            try {
                $this->handle($bot, $next);
            } catch (Exception $exception) {
                Log::channel('support_bot_errors')
                    ->error('Catched Exception error in PrivateChatsOnlyMiddleware, message:' . $exception->getMessage(),
                        [$exception]
                    )
                ;
            } catch (Throwable $throwable) {
                Log::channel('support_bot_errors')
                    ->error('Catched Throwable error in PrivateChatsOnlyMiddleware, message:' . $throwable->getMessage(),
                        [$throwable]
                    )
                ;
            }
            return;
        }
        $next($bot);
    }

    abstract protected function handle(Nutgram $bot, $next): void;
}
