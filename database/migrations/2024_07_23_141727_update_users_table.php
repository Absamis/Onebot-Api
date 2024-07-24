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
        //
        Schema::table("users", function (Blueprint $table) {
            $table->unsignedBigInteger("plan_id")->nullable();
            $table->string("plan_mode")->nullable()->comment(": 0- trial, 1- live");
            $table->date("plan_date_joined")->nullable();
            $table->unsignedBigInteger("plan_duration_in_days")->nullable();
            $table->date("plan_expiring_date")->nullable();
            $table->foreign("plan_id")->references("id")->on("subscription_plans")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropColumns("users", ["plan_id", "plan_mode", "plan_date_joined", "plan_duration_in_days", "plan_expiring_date"]);
    }
};
