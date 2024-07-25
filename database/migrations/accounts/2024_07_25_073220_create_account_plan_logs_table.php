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
        Schema::create('account_plan_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("account_id");
            $table->unsignedBigInteger("plan_id");
            $table->integer("plan_mode");
            $table->date("date_joined");
            $table->string("log_type");
            $table->string("subscription_mode");
            $table->integer("duration_in_days")->nullable();
            $table->string("reference")->nullable();
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("account_id")->references("id")->on("accounts")->cascadeOnDelete();
            $table->foreign("plan_id")->references("id")->on("subscription_plans")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_plan_logs');
    }
};
