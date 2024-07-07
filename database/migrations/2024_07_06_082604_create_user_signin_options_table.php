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
        Schema::create('user_signin_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userid");
            $table->string("type");
            $table->string("signin_app_id")->unique();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->text("token")->nullable();
            $table->text("refresh_token")->nullable();
            $table->string("photo")->nullable();
            $table->string("token_expires_in")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("userid")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_signin_options');
    }
};
