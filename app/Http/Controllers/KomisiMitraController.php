<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Mitra;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportKomisi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UpdateCommissionRequest;





class KomisiMitraController extends Controller
{
    protected $mitra;
    protected $kelas;
    protected $transaction;
    protected $digitalPlatform;
    protected $navbar;
    protected $db;
    protected $path = 'komisi';


    public function __construct(Mitra $mitra, Transaction $transaction, DB $db)
    {
        parent::__construct();
        $this->mitra = $mitra;
        $this->db = $db;
        $this->transaction = $transaction;
        $this->navbar = Str::slug(strtolower('List Komisi Mitra'));
    }

    private function query($request)
    {

        $query = $this->db::table('transactions as t')
            // ->leftJoin('users as uc', 't.user_create', '=', 'uc.id')
            // ->leftJoin('users as uu', 't.user_update', '=', 'uu.id')
            ->leftJoin('kelas as k', 't.kelas_id', '=', 'k.id')
            ->leftJoin('pesertas as p', 't.peserta_id', '=', 'p.id')
            // ->leftJoin('digital_platforms as dp', 'p.digital_platform_id', '=', 'dp.id')
            ->leftJoin('mitras as m', 't.mitra_id', '=', 'm.id')
            ->select(
                't.*',
                // 'uc.name as create_by',
                // 'uu.name as update_by',
                'k.name as nama_kelas',
                'k.jadwal_name',
                'k.is_prakerja',
                'k.metode',
                'k.date',
                'k.jam',
                'k.price',
                'p.name as peserta_name',
                'p.email',
                'p.phone',
                // 'dp.name as dp_name',
                'm.name as mitra_name',
            );


        // Search by mitra_id
        if (!empty($request->mitra_id)) {
            $query = $query->where('t.mitra_id', $request->mitra_id);
        } else {
            $query = $query->where('t.mitra_id', "alll");
        }


        if (!empty($request->period)) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('redeem_period', 'LIKE', "%$request->period%")
                    ->orWhere('finish_period', 'LIKE', "%$request->period%");
            });
        }

        return $query->whereNotNull('finish_at')->orderBy('commission_percentage');
    }
    public function index(Request $request)
    {
        $title = 'List Komisi Mitra';
        $navbar = $this->navbar;
        $query = $this->query($request);
        $mitras = $this->mitra::get();
        $total_commission = $query->sum('t.commission_value');
        $total_kelas = $query->sum('k.price');
        $total_refund = $query->sum('t.finish_refund');
        $transactions = $query->paginate(50)
            ->appends(request()->query());
        return view("$this->path/index", compact('title', 'navbar', 'transactions', 'mitras', 'total_commission', 'total_refund', 'total_kelas'));
    }


    public function update(UpdateCommissionRequest $request): JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            foreach ($data["ids"] as $d) {
                $transaction = $this->transaction::with('kelas')->find($d);
                $harga_kelas = $transaction->kelas->price;
                $commission_value = $harga_kelas * $data['commission'] / 100;
                $transaction->commission_percentage = $data['commission'];
                $transaction->commission_value = $commission_value;
                $transaction->save();
            }
            // $this->transaction->whereIn('id', $data['ids'])->update([
            //     'commission_percentage' => $data['commission']
            // ]);
            $this->successUpdate("Berhasil mengubah data komisi");
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'errors' => (object) [
                    'ids' => $th->getMessage()
                ],
            ], 400);
        }

        return response()->json([
            'success' => true,
        ], 200);
    }


    public function export(Request $request)
    {
        $query = $this->query($request);


        if (!request()->query('search')) {
            $this->errorStatus("Mohon lakukan pencarian lebih dulu!");
            return redirect()->back();
        }
        $total = [
            ['Total Harga Kelas:', number_format($query->sum('k.price'))],
            ['Total Komisi Mitra:', number_format($query->sum('t.commission_value'))],
            ['Total Refund:', number_format($query->sum('t.finish_refund'))],
        ];

        $transactions = $query->get();
        $array = array_map(function ($x) use (&$i) {
            return [
                ++$i,
                $x->peserta_name,
                $x->email,
                $x->phone,
                $x->mitra_name,
                $x->nama_kelas,
                $x->jadwal_name,
                $x->is_prakerja === 1 ? 'Prakerja' : 'Umum',
                $x->metode,
                $x->date,
                $x->jam,
                number_format($x->price),
                $x->invoice,
                $x->voucher,
                // $x->redeem_code,
                // $x->redeem_at,
                // $x->finish_at,
                $x->redeem_period,
                $x->finish_period,
                // number_format($x->redeem_refund),
                // $x->redeem_note,
                number_format($x->finish_refund),
                $x->finish_note,
                number_format($x->commission_percentage),
                number_format($x->commission_value),
                // number_format($x->redeem_paid),
                // number_format($x->finish_paid),
            ];
        }, json_decode($transactions));

        $time = Carbon::now()->toDateString();
        // dd($array);
        $data = [$array];
        return Excel::download(new ExportKomisi(collect($data), $total), 'List Komisi Mitra' . $time . '.xlsx');
    }
}
