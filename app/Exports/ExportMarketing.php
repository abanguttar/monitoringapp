<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportMarketing implements FromCollection, WithHeadings
{

    public $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Email',
            'No Hp',
            'Nama Kelas',
            'Nama Jadwal',
            'Tipe',
            'Jenis',
            'Waktu Pelatihan',
            'Jam',
            'Harga Kelas',
            'Nama DIgital Platform',
            'Invoice',
            'Voucher',
            'Nama Mitra',
            'Redeem Code',
            'Tanggal Redeem',
            '100% Pelatihan',
            'Periode Redeem',
            'Periode Completion',
            'Refund Redeem',
            'Keterangan',
            'Refund Completion',
            'Keterangan',
        ];
    }


    public function collection()
    {
        return $this->data;
    }
}
