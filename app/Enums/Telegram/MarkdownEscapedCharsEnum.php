<?php

namespace App\Enums\Telegram;

use App\Enums\EnumToArrayTrait;

enum MarkdownEscapedCharsEnum: string
{
    use EnumToArrayTrait;

    case BACKSLASH = '\\\\';
    case MINUS = '\-';
    case HASH = '\#';
    case ASTERISK = '\*';
    case PLUS = '\+';
    case BACKQUOTE = '\`';
    case PERIOD = '\.';
    case LEFT_SQUARE_BRACKET = '\[';
    case RIGHT_SQUARE_BRACKET = '\]';
    case LEFT_PARENTHESIS = '\(';
    case RIGHT_PARENTHESIS = '\)';
    case EXCLAMATION_MARK = '\!';
    case AMPERSAND = '\&';
    case LESS_THAN_SIGN = '\<';
    case GREATER_THAN_SIGN = '\>';
    case UNDERSCORE = '\_';
    case LEFT_CURLY_BRACKET = '\{';
    case RIGHT_CURLY_BRACKET = '\}';
    case PIPE = '\|';
}
