<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

interface WithPattern
{
    public static function pattern(): string;
}
