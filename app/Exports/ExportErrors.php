<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportErrors implements FromCollection, WithHeadings
{

    public $data;
    public $tipe;
    public function __construct(Collection $data, $tipe)
    {
        $this->data = $data;
        $this->tipe = $tipe;
    }


    public function headings(): array
    {
        switch ($this->tipe) {
            case 'pembelian':
                $header = [
                    'No',
                    'Nama Peserta',
                    'Email',
                    'No Hp',
                    'Voucher',
                    'Invoice',
                    'Keterangan',
                ];
                break;
            case 'redeemtion':
                $header = [
                    'No',
                    'Voucher',
                    'Kode Redeem',
                    'Waktu Redeem',
                    'Keterangan',
                ];
                break;
            case 'completion':
                $header = [
                    'No',
                    'Voucher',
                    '100% Pelatihan',
                    'Keterangan',
                ];
                break;
            case 'payment':
                $header = [
                    'No',
                    'Invoice',
                    'Periode',
                    'Keterangan',
                ];
                break;
            case 'refund':
                $header = [
                    'No',
                    'Invoice',
                    'Keterangan',
                    'Keterangan gagal',
                ];
                break;


        }
        return $header;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }
}
