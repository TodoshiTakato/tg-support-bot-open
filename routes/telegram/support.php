<?php

declare(strict_types=1);

/** @var Nutgram $bot */

use App\Telegram\Actions\Support\FallbackAction;
use App\Telegram\Actions\Support\OnApiErrorAction;
use App\Telegram\Actions\Support\OnExceptionAction;
use App\Telegram\Commands\Clients\StartCommand;
use App\Telegram\Handlers\Support\Messages\LeftChatMemberHandler;
use App\Telegram\Handlers\Support\Messages\NewChatMembersHandler;
use App\Telegram\Handlers\Support\Messages\MessageHandler;
use App\Telegram\Handlers\Support\MyChatMemberHandler;
use App\Telegram\Handlers\Support\PrivateChats\ChatWithOperatorHandler;
use App\Telegram\Handlers\Support\PrivateChats\LanguageHandler;
use App\Telegram\Handlers\Support\PrivateChats\MainMenuHandler;
use App\Telegram\Middlewares\Support\AttachUser;
use App\Telegram\Middlewares\Support\SetLocale;
use App\Telegram\Middlewares\Support\PrivateChats\PrivateChatMiddleware;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\MessageType;

// Middlewares

// Enums

// Commands

// Handlers

// Actions


// Global Middlewares (don't change order!)
$bot->middleware(AttachUser::class);
$bot->middleware(SetLocale::class);

$bot->onMyChatMember(MyChatMemberHandler::class);
$bot->onMessageType(MessageType::NEW_CHAT_MEMBERS, NewChatMembersHandler::class);
$bot->onMessageType(MessageType::LEFT_CHAT_MEMBER, LeftChatMemberHandler::class);

$bot->registerCommand(StartCommand::class);

// ===================================== Language Handlers =============================================================
    // select language
    $bot->onText(LanguageHandler::pattern(), LanguageHandler::class);
    // set/change language
    $bot->onText(LanguageHandler::langPattern(), [LanguageHandler::class, 'langHandler']);
// =====================================================================================================================


// ===================================== Main Menu =====================================================================
    $bot->onText(MainMenuHandler::pattern(), MainMenuHandler::class);
    $bot->onText(MainMenuHandler::backReplyPattern(), [MainMenuHandler::class, 'backReplyHandler']);
    $bot->onCallbackQueryData(MainMenuHandler::callbackPattern(), [MainMenuHandler::class, 'backToMainMenuCallbackHandler']);
// =====================================================================================================================

// ===================================== Chat With Operator ============================================================
    $bot->onText(ChatWithOperatorHandler::pattern(), ChatWithOperatorHandler::class);
    $bot->onCallbackQueryData(ChatWithOperatorHandler::callbackPattern(), [ChatWithOperatorHandler::class, 'callbackHandler']);
// ===================================== End Chat With Operator ========================================================
    $bot->onCallbackQueryData(ChatWithOperatorHandler::endChatCallbackPattern(), [ChatWithOperatorHandler::class, 'endChatCallbackHandler']);
// =====================================================================================================================

// ===================================== Resend Messages to Operators ==================================================
    $bot->onMessage(MessageHandler::class);
    $bot->onMessageType(MessageType::TEXT, MessageHandler::class);
// =====================================================================================================================


// =================================  All UnHandled Messages Handler  ==================================================
    $bot->fallback(FallbackAction::class);
// =====================================================================================================================

// =================================  All Exceptions Handler  ==========================================================
    $bot->onException(OnExceptionAction::class);
// =====================================================================================================================

// =========================  All 'On Api Error Responses From Telegram' Handler  ======================================
    $bot->onApiError(OnApiErrorAction::class);
// =====================================================================================================================
