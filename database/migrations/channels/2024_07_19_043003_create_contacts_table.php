<?php

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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("account_id");
            $table->unsignedBigInteger("channel_id");
            $table->string("name");
            $table->string("contact_app_id");
            $table->string("token")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("photo")->nullable();
            $table->string("gender")->nullable();
            $table->string("locale")->nullable();
            $table->string("contact_app_type");
            $table->unsignedBigInteger("conversation_assigned_to")->nullable();
            $table->string("conversation_status");
            $table->string("status");
            $table->timestamps();
            $table->foreign("account_id")->references("id")->on("accounts")->cascadeOnDelete();
            $table->foreign("channel_id")->references("id")->on("channels")->cascadeOnDelete();
            $table->foreign("conversation_assigned_to")->references("id")->on("users")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
