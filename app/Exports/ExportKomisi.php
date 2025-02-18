<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportKomisi implements FromCollection
{


    public $data;
    public $metadata;
    public function __construct(Collection $data, array $metadata = [])
    {
        $this->data = $data;
        $this->metadata = $metadata;
    }


    public function heading(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Email',
            'No Hp',
            'Nama Mitra',
            'Nama Kelas',
            'Nama Jadwal',
            'Tipe',
            'Jenis',
            'Waktu Pelatihan',
            'Jam',
            'Harga Kelas',
            'Invoice',
            'Voucher',
            'Periode Redeem',
            'Periode Completion',
            // 'Refund Redeem',
            // 'Keterangan',
            'Refund Completion',
            'Keterangan',
            'Persentase Komisi (%)',
            'Nilai Komisi',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $emptyRow = array_map(function ($x) {
            return "";
        }, $this->heading());
        $combined = collect($this->metadata)
            ->concat([
                $emptyRow,
                $emptyRow
            ])
            ->concat([$this->heading()])
            ->concat($this->data);
        return $combined;
    }
}
