<?php

declare(strict_types=1);

namespace App\Telegram\Helpers;

use SergiX44\Nutgram\Nutgram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class TgFileHelper
{
    /**
     * @param string $fileType
     * @param string $tgFileId
     * @param Nutgram $bot
     * @return string|null
     */
    public static function getFileInfo(string $fileType, string $tgFileId, Nutgram $bot): string|null
    {
        // http запрос к тг методу getFile()
        $tgResponse = Http::get(config('telegram.bots.support.links.tg_getFile'), ['file_id' => $tgFileId]);
        $tgResponseArray = $tgResponse->json();

        if ( ($tgResponse->status() !== Response::HTTP_OK)
            || !array_key_exists('ok', $tgResponseArray)
            || ($tgResponseArray['ok'] !== true)
            || !array_key_exists('result', $tgResponseArray)
            || !array_key_exists('file_path', $tgResponseArray['result'])
            || empty($tgResponseArray['result']['file_path'])
        ) {
            // handle error and send message
            Log::channel('support_bot_errors')->critical(PHP_EOL .
                "Error! Wasn't able to get file_info from Telegram's getFile() API method: ", [
                'chat_id' => $bot->chatId(),
                'file_id' => $tgFileId
            ])
            ;
            $bot->sendMessage(trans('support.errors.file_upload_and_save.resend_file_pls', ['file_type' => $fileType])); // to client
            return null;
        }

        return (string) $tgResponseArray['result']['file_path'];
    }
}
