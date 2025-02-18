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
        Schema::create('transaction_kelas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("kelas_id");
            $table->bigInteger("day");
            $table->string("scheme")->nullable();
            $table->text("trainer_ids")->nullable();
            $table->text("trainer_names")->nullable();
            $table->bigInteger("total")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_kelas');
    }
};
