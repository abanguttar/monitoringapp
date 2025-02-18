<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsErrorsMessagessEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (DB::table('errors')->count() !== 0) {
            session()->flash("error-status", "Hapus data gagal import terlebih dahulu");
            return redirect()->back()->withInput();
        }

        return $next($request);
    }
}
