<?php

namespace App\Http\Controllers;

use App\Exports\ExportErrors;
use App\Exports\ExportMarketing;
use App\Exports\ExportPayment;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Mitra;
use App\Models\Peserta;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ImportRefund;
use App\Imports\ImportPayment;
use App\Imports\PesertaImport;
use App\Models\DigitalPlatform;
use App\Imports\CompletionImport;
use App\Imports\RedemptionImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreImportPayment;
use App\Http\Requests\ImportPesertaRequest;
use App\Http\Requests\ImportRedemptionRequest;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\RefundRedeemPeriodRequest;
use App\Http\Requests\UpdateRedeemPeriodRequest;
use App\Http\Requests\UpdateRedeemCompleteRequest;

class TransactionController extends Controller
{

    protected $peserta;
    protected $mitra;
    protected $kelas;
    protected $transaction;
    protected $digitalPlatform;
    protected $navbar;
    protected $navbarPayment;
    protected $db;
    protected $path = 'transaksi';


    public function __construct(Peserta $peserta, Kelas $kelas, DigitalPlatform $digitalPlatform, Mitra $mitra, Transaction $transaction, DB $db)
    {
        parent::__construct();
        $this->peserta = $peserta;
        $this->kelas = $kelas;
        $this->mitra = $mitra;
        $this->db = $db;
        $this->transaction = $transaction;
        $this->digitalPlatform = $digitalPlatform;
        $this->navbar = Str::slug(strtolower('List Peserta'));
        $this->navbarPayment = Str::slug(strtolower('List Pembayaran'));
    }


    private function query($request)
    {

        $query = $this->db::table('transactions as t')
            ->leftJoin('users as uc', 't.user_create', '=', 'uc.id')
            ->leftJoin('users as uu', 't.user_update', '=', 'uu.id')
            ->leftJoin('kelas as k', 't.kelas_id', '=', 'k.id')
            ->leftJoin('pesertas as p', 't.peserta_id', '=', 'p.id')
            ->leftJoin('digital_platforms as dp', 't.digital_platform_id', '=', 'dp.id')
            ->leftJoin('mitras as m', 't.mitra_id', '=', 'm.id')
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
                'p.phone',
                'dp.name as dp_name',
                'm.name as mitra_name',
            );

