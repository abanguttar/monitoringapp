<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $admin;
    protected $navbar;
    protected $path = 'admin';
    public function __construct(User $admin)
    {
        parent::__construct();
        $this->admin = $admin;
        $this->navbar = Str::slug(strtolower('List User'));
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'superadmin') {
                session()->flash("error-status", "Anda tidak memiliki akses membuka halaman ini");
                return redirect('/dashboard');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $title = 'List User';
        $navbar = $this->navbar;
        $query = $this->admin::with(['uc', 'uu']);

        // Search by peserta name or email
        if (!empty($request->name)) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
                $query->orWhere('username', 'like', '%' . $request->name . '%');
            });
        }
        $users = $query->paginate(20)
            ->appends(request()->query());
        return view("$this->path/index", compact('title', 'navbar', 'users'));
    }

    public function create()
    {
        $title = 'User Create';
        $navbar = $this->navbar;
        $admin = null;
        return view("$this->path/form", compact('title', 'navbar', 'admin'));
    }


    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $password = Hash::make($data['password']);
        $data['password'] = $password;
        $data['user_create'] = $this->user->id;
        $data['user_update'] = $this->user->id;
        // $data['role'] = 'admin';
        $this->admin::create($data);
        $this->successCreate();
        return redirect()->route('list-user');
    }

    public function edit(User $admin)
    {
        $title = 'User Edit';
        $navbar = $this->navbar;
        return view("$this->path/form", compact('title', 'navbar', 'admin'));
    }


    public function update(UpdateUserRequest $request, User $admin)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $password = Hash::make($data['password']);
            $data['password'] = $password;
        } else {
            unset($data['password']);
        }
        $admin->update($data);
        $this->successUpdate();
        return redirect()->route('list-user');
    }


    public function permission(User $admin)
    {
        $title = 'User Permission';
        $navbar = $this->navbar;
        $permissions = DB::table('permissions')->orderBy('group')->orderBy('name')->get();
        $temp = [];

        foreach ($permissions as $permission) {
            $temp[$permission->group][$permission->name][] = (object) [
                'name' => $permission->access,
                'id' => $permission->id
            ];
        }

        $permissions = $temp;
        $user_permissions = DB::table('user_permissions')->where('user_id', $admin->id)->pluck('permission_id')->toArray();
        return view("$this->path/permission", compact('title', 'navbar', 'admin', 'permissions', 'user_permissions'));

    }


    public function permissionUpdate(Request $request, User $admin)
    {
        $access = $request->access;
        $datas = [];
        $user_permissions = DB::table('user_permissions');
        $truncate = $user_permissions;
        $truncate->truncate();
        if ($access !== null) {
            foreach ($access as $item) {
                array_push($datas, ['user_id' => $admin->id, 'permission_id' => $item]);
            }
            $user_permissions->insert($datas);
        }

        $key = 'user_permission=' . $admin->username;
        Cache::forget($key);
        $this->successUpdate();
        return redirect()->route('list-user');
    }
}
