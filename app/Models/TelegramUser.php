<?php

declare(strict_types=1);

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\Telegram\Locale;
use SergiX44\Nutgram\Telegram\Properties\ChatType;


/**
 * @mixin  Builder
 */
class TelegramUser extends Model
{

    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'telegram_users';

    public const ACTIVE = true;
    public const BLOCKED = false;


    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'chat_id',
        'group_chat_id',
        'is_active',        // bool
        'is_chatting',      // bool
        'locale',  // Locale::class
        'phone_number',
        'name',
        'username',
        'group_chat_name',
        'group_chat_username',
        'chat_type',
    ];


    /**
     * @var string[]
     */
    protected $casts = [
        'is_active'        => 'bool',
        'is_chatting'      => 'bool',
        'locale'           => Locale::class,
        'chat_type'        => ChatType::class,
    ];

}
