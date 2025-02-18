<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Trainer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Http\Requests\StoreKomisiTrainerRequest;

class TrainerController extends Controller
{

    protected $db;
    protected $trainer;
    protected $navbar;
    protected $kelas;
    protected $navbarPayment;
    protected $path = 'trainer';

    public function __construct(Trainer $trainer, DB $db, Kelas $kelas)
    {
        parent::__construct();
        $this->trainer = $trainer;
        $this->db = $db;
        $this->kelas = $kelas;
        $this->navbar = Str::slug(strtolower('Master Data Trainer'));
        $this->navbarPayment = Str::slug(strtolower('List Pembayaran Trainer'));
    }


    private function query($request)
    {

        $query = $this->db::table('transactions as t')
            ->leftJoin('users as uc', 't.user_create', '=', 'uc.id')
            ->leftJoin('users as uu', 't.user_update', '=', 'uu.id')
            ->leftJoin('kelas as k', 't.kelas_id', '=', 'k.id')
            ->leftJoin('trainers as tr', 'k.trainer_id', '=', 'tr.id')
            ->leftJoin('pesertas as p', 't.peserta_id', '=', 'p.id')
            ->select(
                't.*',
                'uc.name as create_by',
                'uu.name as update_by',
                'k.name as nama_kelas',
                'k.jadwal_name',
                'k.is_prakerja',
                'k.metode',
                'k.date',
                'k.jam',
                'k.price',
                'p.name as peserta_name',
                'p.email',
                'tr.name as trainer_name',
            );

        // Search by peserta name or email
        if (!empty($request->name)) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('p.name', 'like', '%' . $request->name . '%');
                $query->orWhere('p.email', 'like', '%' . $request->name . '%');
            });
        }

        return $query;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Master Data Trainer';
        $navbar = $this->navbar;
        $query = $this->trainer::with(['uc', 'uu']);
        if (!empty($request->name)) {
            $query = $query->where('name', 'like', '%' . $request->name . '%');
        }
        $trainers = $query->get();
        return view("$this->path/index", compact('title', 'navbar', 'trainers'));
    }
    public function paymentIndex(Request $request)
    {
        $title = 'List Pembayaran Trainer';
        $navbar = $this->navbarPayment;
        $query = $this->query($request);
        $trainers = $this->trainer::get();
        $transactions = $query->paginate(20)
            ->appends(request()->query());


        return view("$this->path/payment-index", compact('title', 'navbar', 'trainers',  'transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Master Data Trainer Create';
        $navbar = $this->navbar;
        $trainer = null;
        return view("$this->path/form", compact('title', 'navbar', 'trainer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrainerRequest $request)
    {
        $data = $request->validated();
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;
        $this->trainer::create($data);
        $this->successCreate();
        return redirect()->route('master-data-trainer');
    }

    /**
     * Display the specified resource.
     */
    public function indexKelasJadwal(Request $request)
    {
        $title = 'List Kelas & Jadwal';
        $navbar = 'komisi-trainer-kelas-jadwal';
        $query = $this->db::table('transaction_kelas as tk')
            ->leftJoin("kelas as k", 'tk.kelas_id', '=', 'k.id')
            ->select('tk.*', 'k.name', 'k.jadwal_name');

        if (!empty($request->class_name)) {
            $query = $query->where('k.name',  $request->class_name);
        }
        if (!empty($request->kelas_id)) {
            $query = $query->where('k.kelas_id',  $request->kelas_id);
        }
        if (!empty($request->year)) {
            $query = $query->whereYear('tk.created_at', $request->year);
            $datas = $query->paginate()->appends(request()->query());
        } else {
            $query = $query->whereYear('tk.created_at', 1990);
            $datas = $query->paginate()->appends(request()->query());
        }

        $kelas = $this->kelas::get();
        $listClassName = array_map(function ($x) {
            return (object) [
                'id' => $x,
                'name' => $x,
            ];
        }, array_unique(array_column(json_decode($kelas, true), 'name')));

        return view("$this->path/index-kelas", compact('title', 'navbar', 'datas', 'kelas', 'listClassName'));
    }



    public function editKelasJadwal($id)
    {
        $title = 'Edit Kelas & Jadwal';
        $navbar = "komisi-trainer-kelas-jadwal";
        $transaction_kelas = $this->db::table('transaction_kelas as tk')
            ->where('tk.id', $id)->first();
        $pivots = $this->db::table('pivot_kelas as pk')
            ->where('transaction_kelas_id', $id)
            ->get();
        // dd($transaction_kelas, $pivots);
        $trainers = $this->trainer::get();

        return view("$this->path/edit", compact('title', 'navbar', 'transaction_kelas', 'pivots', 'trainers'));
    }


    private function updatePivotKelas($tk_id, $day_numbers, $commission, $trainer_id, $trainer_name)
    {
        $this->db::table('pivot_kelas')->where('transaction_kelas_id', $tk_id)->whereIn('day_number', $day_numbers)
            ->update([
                'trainer_id_1' => $trainer_id,
                'trainer_name_1' => $trainer_name,
                'commission_1' => $commission,
                'updated_at' => Carbon::now()
            ]);
    }



    public function updateKelasJadwal(StoreKomisiTrainerRequest $request, $id)
    {
        $data = $request->validated();
        $tk =  $this->db::table('transaction_kelas')->where('id', $id)->first();

        if ($data['type'] === 'minimum') {
            $xp = explode('|', $data['trainer']);
            $trainer_id = $xp[0];
            $trainer_name = $xp[1];
            $day_numbers = [];
            for ($i = 1; $i <= $tk->day; $i++) {
                $day_numbers[] = $i;
            }
            $commission = $data['komisi'] / $tk->day;

            $this->updatePivotKelas($id, $day_numbers, $commission, $trainer_id, $trainer_name);

            $this->db::table('transaction_kelas')->where('id', $id)->update([
                'trainer_names' => implode(', ', [$trainer_name]),
                'trainer_ids' => implode(', ', [$trainer_id]),
                'total' => $data['komisi'],
                'status' => 'pending',
                'scheme' => ucwords($data['type'])
            ]);
        } else {
            $xp1 = explode('|', $data['trainer_1']);
            $trainer_id_1 = $xp1[0];
            $trainer_name_1 = $xp1[1];
            $commission_1 = $data['komisi_1'];
            $trainer_name_2 = null;
            $trainer_id_2 = null;
            $commission_2 = null;
            if ($data['trainer_2']) {
                $xp2 = explode('|', $data['trainer_2']);
                $trainer_id_2 = $xp2[0];
                $trainer_name_2 = $xp2[1];
                $commission_2 = $data['komisi_2'];
            }
            $this->db::table('pivot_kelas')->where('transaction_kelas_id', $id)
                ->where('day_number', $data['day'])
                ->update([
                    'trainer_id_1' => $trainer_id_1,
                    'trainer_name_1' => $trainer_name_1,
                    'commission_1' => $commission_1,
                    'trainer_id_2' => $trainer_id_2,
                    'trainer_name_2' => $trainer_name_2,
                    'commission_2' => $commission_2,
                    'updated_at' => Carbon::now()
                ]);
            $t_com_1 =  $this->db::table('pivot_kelas')->where('transaction_kelas_id', $id)->sum('commission_1');
            $t_com_2 =  $this->db::table('pivot_kelas')->where('transaction_kelas_id', $id)->sum('commission_2');
            $sumTotal = $t_com_1 + $t_com_2;
            $pivots = $this->db::table('pivot_kelas')->where('transaction_kelas_id', $id)->get();

            $array_names = [];
            $array_ids = [];
            foreach ($pivots as $piv) {
                array_push($array_names, $piv->trainer_name_1, $piv->trainer_name_2);
                array_push($array_ids, $piv->trainer_id_1, $piv->trainer_id_2);
            }

            array_push($array_names, $trainer_name_1);
            array_push($array_ids, $trainer_id_1);
            if ($trainer_name_2) {
                array_push($array_names, $trainer_name_2);
            }
            if ($trainer_id_2) {
                array_push($array_ids, $trainer_id_2);
            }
            $array_names =   array_filter(array_unique($array_names), function ($x) {
                return $x;
            });
            $array_ids = array_filter(array_unique($array_ids), function ($x) {
                return $x;
            });
            $trainer_names = implode(', ', $array_names);
            $trainer_ids = implode(', ', $array_ids);

            $this->db::table('transaction_kelas')->where('id', $id)->update([
                'trainer_names' => $trainer_names,
                'trainer_ids' => $trainer_ids,
                'total' => $sumTotal,
                'status' => 'pending',
                'scheme' => ucwords($data['type'])
            ]);
        }
        $this->successCreate("Berhasil memperbarui data");
        return redirect("/AplikasiMonitoring/komisi-trainer/kelas-jadwal/$id/edit");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trainer $trainer)
    {
        $title = 'Master Data Trainer Edit';
        $navbar = $this->navbar;
        return view("$this->path/form", compact('title', 'navbar', 'trainer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainerRequest $request, Trainer $trainer)
    {
        $data = $request->validated();
        $data['user_update'] = $this->user->id;
        $trainer->update($data);
        $this->successUpdate();
        return redirect()->route('master-data-trainer');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        $this->successDestroy();
        return response()->json(['message' => 'ok'], 200);
    }
}
