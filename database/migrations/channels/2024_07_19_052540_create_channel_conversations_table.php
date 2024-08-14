<?php

use App\Enums\AppEnums;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('channel_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("contact_id");
            $table->unsignedBigInteger("admin_id")->nullable();
            $table->longText("message")->nullable();
            $table->longText("attachments")->nullable();
            $table->text("sticker")->nullable();
            $table->text("reaction")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("contact_id")->references("id")->on("contacts")->cascadeOnDelete();
            $table->foreign("admin_id")->references("id")->on("users")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_conversations');
    }
};
