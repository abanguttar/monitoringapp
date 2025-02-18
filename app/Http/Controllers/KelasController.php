<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Trainer;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class KelasController extends Controller
{


    protected $kelas;
    protected $navbar;
    protected $trainer;
    protected $path = 'kelas';
    public function __construct(Kelas $kelas, Trainer $trainer)
    {
        parent::__construct();
        $this->kelas = $kelas;
        $this->trainer = $trainer;
        $this->navbar = Str::slug(strtolower('List Data Kelas'));
    }


    private function pivotMock($start, $end, $t_id, $kelas_id)
    {
        // Insert pivot table
        $insertDatas = [];

        for ($i = $start; $i <= $end; $i++) {
            $insertDatas[] =   [
                'transaction_kelas_id' => $t_id,
                'kelas_id' => $kelas_id,
                'day_number' => $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        return $insertDatas;
    }



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'List Data Kelas';
        $navbar = $this->navbar;
        $query = $this->kelas::with(['uc', 'uu']);

        // Search by class name
        if (!empty($request->name)) {
            $query = $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Search by jadwal name
        if (!empty($request->jadwal_name)) {
            $query = $query->where('jadwal_name', 'like', '%' . $request->jadwal_name . '%');
        }

        $kelas = $query->paginate(20)
            ->appends(request()->query());
        return view("$this->path/index", compact('title', 'navbar', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Kelas Create';
        $navbar = $this->navbar;
        $kelas = null;
        $trainers = $this->trainer::get();
        return view("$this->path/form", compact('title', 'navbar', 'kelas', 'trainers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKelasRequest $request)
    {
        $data = $request->validated();
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;

        DB::beginTransaction();
        try {
            $kelas = $this->kelas->create($data);
            $transaction_kelas = DB::table('transaction_kelas')->insertGetId([
                'kelas_id' => $kelas->id,
                'day' => $kelas->day,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $insertDatas = $this->pivotMock(1, $kelas->day, $transaction_kelas, $kelas->id);
            DB::table('pivot_kelas')->insert($insertDatas);

            DB::commit();
            $this->successCreate();
            return redirect()->route('list-kelas');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->errorStatus("Ada kesalahan");
            Log::error("Error store kelas ", [
                'errors' => $th->getMessage()
            ]);
            return redirect()->back()->withInput();
            //throw $th;
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $notExist = $this->isTransactionExist($kelas->id, 'kelas_id');
        if (!$notExist) {
            $this->errorStatus("Kelas telah memiliki transaksi tidak dapat diubah");
            return redirect()->back();
        }
        $title = 'Kelas Edit';
        $navbar = $this->navbar;
        return view("$this->path/form", compact('title', 'navbar', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKelasRequest $request, Kelas $kelas)
    {
        $data = $request->validated();
        $data['user_update'] = $this->user->id;
        $kelas->update($data);
        DB::table('transaction_kelas')->where('kelas_id', $kelas->id)->update([
            'day' => $kelas->day
        ]);

        $transaction_kelas = DB::table('transaction_kelas')->where('kelas_id', $kelas->id)->first();

        (int) $pivot_kelas = DB::table('pivot_kelas')->where('kelas_id', $kelas->id)->count();
        if ((int) $kelas->day < $pivot_kelas) {
            DB::table('pivot_kelas')->where('kelas_id', $kelas->id)->where('day_number', '>', $kelas->day)->delete();
        } else {
            $sisa =  ++$pivot_kelas;
            $insertDatas = $this->pivotMock($sisa, $kelas->day, $transaction_kelas->id, $kelas->id);
            DB::table('pivot_kelas')->insert($insertDatas);
        }
        $this->successCreate();
        return redirect()->route('list-kelas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        $notExist = $this->isTransactionExist($kelas->id, 'kelas_id');
        if (!$notExist) {
            throw new HttpResponseException(response()->json([
                'success' => 'false',
                'errors' => ['Kelas telah memiliki transaksi tidak dapat dihapus']
            ], 400));
        }
        $kelas->delete();
        $this->successDestroy();
        return response()->json(['success' => true, 'message' => 'Berhasil hapus data!'], 200);
    }




}
