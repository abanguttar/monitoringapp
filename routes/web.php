<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\KomisiMitraController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DigitalPlatformController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('AplikasiMonitoring/login');
});

Route::prefix("AplikasiMonitoring")->group(function () {

    Route::get('/dashboard', function () {
        $title = 'Dashboard';
        $navbar = Str::slug(strtolower($title));
        // dd($title);
        return view('dashboard', compact('title', 'navbar'));
    })->middleware(['auth', 'verified'])->name('dashboard', 'navbar');

    Route::middleware('auth')->group(function () {
        Route::prefix('list-mitra')->controller(MitraController::class)->group(function () {
            Route::get('', 'index')->name('list-mitra')->middleware('prms:18');
            Route::get('/create', 'create')->middleware('prms:19');
            Route::post('/create', 'store')->middleware('prms:19');
            Route::get('/{mitra}/edit', 'edit')->middleware('prms:20');
            Route::put('/{mitra}/edit', 'update')->middleware('prms:20');
            Route::delete('/{mitra}/delete', 'destroy')->middleware('prms:21');
        });

        Route::prefix('list-digital-platform')->controller(DigitalPlatformController::class)->group(function () {
            Route::get('', 'index')->name('list-dp')->middleware('prms:22');
            Route::get('/create', 'create')->middleware('prms:23');
            Route::post('/create', 'store')->middleware('prms:23');
            Route::get('/{digitalPlatform}/edit', 'edit')->middleware('prms:24');
            Route::put('/{digitalPlatform}/edit', 'update')->middleware('prms:24');
            Route::delete('/{digitalPlatform}/delete', 'destroy')->middleware('prms:25');
        });

        Route::prefix('list-data-kelas')->controller(KelasController::class)->group(function () {
            Route::get('', 'index')->name('list-kelas')->middleware('prms:26');
            Route::get('/create', 'create')->middleware('prms:27');
            Route::post('/create', 'store')->middleware('prms:27');
            Route::get('/{kelas}/edit', 'edit')->middleware('prms:28');
            Route::put('/{kelas}/edit', 'update')->middleware('prms:28');
            Route::delete('/{kelas}/delete', 'destroy')->middleware('prms:29');
        });

        Route::prefix('master-data-peserta')->controller(PesertaController::class)->group(function () {
            Route::get('', 'index')->name('master-data-peserta')->middleware('prms:1');
            Route::get('/create', 'create')->middleware('prms:2');
            Route::post('/create', 'store')->middleware('prms:2');
            Route::get('/{peserta}/edit', 'show')->middleware('prms:3');
            Route::put('/{peserta}/edit', 'update')->middleware('prms:3');
            Route::get('/{peserta}/fetch', 'fetch')->middleware('prms:2');
        });

        Route::prefix('list-peserta')->controller(TransactionController::class)->group(function () {
            Route::get('', 'index')->name('list-peserta')->middleware('prms:4');
            Route::get('/marketing', 'marketingIndex')->name('list-peserta-marketing')->middleware('prms:35');
            Route::get('/create', 'create')->middleware('prms:5');
            Route::post('/create', 'store')->middleware('prms:5');
            Route::get('/{transaction}/edit', 'edit')->middleware('prms:6');
            Route::put('/{transaction}/edit', 'update')->middleware('prms:6');
            Route::get('/{transaction}/redeem-complete', 'redeemCompleteView')->middleware('prms:7');
            Route::put('/{transaction}/redeem-complete', 'redeemCompleteUpdate')->middleware('prms:7');
            Route::get('/import', 'importPembelian')->middleware('prms:8');
            Route::post('/import', 'storeImportPembelian')->middleware('isErrorsEmpty')->middleware('prms:8');
            Route::get('/{tipe}/import', 'import')->middleware('prms:9');
            Route::post('/{tipe}/import', 'storeImport')->middleware('isErrorsEmpty')->middleware('prms:9');
        });

        Route::prefix('list-pembayaran')->controller(TransactionController::class)->group(function () {
            Route::get('', 'paymentIndex')->name('list-pembayaran')->middleware('prms:11');
            Route::get('/{transaction}/payment', 'payment')->middleware('prms:14');
            Route::put('/{transaction}/payment', 'paymentUpdate')->middleware('prms:14');
            Route::get('/{transaction}/refund', 'refund')->middleware('prms:15');
            Route::put('/{transaction}/refund', 'refundUpdate')->middleware('prms:15');
            Route::get('/{tipe}/import', 'importPayment')->middleware('prms:12');
            Route::post('/{tipe}/import', 'storeImportPayment')->middleware('isErrorsEmpty')->middleware('prms:12');
        });



        Route::prefix('list-komisi-mitra')->controller(KomisiMitraController::class)->group(function () {
            Route::get('', 'index')->name('list-komisi-mitra')->middleware('prms:16');
            Route::post('/update', 'update')->middleware('prms:17');
        });

        Route::get('/errors/delete', function () {
            DB::table('errors')->truncate();
            return redirect()->back();
        });

        Route::prefix('list-user')->controller(UserController::class)->group(function () {
            Route::get('', 'index')->name('list-user');
            Route::get('/create', 'create');
            Route::post('/create', 'store');
            Route::get('/{admin}/edit', 'edit');
            Route::put('/{admin}/edit', 'update');
            Route::get('/{admin}/permission', 'permission');
            Route::put('/{admin}/permission', 'permissionUpdate');
        });

        Route::get('export', [TransactionController::class, 'export']); //Export
        Route::get('export-komisi', [KomisiMitraController::class, 'export'])->middleware('prms:17'); //Export
        Route::get('export-marketing', [TransactionController::class, 'exportMarketing']); //Export
        Route::get('export/errors/import/{tipe}', [TransactionController::class, 'exportErrors']); //Export




        Route::prefix('master-data-trainer')->controller(TrainerController::class)->group(function () {
            Route::get('', 'index')->name('master-data-trainer')->middleware('prms:22');
            Route::get('/create', 'create')->middleware('prms:23');
            Route::post('/create', 'store')->middleware('prms:23');
            Route::get('/{trainer}/edit', 'edit')->middleware('prms:24');
            Route::put('/{trainer}/edit', 'update')->middleware('prms:24');
            Route::delete('/{trainer}/delete', 'destroy')->middleware('prms:25');
        });

        Route::prefix('list-pembayaran-trainer')->controller(TrainerController::class)->group(function () {
            Route::get('', 'paymentIndex')->name('list-pembayaran-trainer')->middleware('prms:22');
        });

        Route::prefix('grafik-transaksi')->controller(GrafikController::class)->group(function () {
            Route::get('', 'index')->middleware('prms:36');
            Route::get('/pembelian-penyelesaian', 'pembelianPenyelesaian');
            Route::get('/penjualan-kelas', 'penjualanKelas');
            Route::get('/penjualan-mitra', 'penjualanMitra');
        });

        Route::prefix('komisi-trainer')->controller(TrainerController::class)->group(function () {
            Route::get('kelas-jadwal', 'indexKelasJadwal')->middleware('prms:22');
            Route::get('kelas-jadwal/{id}/edit', 'editKelasJadwal')->middleware('prms:22');
            Route::post('kelas-jadwal/{id}/edit', 'updateKelasJadwal')->middleware('prms:22');
            Route::get('kelas-jadwal/{id}/payment', 'editPaymentKelasJadwal')->middleware('prms:22');
            Route::post('kelas-jadwal/{id}/payment', 'updatePaymentKelasJadwal')->middleware('prms:22');
            Route::get('list-komisi', 'indexKomisiTrainer')->middleware('prms:22');
        });

        // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});



require __DIR__ . '/auth.php';

Route::get('/register', function () {
    return redirect('/AplikasiMonitoring/login');
});
