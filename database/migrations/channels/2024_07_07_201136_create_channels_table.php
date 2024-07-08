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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("account_id");
            $table->string("name");
            $table->string("type");
            $table->string("photo")->nullable();
            $table->string("description")->nullable();
            $table->string("channel_app_id")->unique();
            $table->text("token")->nullable();
            $table->text("refresh_token")->nullable();
            $table->string("token_expires_in")->nullable();
            $table->text("permissions")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("account_id")->references("id")->on("accounts")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