        // Search by peserta name or email
        if (!empty($request->name)) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('p.name', 'like', '%' . $request->name . '%');
                $query->orWhere('p.email', 'like', '%' . $request->name . '%');
            });
        }

        // Search by voucher
        if (!empty($request->voucher)) {
            $query = $query->where('t.voucher', $request->voucher);
        }
        // Search by invoice
        if (!empty($request->invoice)) {
            $query = $query->where('t.invoice', $request->invoice);
        }

        // Search by redeem_code
        if (!empty($request->redeem_code)) {
            $query = $query->where('t.redeem_code', $request->redeem_code);
        }

        // Search by finish_at
        if (!empty($request->finish_at)) {
            $query = $query->where('t.finish_at', 'like', '%' . $request->finish_at . '%');
        }

        // Search by is_finished
        if (!empty($request->is_finished)) {
            $query = $query->whereNotNull('t.finish_at');
        }

        // Search by kelas_id
        if (!empty($request->kelas_id)) {
            $query = $query->where('t.kelas_id', $request->kelas_id);
        }

        // Search by mitra_id
        if (!empty($request->mitra_id)) {
            $query = $query->where('t.mitra_id', $request->mitra_id);
        }
        // Search by digital_platform_id
        if (!empty($request->digital_platform_id)) {
            $query = $query->where('p.digital_platform_id', $request->digital_platform_id);
        }
        // Search by nama kelas
        if (!empty($request->class_name)) {
            $query = $query->where('k.name', $request->class_name);
        }
        // Search by periode redeem
        if (!empty($request->redeem_period)) {
            $query = $query->where('t.redeem_period', 'like', "%$request->redeem_period%");
        }
        // Search by periode completion
        if (!empty($request->finish_period)) {
            $query = $query->where('t.finish_period', 'like', "%$request->finish_period%");
        }

        return $query;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'List Peserta';
        $navbar = $this->navbar;
        $query = $this->query($request);
        $kelas = $this->kelas::get();
        $mitras = $this->mitra::get();
        $digitalPlatforms = $this->digitalPlatform::get();
        $listClassName = array_map(function ($x) {
            return (object) [
                'id' => $x,
                'name' => $x,
            ];
        }, array_unique(array_column(json_decode($kelas, true), 'name')));

        $transactions = $query->paginate(50) //UPDATE 50 Perpage
            ->appends(request()->query());

        return view("$this->path/index", compact('title', 'navbar', 'transactions', 'kelas', 'mitras', 'digitalPlatforms', 'listClassName'));
    }

    public function paymentIndex(Request $request)
    {
        $title = 'List Pembayaran';
        $navbar = $this->navbarPayment;
        $query = $this->query($request);
        $kelas = $this->kelas::get();
        $mitras = $this->mitra::get();
        $digitalPlatforms = $this->digitalPlatform::get();
        $listClassName = array_map(function ($x) {
            return (object) [
                'id' => $x,
                'name' => $x,
            ];
        }, array_unique(array_column(json_decode($kelas, true), 'name')));

        $total = (object) [
            'redeem_paid' => null,
            'finish_paid' => null,
            'redeem_refund' => null,
            'finish_refund' => null,
        ];


        if (request()->query('search')) {
            $total->redeem_paid = $query->sum('t.redeem_paid');
            $total->finish_paid = $query->sum('t.finish_paid');
            $total->redeem_refund = $query->sum('t.redeem_refund');
            $total->finish_refund = $query->sum('t.finish_refund');
        }

        $transactions = $query->paginate(20)
            ->appends(request()->query());
        // dd($total);

        return view("$this->path/index", compact('title', 'navbar', 'transactions', 'kelas', 'mitras', 'digitalPlatforms', 'listClassName', 'total'));
    }


    public function marketingIndex(Request $request)
    {
        $title = 'List Peserta';
        $navbar = $this->navbar;
        $query = $this->query($request);
        $kelas = $this->kelas::get();
        $mitras = $this->mitra::get();
        $digitalPlatforms = $this->digitalPlatform::get();
        $listClassName = array_map(function ($x) {
            return (object) [
                'id' => $x,
                'name' => $x,
            ];
        }, array_unique(array_column(json_decode($kelas, true), 'name')));


        if (!request()->query('search')) {
            $query->where('t.mitra_id', "alll");
        }

        if (empty($request->name) && empty($request->voucher) && empty($request->invoice) && empty($request->mitra_id) && empty($request->redeem_period) && empty($request->finish_period)) {
            $query->where('t.mitra_id', "alll");
        }

        $transactions = $query->paginate(50) //UPDATE 50 Perpage
            ->appends(request()->query());

        return view("$this->path/index-marketing", compact('title', 'navbar', 'transactions', 'kelas', 'mitras', 'digitalPlatforms', 'listClassName'));
    }

    public function export(Request $request)
    {
        $tipe = $request->tipe;
        $query = $this->query($request);


        if (!request()->query('search')) {
            $this->errorStatus("Mohon lakukan pencarian lebih dulu!");
            return redirect()->back();
        }
        $total = [
            ['Bayar Redeem:', $query->sum('t.redeem_paid')],
            ['Bayar Completion', $query->sum('t.finish_paid')],
            ['Refund Redeem', $query->sum('t.redeem_refund')],
            ['Refund Completion', $query->sum('t.finish_refund')],
        ];
        $transactions = $query->get();
        $array = array_map(function ($x) use (&$i) {
            return [
                ++$i,
                $x->peserta_name,
                $x->email,
                $x->nama_kelas,
                $x->jadwal_name,
                $x->is_prakerja === 1 ? 'Prakerja' : 'Umum',
                $x->metode,
                $x->date,
                $x->jam,
                number_format($x->price),
                $x->dp_name,
                $x->invoice,
                $x->voucher,
                $x->mitra_name,
                $x->redeem_code,
                $x->redeem_at,
                $x->finish_at,
                number_format($x->redeem_paid),
                $x->redeem_period,
                number_format($x->redeem_refund),
                $x->redeem_note,
                number_format($x->finish_paid),
                $x->finish_period,
                number_format($x->finish_refund),
                $x->finish_note,
            ];
        }, json_decode($transactions));

        $time = Carbon::now()->toDateString();
        // dd($array);
        $data = [$array];
        return Excel::download(new ExportPayment(collect($data), $total), 'List Pembayaran ' . $time . '.xlsx');
    }

    public function exportMarketing(Request $request)
    {
        $query = $this->query($request);

        if (!request()->query('search')) {
            $this->errorStatus("Mohon lakukan pencarian lebih dulu!");
            return redirect()->back();
        }

        $transactions = $query->get();
        $array = array_map(function ($x) use (&$i) {
            return [
                ++$i,
                $x->peserta_name,
                $x->email,
                $x->phone,
                $x->nama_kelas,
                $x->jadwal_name,
                $x->is_prakerja === 1 ? 'Prakerja' : 'Umum',
                $x->metode,
                $x->date,
                $x->jam,
                number_format($x->price),
                $x->dp_name,
                $x->invoice,
                $x->voucher,
                $x->mitra_name,
                $x->redeem_code,
                $x->redeem_at,
                $x->finish_at,
                $x->redeem_period,
                $x->finish_period,
                number_format($x->redeem_refund),
                $x->redeem_note,
                number_format($x->finish_refund),
                $x->finish_note,
            ];
        }, json_decode($transactions));

        $time = Carbon::now()->toDateString();
        // dd($array);
        $data = [$array];
        return Excel::download(new ExportMarketing(collect($data)), 'List Peserta ' . $time . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Peserta Add Pelatihan';
        $kelas = $this->kelas::get();
        $navbar = $this->navbar;
        $mitras = $this->mitra::get();
        $pesertas = $this->peserta::get();

        $digitalPlatforms = $this->digitalPlatform::get();
        return view("$this->path/form", compact('title', 'navbar', 'kelas', 'mitras', 'pesertas', 'digitalPlatforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        $kelas = $this->kelas::findOrFail($data['kelas_id']);

        // Check is user already have class?
        $user_transactions = $this->transaction::where('peserta_id', $data['peserta_id'])->pluck('kelas_id')->toArray();
        if (count($user_transactions) > 0) {
            foreach ($user_transactions as $ut) {
                $user_kelas = Kelas::find($ut);
                if (strtolower($kelas->name) == strtolower($user_kelas->name)) {
                    throw ValidationException::withMessages(['kelas_id' => 'Email sudah memiliki kelas ini']);
                    return redirect()->back()->withInput();
                }
            }
        }
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;
        $this->transaction::create($data);
        $this->successCreate();
        return redirect()->route('list-peserta');
    }

    /**
     * Display the specified resource.
     */
    public function import($tipe)
    {
        $title = 'Import ' . ucwords($tipe);
        $navbar = $this->navbar;
        $error_imports = DB::table('errors')->get();
        return view("$this->path/import", compact('title', 'navbar', 'error_imports', 'tipe'));
    }

    public function importPayment($tipe)
    {
        $title = 'Import ' . ucwords($tipe);
        $navbar = $this->navbarPayment;
        $error_imports = DB::table('errors')->get();
        return view("$this->path/import", compact('title', 'navbar', 'error_imports', 'tipe'));
    }

    public function importPembelian()
    {
        $title = 'Peserta Import';
        $peserta = null;
        $navbar = $this->navbar;
        $mitras = $this->mitra::get();
        $kelas = $this->kelas::get();
        $digitalPlatform = $this->digitalPlatform::get();
        $error_imports = DB::table('errors')->get();
        return view("$this->path/import-pembelian", compact('title', 'navbar', 'peserta', 'kelas', 'digitalPlatform', 'mitras', 'error_imports'));
    }


    public function storeImport(ImportRedemptionRequest $request, $tipe)
    {
        $request->validated();
        if ($tipe === 'redemption') {
            $import = new RedemptionImport();
        } else {
            $import = new CompletionImport();
        }
        Excel::import($import, $request->file('file'));


        $result = $import->getResult();
        if ($result['success'] > 0) {
            $this->successCreate("Berhasil mengimport data " . $result['success']);
        }
        if ($result['errors'] > 0) {
            $this->errorStatus("Gagal mengimport data " . $result['errors']);
        }
        return redirect()->back();
    }

    public function storeImportPayment(StoreImportPayment $request, $tipe)
    {
        session()->remove('tipe_import');
        $data = $request->validated();


        if ($tipe === 'payment') {
            $import = new ImportPayment($data['tipe']);
        } else {
            $import = new ImportRefund($data['tipe']);
        }
        Excel::import($import, $request->file('file'));

        $result = $import->getResult();
        if ($result['success'] > 0) {
            $this->successCreate("Berhasil mengimport data " . $result['success']);
        }
        if ($result['errors'] > 0) {
            $this->errorStatus("Gagal mengimport data " . $result['errors']);
        }
        session()->put('tipe_import', $data['tipe']);
        return redirect()->back()->withInput();
    }


    public function storeImportPembelian(ImportPesertaRequest $request)
    {
        $data = $request->validated();
        $import = new PesertaImport($data['mitra_id'], $data['digital_platform_id'], $data['kelas_id']);
        Excel::import($import, $request->file('file'));
        $result = $import->getResult();
        if ($result['success'] > 0) {
            $this->successCreate("Berhasil mengimport data " . $result['success']);
        }
        if ($result['errors'] > 0) {
            $this->errorStatus("Gagal mengimport data " . $result['errors']);
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // if ($transaction->redeem_code) {
        //     $this->errorStatus("Redeem code telah terisi, tidak dapat mengubah pelatihan");
        //     return redirect()->back();
        // }
        $title = 'Peserta Edit Pelatihan';
        $kelas = $this->kelas::get();
        $navbar = $this->navbar;
        $mitras = $this->mitra::get();
        $digitalPlatforms = $this->digitalPlatform::get();
        $transaction = $this->transaction::with(['mitra', 'dp', 'peserta'])->findOrFail($transaction->id);
        return view("$this->path/form", compact('title', 'navbar', 'kelas', 'mitras', 'digitalPlatforms', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        $kelas = $this->kelas::findOrFail($data['kelas_id']);

        // Check is user already have class?
        $user_transactions = $this->transaction::where('peserta_id', $transaction->peserta_id)
            ->whereNot('id', $transaction->id)
            ->pluck('kelas_id')->toArray();

        if (count($user_transactions) > 0) {
            foreach ($user_transactions as $ut) {
                $user_kelas = Kelas::find($ut);
                if (strtolower($kelas->name) == strtolower($user_kelas->name)) {
                    throw ValidationException::withMessages(['kelas_id' => 'Email sudah memiliki kelas ini']);
                    return redirect()->back()->withInput();
                }
            }
        }

        $data['user_update'] = $this->user->id;
        $transaction->update($data);
        $this->successUpdate();
        return redirect()->route('list-peserta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function redeemCompleteView(Transaction $transaction)
    {
        // if ($transaction->redeem_code) {
        //     $this->errorStatus("Redeem code telah terisi, tidak dapat mengubah pelatihan");
        //     return redirect()->back();
        // }
        $title = 'Peserta Edit Redeem/Complete';
        $kelas = $this->kelas::get();
        $navbar = $this->navbar;
        $mitras = $this->mitra::get();
        $digitalPlatforms = $this->digitalPlatform::get();
        $transaction = $this->transaction::with(['mitra', 'dp', 'peserta'])->findOrFail($transaction->id);
        return view("$this->path/form", compact('title', 'navbar', 'kelas', 'mitras', 'digitalPlatforms', 'transaction'));
    }

    public function redeemCompleteUpdate(UpdateRedeemCompleteRequest $request, Transaction $transaction)
    {
        $data = $request->validated();

        if (!empty($data['finish_at']) && (empty($data['redeem_code']) || empty($data['redeem_at']))) {
            throw ValidationException::withMessages(['finish_at' => "Redeem Code dan Waktu Redeem harus diisi"]);
            return redirect()->back()->withInput();
        }

        if (!empty($data['redeem_code']) && empty($data['redeem_at'])) {
            throw ValidationException::withMessages(['redeem_at' => "Waktu Redeem harus diisi"]);
            return redirect()->back()->withInput();
        }


        if (count($data) > 0) {
            $transaction->update($data);
        }
        $this->successUpdate();
        return redirect()->route('list-peserta');
    }

    public function payment(Transaction $transaction)
    {
        $title = 'Peserta Ubah Pembayaran';
        $navbar = $this->navbarPayment;
        $transaction = $this->transaction::with(['mitra', 'dp', 'peserta'])->findOrFail($transaction->id);
        return view("$this->path/payment-refund", compact('title', 'navbar', 'transaction'));
    }
    public function refund(Transaction $transaction)
    {
        $title = 'Peserta Ubah Refund';
        $navbar = $this->navbarPayment;
        $transaction = $this->transaction::with(['mitra', 'dp', 'peserta'])->findOrFail($transaction->id);
        return view("$this->path/payment-refund", compact('title', 'navbar', 'transaction'));
    }

    public function paymentUpdate(UpdateRedeemPeriodRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        $message = [];

        // Jika keterangan terisi namun tidak terceklis
        if (empty($data['check_redeem_period']) && !empty($data['redeem_period'])) {
            throw ValidationException::withMessages(['redeem_period' => 'Bayar Redeem belum terceklis']);
            return redirect()->back()->withInput();
        }

        if (empty($data['check_finish_period']) && !empty($data['finish_period'])) {
            throw ValidationException::withMessages(['finish_period' => 'Bayar Completion belum terceklis']);
            return redirect()->back()->withInput();
        }


        if (!empty($data['check_redeem_period']) && empty($data['redeem_period'])) {
            throw ValidationException::withMessages(['redeem_period' => 'Periode Redeem belum terisi']);
            return redirect()->back()->withInput();
        }

        if (!empty($data['check_finish_period']) && empty($data['finish_period'])) {
            throw ValidationException::withMessages(['finish_period' => 'Periode Completion belum terisi']);
            return redirect()->back()->withInput();
        }


        if (!empty($data['finish_period']) && empty($data['redeem_period'])) {
            throw ValidationException::withMessages(['redeem_period' => "Periode Redeem harus diisi"]);
            return redirect()->back()->withInput();
        }


        /** Jika redeem period diisi pastikan redeem code dan waktu redeem sudah diisi */
        if (empty($transaction->redeem_code)) {
            $message[] = "Redeem Code belum terisi";
        }

        if (empty($transaction->redeem_at)) {
            $message[] = "Tanggal Redeem belum terisi";
        }

        if (!empty($transaction->redeem_period) && $transaction->redeem_period !== $data['redeem_period']) {
            $message[] = "Periode Redeem yang terisi, tidak bisa diubah. Periode Redeem yang terisi ($transaction->redeem_period)";
        }


        $harga_kelas = $transaction->kelas->price;
        $redeem_paid = $harga_kelas * (30 / 100);
        $finish_paid = $harga_kelas * (70 / 100);

        if (!empty($data['redeem_period'])) {
            $data['redeem_paid'] = $redeem_paid;
            if (count($message) > 0) {
                throw ValidationException::withMessages(['redeem_period' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }

        if (!empty($transaction->finish_period) && $transaction->finish_period !== $data['finish_period']) {
            throw ValidationException::withMessages(['finish_period' => "Periode Completion yang terisi, tidak bisa diubah. Periode Completion yang terisi ($transaction->finish_period)"]);
            return redirect()->back()->withInput();
        }


        if (empty($transaction->finish_at)) {
            $message[] = "100% Pelatihan belum terisi";
        }

        if (!empty($data['finish_period'])) {

            $data['finish_paid'] = $finish_paid;
            if (count($message) > 0) {
                throw ValidationException::withMessages(['finish_period' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }





        // Jika checklist sudah terisi keterangan tidak boleh kosong
        if ($transaction->redeem_period && empty($data['redeem_period'])) {
            throw ValidationException::withMessages(['redeem_period' => 'Bayar Redeem yang terceklis, periode tidak boleh kosong']);
            return redirect()->back()->withInput();
        }
        if ($transaction->finish_period && empty($data['finish_period'])) {
            throw ValidationException::withMessages(['finish_period' => 'Bayar Completion yang terceklis, periode tidak boleh kosong']);
            return redirect()->back()->withInput();
        }





        $data['user_update'] = $this->user->id;
        $transaction->update($data);
        $this->successUpdate();
        return redirect()->route('list-pembayaran');
    }
    public function refundUpdate(RefundRedeemPeriodRequest $request, Transaction $transaction)
    {
        $data = $request->validated();

        // Jika keterangan terisi namun tidak terceklis
        if (empty($data['check_redeem_note']) && !empty($data['redeem_note'])) {
            throw ValidationException::withMessages(['redeem_note' => 'Refund Redeem belum terceklis']);
            return redirect()->back()->withInput();
        }


        if (empty($data['check_finish_note']) && !empty($data['finish_note'])) {
            throw ValidationException::withMessages(['finish_note' => 'Refund Completion belum terceklis']);
            return redirect()->back()->withInput();
        }

        if (!empty($data['check_finish_note']) && empty($data['finish_note'])) {
            throw ValidationException::withMessages(['finish_note' => 'Keterangan belum terisi']);
            return redirect()->back()->withInput();
        }

        if (!empty($data['check_redeem_note']) && empty($data['redeem_note'])) {
            throw ValidationException::withMessages(['redeem_note' => 'Keterangan belum terisi']);
            return redirect()->back()->withInput();
        }

        $message = [];



        if (empty($data['finish_note']) && !empty($data['redeem_note'])) {
            throw ValidationException::withMessages(['redeem_note' => "Keterangan Completion harus diisi"]);
            return redirect()->back()->withInput();
        }

        /** Jika redeem period diisi pastikan redeem code dan waktu redeem sudah diisi */
        if (empty($transaction->redeem_code)) {
            $message[] = "Redeem Code belum terisi";
        }

        if (empty($transaction->redeem_at)) {
            $message[] = "Tanggal Redeem belum terisi";
        }
        if (!empty($transaction->finish_refund) && empty($data["check_finish_note"] ?? null)) {
            $message[] = "Refund Completion yang terisi, tidak bisa di uncheck";
            if (count($message) > 0) {
                throw ValidationException::withMessages(['finish_note' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }
        if (!empty($transaction->redeem_refund) && empty($data["check_redeem_note"] ?? null)) {
            $message[] = "Refund Redeemtion yang terisi, tidak bisa di uncheck";
            if (count($message) > 0) {
                throw ValidationException::withMessages(['redeem_note' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }

        // dd($message);

        $harga_kelas = $transaction->kelas->price;
        $redeem_refund = $harga_kelas * (30 / 100);
        $finish_refund = $harga_kelas * (70 / 100);

        if (!empty($data['redeem_note'])) {
            $data['redeem_refund'] = $redeem_refund;
            if (count($message) > 0) {
                throw ValidationException::withMessages(['redeem_note' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }

        // dd($data, $transaction->finish_refund, $transaction->redeem_refund);

        if (empty($transaction->finish_at)) {
            $message[] = "100% Pelatihan belum terisi";
        }

        if (!empty($data['finish_note'])) {
            $data['finish_refund'] = $finish_refund;
            if (count($message) > 0) {
                throw ValidationException::withMessages(['finish_note' => implode(', ', $message)]);
                return redirect()->back()->withInput();
            }
        }



        $data['user_update'] = $this->user->id;
        $transaction->update($data);
        $this->successUpdate();
        return redirect()->route('list-pembayaran');
    }



    public function exportErrors($tipe)
    {
        $errors = DB::table('errors')->get();

        switch ($tipe) {
            case 'pembelian':
                $array = array_map(function ($x) use (&$i) {
                    return [
                        ++$i,
                        $x->name,
                        $x->email,
                        $x->phone,
                        $x->voucher,
                        $x->invoice,
                        $x->message,
                    ];
                }, json_decode($errors));
                break;
            case 'redeemtion':
                $array = array_map(function ($x) use (&$i) {
                    return [
                        ++$i,
                        $x->voucher,
                        $x->redeem_code,
                        $x->redeem_at,
                        $x->message,
                    ];
                }, json_decode($errors));
                break;
            case 'completion':
                $array = array_map(function ($x) use (&$i) {
                    return [
                        ++$i,
                        $x->voucher,
                        $x->finish_at,
                        $x->message,
                    ];
                }, json_decode($errors));
                break;
            case 'payment':
                $array = array_map(function ($x) use (&$i) {
                    return [
                        ++$i,
                        $x->invoice,
                        session('tipe_import') === 'Redeemtion' ? $x->redeem_period : $x->finish_period,
                        $x->message,
                    ];
                }, json_decode($errors));
                break;
            case 'refund':
                $array = array_map(function ($x) use (&$i) {
                    return [
                        ++$i,
                        $x->invoice,
                        session('tipe_import') === 'Redeemtion' ? $x->redeem_note : $x->finish_note,
                        $x->message,
                    ];
                }, json_decode($errors));
                break;
                break;
        }

        $time = Carbon::now()->toDateString();
        // dd($array);
        $data = [$array];
        return Excel::download(new ExportErrors(collect($data), $tipe), 'Errors ' . $tipe . '-' . $time . '.xlsx');
    }
}
