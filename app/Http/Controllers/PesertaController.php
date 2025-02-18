<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mitra;
use App\Models\Peserta;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\PesertaImport;
use App\Models\DigitalPlatform;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StorePesertaRequest;
use App\Http\Requests\ImportPesertaRequest;
use App\Http\Requests\UpdatePesertaRequest;

class PesertaController extends Controller
{

    protected $peserta;
    protected $mitra;
    protected $kelas;
    protected $digitalPlatform;
    protected $navbar;
    protected $path = 'peserta';
    public function __construct(Peserta $peserta, Kelas $kelas, DigitalPlatform $digitalPlatform, Mitra $mitra)
    {
        parent::__construct();
        $this->peserta = $peserta;
        $this->kelas = $kelas;
        $this->mitra = $mitra;
        $this->digitalPlatform = $digitalPlatform;
        $this->navbar = Str::slug(strtolower('Master Data Peserta'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Master Data Peserta';
        $navbar = $this->navbar;
        $query = $this->peserta::with(['uc', 'uu']);

        // Search by peserta name or email
        if (!empty($request->name)) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
                $query->orWhere('email', 'like', '%' . $request->name . '%');
            });
        }
        $pesertas = $query->paginate(20)
            ->appends(request()->query());
        return view("$this->path/index", compact('title', 'navbar', 'pesertas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Peserta Create';
        $navbar = $this->navbar;
        $peserta = null;
        return view("$this->path/form", compact('title', 'navbar', 'peserta', ));

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePesertaRequest $request)
    {
        $data = $request->validated();
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;
        DB::beginTransaction();
        // $transaction_data['peserta_id'] = $peserta->id;
        // $transaction_data['kelas_id'] = $data['kelas_id'];
        // $transaction_data['voucher'] = $data['voucher'];
        // $transaction_data['invoice'] = $data['invoice'];
        // $transaction_data['user_create'] = $this->user->id;
        // $transaction_data['user_update'] = $this->user->id;
        // Transaction::create($transaction_data);
        // $transaction_data = [];
        try {

            $this->peserta::create($data);
            DB::commit();
            $this->successCreate();
            return redirect()->route('master-data-peserta');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Peserta $peserta)
    {
        $title = 'Peserta Edit';
        $navbar = $this->navbar;

        return view("$this->path/form", compact('title', 'navbar', 'peserta', ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peserta $peserta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePesertaRequest $request, Peserta $peserta)
    {
        $data = $request->validated();
        $data['user_update'] = $this->user->id;
        $peserta->update($data);
        $this->successUpdate();
        return redirect()->route('master-data-peserta');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peserta $peserta)
    {
        //
    }


    public function fetch(Peserta $peserta)
    {

        return response()->json([
            'success' => true,
            'data' => $peserta
        ]);

    }
}
