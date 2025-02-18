<?php

namespace Database\Seeders;

use App\Models\Peserta;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peserta::truncate();
        Transaction::truncate();
    }
}
