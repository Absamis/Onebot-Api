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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->unsignedBigInteger("account_id");
            $table->string("transaction_type");
            $table->double("amount");
            $table->string("currency");
            $table->text("narration")->nullable();
            $table->string("payment_method");
            $table->string("payment_reference")->nullable();
            $table->string("payment_channel")->nullable();
            $table->datetime("transaction_date");
            $table->string("status");
            $table->timestamps();
            $table->foreign("account_id")->references("id")->on("accounts")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
