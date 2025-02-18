<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\Transaction;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class PesertaImport implements ToCollection
{
    protected $mitra_id;
    protected $dp_id;
    protected $kelas_id;
    protected $result;

    public function __construct($mitra_id, $dp_id, $kelas_id)
    {
        $this->mitra_id = $mitra_id;
        $this->dp_id = $dp_id;
        $this->kelas_id = $kelas_id;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $pesertas = Peserta::pluck('email')->toArray();
        $vouchers = Transaction::pluck('voucher')->toArray();
        $invoices = Transaction::pluck('invoice')->toArray();
        $kelas = Kelas::findOrFail($this->kelas_id);
        $nama_kelas = $kelas->name;
        $user_id = Auth::user()->id;
        $data = [
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        // $kelas = Kelas::findOrFail('id', $this->kelas_id);
        $errors = [];
        unset($rows[0]);
        $totalSuccess = 0;


        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $message = [];
                $name = $row[1];
                $email = $row[2];
                $phone = $row[3];
                $voucher = $row[4];
                $invoice = $row[5];

                $is_peserta_exist = false;

                if ($name === null) {
                    break;
                }

                // Check is email is already exists?
                if (in_array($email, $pesertas)) {
                    $peserta = Peserta::where("email", $email)->first();
                    $is_peserta_exist = true;
                }

                // Check is voucher is already exists?
                if (in_array($voucher, $vouchers)) {
                    $message[] = "Voucher sudah ada";
                }

                // Check is invoice is already exists?
                if (in_array($invoice, $invoices)) {
                    $message[] = "Invoice sudah ada";
                }


                // If peserta already exist check is the peserta have a transaction before
                if ($is_peserta_exist) {
                    // Apakah user sudah melakukan transaksi?
                    $user_transactions = Transaction::where('peserta_id', $peserta->id)->pluck('kelas_id')->toArray();

                    if (count($user_transactions) > 0) {
                        foreach ($user_transactions as $ut) {
                            $user_kelas = Kelas::find($ut);
                            if (strtolower($nama_kelas) == strtolower($user_kelas->name)) {
                                $message[] = "Email sudah memiliki kelas ini";
                            }
                        }
                    }
                }



                $data['name'] = $name;
                $data['email'] = $email;
                $data['phone'] = $phone;

                if (count($message) > 0) {
                    $error = $data;
                    unset($error['mitra_id'], $error['digital_platform_id'], $error['user_create'], $error['user_update']);
                    $error['voucher'] = $voucher;
                    $error['invoice'] = $invoice;
                    $messages = implode(', ', $message);
                    $error['message'] = $messages;
                    $errors[] = $error;
                    /**
                     * Go to next line
                     */
                    continue;
                }


                /**
                 * Jika peserta belum pernah ada maka create baru
                 */
                if (!$is_peserta_exist) {
                    $peserta = Peserta::create($data);
                    $pesertas[] = $email;
                }

                $transaction_data = [
                    'mitra_id' => $this->mitra_id,
                    'digital_platform_id' => $this->dp_id,
                    'peserta_id' => $peserta->id,
                    'kelas_id' => $this->kelas_id,
                    'voucher' => $voucher,
                    'invoice' => $invoice,
                    'user_create' => $user_id,
                    'user_update' => $user_id
                ];

                Transaction::create($transaction_data);

                $vouchers[] = $voucher;
                $invoices[] = $invoice;
                $totalSuccess++;
            }

            DB::commit();

            $totalErrors = count($errors);
            // Insert error if have
            if ($totalErrors > 0) {
                DB::table('errors')->insert($errors);
            }

            $this->result = collect([
                'success' => $totalSuccess,
                'errors' => $totalErrors,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash("error-status", $th->getMessage());
            return redirect()->back()->withInput();
        }

    }



    public function getResult()
    {
        return $this->result;
    }
}
