<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $this->user = Auth::user();
            }
            return $next($request);

        });
    }

    public function isTransactionExist($id, $param)
    {
        // dd($param);
        if (Transaction::where($param, $id)->count() > 0) {
            return false;
        }
        return true;
    }
    public function successCreate($message = "Berhasil menambahkan data")
    {
        return session()->flash("success-status", $message);
    }

    public function successUpdate($message = "Berhasil memperbarui data")
    {
        return session()->flash("success-status", $message);
    }

    public function successDestroy($message = "Berhasil menghapus data")
    {
        return session()->flash("success-status", $message);
    }

    public function errorStatus($message = "Error")
    {
        return session()->flash("error-status", $message);
    }
}
