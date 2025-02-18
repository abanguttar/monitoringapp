<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mitra;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrafikController extends Controller
{
    protected $transaction;
    protected $db;
    protected $navbar;

    protected $kelas;
    protected $mitra;

    protected $path = 'grafik';

    public function __construct(Transaction $transaction, Kelas $kelas, Mitra $mitra, DB $db)
    {
        parent::__construct();
        $this->db = $db;
        $this->kelas = $kelas;
        $this->mitra = $mitra;
        $this->transaction = $transaction;
        $this->navbar = Str::slug(strtolower('Grafik Transaksi'));
    }


    private function transaction()
    {
        $query = $this->db::table('transactions as t')
            ->leftJoin('kelas as k', 't.kelas_id', '=', 'k.id');

        return $query;
    }

    private function periodes($year = null, $tipe = 'redeem_period')
    {
        $query = $this->db::table('transactions')
            ->select($tipe)->groupBy($tipe)
            ->whereNotNull($tipe);
        if ($year) {
            $query = $query->where(DB::raw("YEAR(created_at)"), '=', $year);
        }
        $periodes = $query->get();

        return array_map(function ($x) use ($tipe) {
            return (object) [
                'id' => $x->$tipe,
                'name' => $x->$tipe,
            ];
        }, json_decode($periodes));
    }


    public function index(Request $request)
    {
        $title = 'Grafik Transaksi';
        $navbar = $this->navbar;
        $kelas = $this->kelas::get();
        $mitras = $this->mitra::get();
        $listClassName = array_map(function ($x) {
            return (object) [
                'id' => $x,
                'name' => $x,
            ];
        }, array_unique(array_column(json_decode($kelas, true), 'name')));

        $periodes = $this->periodes(2025, 'finish_period');
        $redeem_periodes = $this->periodes(2025);
        return view("$this->path/index", compact('title', 'navbar', 'kelas', 'mitras', 'listClassName', 'periodes', 'redeem_periodes'));
    }


    public function pembelianPenyelesaian(Request $request)
    {
        $nama_kelas = $request->name;
        $kelas_id = $request->kelas_id;
        $year = $request->year;
        $pelatihans = $this->transaction();
        if (!empty($nama_kelas)) {
            $pelatihans->where('k.name', $nama_kelas);
        } else if (!empty($kelas_id)) {
            $pelatihans->where('t.kelas_id', $kelas_id);
        }
        $pelatihans->where(DB::raw("YEAR(t.created_at)"), "=", $year);
        $pembelians = clone $pelatihans;
        $redemptions = clone $pelatihans;
        $completions = clone $pelatihans;
        $pembelian = $pembelians->count();
        $redemption = $redemptions->whereNotNull('t.redeem_code')->count();
        $completion = $completions->whereNotNull('t.finish_at')->count();

        $data = (object) [
            'title' => [
                'Pembelian',
                'Redemption',
                'Completion'
            ],
            'datasets' => [
                (object) [
                    'label' => 'Semua data kelas',
                    'data' => [$pembelian, $redemption, $completion],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    'hoverOffset' => 4

                ],

            ]
        ];

        return response()->json($data);
    }


    public function penjualanKelas(Request $request)
    {
        $year = $request->year ?? 2025;
        $nama_kelas = $request->name;
        $kelas_id = $request->kelas_id;
        $redeem_periodes = array_map(function ($x) {
            return $x->name;
        }, $this->periodes($year));
        $finish_periodes = array_map(function ($x) {
            return $x->name;
        }, $this->periodes($year, 'finish_period'));

        $periodes = array_unique(array_merge($redeem_periodes, $finish_periodes));

        $arrayPembelian = [];
        $arrayRedeemtion = [];
        $arrayCompletion = [];

        foreach ($periodes as $periode) {
            $arrayPembelian[$periode] = [];
            $arrayRedeemtion[$periode] = [];
            $arrayCompletion[$periode] = [];
        }


        $pelatihans = $this->transaction();
        if (!empty($nama_kelas)) {
            $pelatihans->where('k.name', '=', $nama_kelas);
        } else if (!empty($kelas_id)) {
            $pelatihans->where('t.kelas_id', '=', $kelas_id);
        }
        $pelatihans->where(DB::raw("YEAR(t.created_at)"), "=", $year);
        $pembelians = clone $pelatihans;
        $redemptions = clone $pelatihans;
        $completions = clone $pelatihans;
        $pembelian = $pembelians->whereNotNull('t.redeem_period')->get();
        $redemption = $redemptions->whereNotNull('t.redeem_code')->whereNotNull('t.redeem_period')->get();
        $completion = $completions->whereNotNull('t.finish_at')->whereNotNull('t.redeem_period')->get();
        $result = [];
        foreach ($pembelian as $p) {
            $rp = $p->redeem_period;
            $fp = $p->finish_period;
            $arrayPembelian[$rp][] = $p;
        }
        foreach ($redemption as $r) {
            $rp = $r->redeem_period;
            $arrayRedeemtion[$rp][] = $r;
        }
        foreach ($completion as $c) {
            $rp = $c->redeem_period;
            $fp = $c->finish_period;
            $arrayCompletion[$fp][] = $c;
        }

        // dd($pembelian, $redemption, $completion);
        // dd($array_p, $array_r, $array_c);
        foreach ($periodes as $periode) {
            $obj = (object) [
                'x' => $periode,
                'pembelian' => count($arrayPembelian[$periode]),
                'redeemtion' => count($arrayRedeemtion[$periode]),
                'completion' => count($arrayCompletion[$periode])
            ];
            array_push($result, $obj);
        }

        $data = (object) [
            'title' => $periodes,
            'datasets' => [
                (object) [
                    'label' => 'Pembelian',
                    'data' => $result,
                    'parsing' => (object) [
                        'yAxisKey' => 'pembelian'
                    ],
                    'backgroundColor' => '#20c997',
                    'borderColor' => '#20c997',
                    'borderWidth' => 2
                ],
                (object) [
                    'label' => 'Redeemtion',
                    'data' => $result,
                    'parsing' => (object) [
                        'yAxisKey' => 'redeemtion'
                    ],
                    'backgroundColor' => '#6610f2',
                    'borderColor' => '#6610f2',
                    'borderWidth' => 2
                ],
                (object) [
                    'label' => 'Completion',
                    'data' => $result,
                    'parsing' => (object) [
                        'yAxisKey' => 'completion'
                    ],
                    'backgroundColor' => 'rgb(255, 205, 86)',
                    'borderColor' => 'rgb(255, 205, 86)',
                    'borderWidth' => 2
                ],

            ]
        ];


        return response()->json($data);
    }


    public function penjualanMitra(Request $request)
    {
        $year = $request->year ?? 2025;
        $mitras = array_map(function ($x) {
            return $x->name;
        }, json_decode($this->mitra->get()));
        $query = $this->transaction()
            ->leftJoin('mitras as m', 'm.id', '=', 't.mitra_id')
            // ->whereNotNull('t.finish_at')
            ->select('t.*', 'k.*', 'm.name as mitra_name')
            ->where(DB::raw("YEAR(t.created_at)"), "=", $year);

        $redeem_period = $request->redeem_period;
        $finish_period = $request->finish_period;

        $nama_kelas = $request->name;
        $kelas_id = $request->kelas_id;

        if (!empty($nama_kelas)) {
            $query->where('k.name', $nama_kelas);
        } else if (!empty($kelas_id)) {
            $query->where('t.kelas_id', $kelas_id);
        }
        if (!empty($redeem_period)) {
            $query->where('t.redeem_period', $redeem_period);
        }
        // if (!empty($finish_period)) {
        //     $query->where('t.finish_period', $finish_period);
        // }

        $datas = $query->get();
        $arrayMitras = [];
        $result = [];
        foreach ($datas as $data) {
            $arrayMitras[$data->mitra_name][] = $data->id;
        }


        foreach ($arrayMitras as $key => $m) {
            $obj = (object) [
                'x' => $key,
                'value' => count($arrayMitras[$key])
            ];
            array_push($result, $obj);
        }

        $data = (object) [
            'title' => $mitras,
            'datasets' => [
                (object) [
                    'label' => 'Jumlah Penjualan Terafiliasi Mitra',
                    'data' => $result,
                    'parsing' => (object) [
                        'yAxisKey' => 'value'
                    ],
                    'backgroundColor' => '#20c997',
                    'borderColor' => '#20c997',
                    'borderWidth' => 2
                ],


            ]
        ];

        return response()->json($data);
    }
}
