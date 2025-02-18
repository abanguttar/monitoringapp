<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('voucher')->nullable();
            $table->string('invoice')->nullable();
            $table->string('kelas_id')->nullable();
            $table->string('redeem_code')->nullable();
            $table->string('redeem_at')->nullable();
            $table->string('finish_at')->nullable();
            $table->string('redeem_period')->nullable();
            $table->integer('redeem_paid')->nullable();
            $table->integer('redeem_refund')->nullable();
            $table->string('redeem_note')->nullable();
            $table->string('finish_period')->nullable();
            $table->integer('finish_paid')->nullable();
            $table->integer('finish_refund')->nullable();
            $table->string('finish_note')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
    }
};
