<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DigitalPlatform;
use App\Http\Requests\StoreDigitalPlatformRequest;
use App\Http\Requests\UpdateDigitalPlatformRequest;

class DigitalPlatformController extends Controller
{


    protected $dp;
    protected $navbar;
    protected $path = 'dp';
    public function __construct(DigitalPlatform $dp)
    {
        parent::__construct();
        $this->dp = $dp;
        $this->navbar = Str::slug(strtolower('List Digital Platform'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'List Digital Platform';
        $navbar = $this->navbar;
        $query = $this->dp::with(['uc', 'uu']);
        if (!empty($request->name)) {
            $query = $query->where('name', 'like', '%' . $request->name . '%');
        }
        $dps = $query->get();
        return view("$this->path/index", compact('title', 'navbar', 'dps'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Digital Platform Create';
        $navbar = $this->navbar;
        $dp = null;
        return view("$this->path/form", compact('title', 'navbar', 'dp'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDigitalPlatformRequest $request)
    {
        $data = $request->validated();
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;

        $this->dp->create($data);
        $this->successCreate();
        return redirect()->route('list-dp');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DigitalPlatform $digitalPlatform)
    {
        $title = 'Digital Platform Edit';
        $navbar = $this->navbar;
        $dp = $digitalPlatform;
        return view("$this->path/form", compact('title', 'navbar', 'dp'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDigitalPlatformRequest $request, DigitalPlatform $digitalPlatform)
    {
        $data = $request->validated();
        $data['user_update'] = $this->user->id;
        $digitalPlatform->update($data);
        $this->successUpdate();
        return redirect()->route('list-dp');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DigitalPlatform $digitalPlatform)
    {
        $digitalPlatform->delete();
        $this->successDestroy();
        return response()->json(['success' => true, 'message' => 'Berhasil menghapus data'], 200);
    }
}
