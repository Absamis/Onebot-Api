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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->text("slug");
            $table->string("subscription_mode");
            $table->double("price")->nullable();
            $table->double("discount_price")->nullable();
            $table->boolean("allow_trial")->default(false);
            $table->unsignedBigInteger("trial_validity")->nullable();
            $table->unsignedBigInteger("grace_duration_in_days")->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
