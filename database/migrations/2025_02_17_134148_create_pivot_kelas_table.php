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
        Schema::create('pivot_kelas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("transaction_kelas_id");
            $table->bigInteger("kelas_id");
            $table->bigInteger("day_number");
            $table->bigInteger("trainer_id_1")->nullable();
            $table->bigInteger("trainer_id_2")->nullable();
            $table->string("trainer_name_1")->nullable();
            $table->string("trainer_name_2")->nullable();
            $table->bigInteger("commission_1")->nullable();
            $table->bigInteger("commission_2")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_kelas');
    }
};
