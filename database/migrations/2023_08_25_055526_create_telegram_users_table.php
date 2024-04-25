<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Telegram\Locale;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id()->nullable()->default(null);
            $table->unsignedBigInteger('chat_id')->nullable()->default(null);
            $table->unsignedBigInteger('group_chat_id')->nullable()->default(null);
            $table->boolean('is_active')->nullable()->default(false); // bool
            $table->boolean('is_chatting')->default(false); // bool
            $table->string('locale')->nullable()->default(null);
            $table->char('phone_number', 12)->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('username')->nullable()->default(null);
            $table->string('group_chat_name')->nullable()->default(null);
            $table->string('group_chat_username')->nullable()->default(null);
            $table->string('chat_type', 20)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_users');
    }
};
