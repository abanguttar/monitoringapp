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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('peserta_id');
            $table->bigInteger('kelas_id');
            $table->bigInteger('mitra_id');
            $table->bigInteger('digital_platform_id');
            $table->string('voucher');
            $table->string('invoice');
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
            $table->integer('commission_percentage')->nullable();
            $table->integer('commission_value')->nullable();
            $table->integer('commission_trainer_percentage')->nullable();
            $table->integer('commission_trainer_value')->nullable();
            $table->string('user_create');
            $table->string('user_update');
            $table->timestamps();
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
