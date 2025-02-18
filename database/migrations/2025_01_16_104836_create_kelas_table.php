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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jadwal_name');
            $table->string('jam')->nullable();
            $table->string('date')->nullable();
            $table->bigInteger('price');
            $table->integer('is_prakerja');
            $table->string('metode');
            $table->string('day')->default(0);
            $table->bigInteger('trainer_id')->nullable();
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
        Schema::dropIfExists('kelas');
    }
};
