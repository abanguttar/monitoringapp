<?php

namespace Database\Seeders;

use App\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->truncate();
        // DB::table('user_permissions')->truncate();

        $permissions = [
            new Permission("master", "master_data_peserta"),
            new Permission("master", "list_peserta"),
            new Permission("master", "list_peserta", ['ubah redeem/complete', 'import peserta baru', 'import redeemtion', 'import completion']),
            new Permission("master", "list_pembayaran", ['view', 'import pembayaran', 'import refund', 'ubah payment', 'ubah refund']),
            new Permission("master", "list_komisi_mitra", ['view', 'export']),
            new Permission("master", "list_mitra"),
            new Permission("master", "list_mitra", ['delete']),
            new Permission("master", "list_digital_platform"),
            new Permission("master", "list_digital_platform", ['delete']),
            new Permission("master", "list_data_kelas"),
            new Permission("master", "list_data_kelas", ['delete']),
            new Permission("master", "list_user"),
            new Permission("master", "list_user", ['delete', 'access']),
            new Permission("master", "list_peserta_marketing", ['view']),
            new Permission("master", "grafik_transaksi", ['view']),
        ];


        foreach ($permissions as $key => $permission) {
            $group = $permission->group;
            $name = $permission->name;
            foreach ($permission->access as $access) {
                $data = [
                    'group' => $group,
                    'name' => $name,
                    'access' => $access,
                ];
                DB::table('permissions')->insert($data);
            }
        }
    }
}
