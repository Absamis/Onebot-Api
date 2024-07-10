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
        Schema::create('account_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userid")->nullable();
            $table->unsignedBigInteger("account_id");
            $table->string("email");
            $table->string("name")->nullable();
            $table->unsignedBigInteger("role_id");
            $table->string("token")->unique()->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("account_id")->references("id")->on("accounts")->cascadeOnDelete();
            $table->foreign("role_id")->references("id")->on("roles")->cascadeOnDelete();
            $table->foreign("userid")->references("id")->on("users")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_invitations');
    }
};
