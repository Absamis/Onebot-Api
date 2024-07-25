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
        Schema::create('subscription_plan_promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("plan_id");
            $table->unsignedBigInteger("subs_in_days");
            $table->string("discount_type")->comment("fixed,percentage");
            $table->double("discount_value");
            $table->integer("status")->default(AppEnums::active);
            $table->timestamps();
            $table->foreign("plan_id")->references("id")->on("subscription_plans")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plan_promos');
    }
};
