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
        Schema::create('signin_options', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->text("description")->nullable();
            $table->string("image")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signin_options');
    }
};
