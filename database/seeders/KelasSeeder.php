<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Kelas::factory(50)->create();
        Kelas::truncate();
        DB::table('transaction_kelas')->truncate();
        DB::table('pivot_kelas')->truncate();
    }
}
