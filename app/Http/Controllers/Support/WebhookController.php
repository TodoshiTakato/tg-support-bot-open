<?php

declare(strict_types=1);

namespace App\Http\Controllers\Support;


use App\Http\Controllers\Controller;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use SergiX44\Nutgram\Nutgram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WebhookController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Nutgram $bot
     * @return void
     */
    public function __invoke(Request $request, Nutgram $bot)
    {
        Log::channel('support_bot')->debug('1) Support/WebhookController: Request: ' . PHP_EOL
            . json_encode($request->all(), JSON_PRETTY_PRINT |
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE |
                JSON_INVALID_UTF8_SUBSTITUTE |
                JSON_PARTIAL_OUTPUT_ON_ERROR
            ) . PHP_EOL
        );
        try {
            $bot->run();
//            Log::channel('support_bot')->debug('Support/WebhookController: Update type:' . prettyPrintObjectProperties($bot->update()) . PHP_EOL);
        } catch (NotFoundExceptionInterface $e) {
            Log::channel('support_bot_errors')->critical(
                "Error! Couldn't start bot! NotFoundExceptionInterface." . PHP_EOL
                . 'Exception message: ' . $e->getMessage() . PHP_EOL
                . 'Exception stack trace: ' . $e->getTraceAsString() . PHP_EOL
            );
            return;
        } catch (ContainerExceptionInterface $e) {
            Log::channel('support_bot_errors')->critical(
                "Error! Couldn't start bot! ContainerExceptionInterface." . PHP_EOL
                . 'Exception message: ' . $e->getMessage() . PHP_EOL
                . 'Exception stack trace: ' . $e->getTraceAsString() . PHP_EOL
            );
            return;
        } catch (Exception $e) {
            Log::channel('support_bot_errors')->critical(
                "Error! Couldn't start bot! Unhandled Exception." . PHP_EOL
                . 'Exception message: ' . $e->getMessage() . PHP_EOL
                . 'Exception stack trace: ' . $e->getTraceAsString() . PHP_EOL
            );
            return;
        }
    }
}
