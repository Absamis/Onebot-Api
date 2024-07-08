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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userid");
            $table->string("name");
            $table->string("category")->nullable();
            $table->string("company_url")->nullable();
            $table->string("type")->nullable();
            $table->text("description")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("userid")->references("id")->on("users")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
