<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMitraRequest;
use App\Http\Requests\UpdateMitraRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MitraController extends Controller
{
    protected $mitra;
    protected $navbar;
    protected $path = 'mitra';
    public function __construct(Mitra $mitra)
    {
        parent::__construct();
        $this->mitra = $mitra;
        $this->navbar = Str::slug(strtolower('List Mitra'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'List Mitra';
        $navbar = $this->navbar;
        $query = $this->mitra::with(['uc', 'uu']);
        if (!empty($request->name)) {
            $query = $query->where('name', 'like', '%' . $request->name . '%');
        }
        $mitras = $query->get();
        return view("$this->path/index", compact('title', 'navbar', 'mitras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Mitra Create';
        $navbar = $this->navbar;
        $mitra = null;
        return view("$this->path/form", compact('title', 'navbar', 'mitra'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMitraRequest $request)
    {
        $data = $request->validated();
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;

        $this->mitra->create($data);
        $this->successCreate();
        return redirect()->route('list-mitra');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        $title = 'Mitra Edit';
        $navbar = $this->navbar;
        return view("$this->path/form", compact('title', 'navbar', 'mitra'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMitraRequest $request, Mitra $mitra)
    {
        $data = $request->validated();
        $data['user_update'] = $this->user->id;

        $mitra->update($data);
        $this->successUpdate();
        return redirect()->route('list-mitra');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra): JsonResponse
    {
        $notExist = $this->isTransactionExist($mitra->id, 'mitra_id');
        if (!$notExist) {
            throw new HttpResponseException(response()->json([
                'success' => 'false',
                'errors' => ['Mitra telah memiliki transaksi tidak dapat dihapus']
            ], 400));
        }
        $mitra->delete();
        $this->successDestroy();
        return response()->json(['success' => true, 'message' => 'Berhasil hapus data!'], 200);

    }
}
