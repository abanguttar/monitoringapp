<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission_id): Response
    {

        $user = Auth::user();
        $key = 'user_permission=' . $user->username;
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        $user_permissions = Cache::remember($key, Carbon::now()->addDays(1), function () use ($user) {
            return DB::table("user_permissions")->where('user_id', $user->id)->pluck('permission_id')->toArray();
        });

        if (!in_array($permission_id, $user_permissions)) {
            session()->flash("error-status", "Anda tidak memiliki akses membuka halaman ini");
            return redirect()->back();
        }

        return $next($request);

    }
}
