<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportPayment implements FromCollection
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
            'Nama Kelas',
            'Nama Jadwal',
            'Tipe',
            'Jenis',
            'Waktu Pelatihan',
            'Jam',
            'Harga Kelas',
            'Nama Digital Platform',
            'Invoice',
            'Voucher',
            'Nama Mitra',
            'Redeem Code',
            'Tanggal Redeem',
            '100% Pelatihan',
            'Bayar Redeem',
            'Periode Redeem',
            'Refund Redeem',
            'Keterangan',
            'Bayar Completion',
            'Periode Completion',
            'Refund Completion',
            'Keterangan',
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
