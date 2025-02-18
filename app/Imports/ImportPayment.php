<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportPayment implements ToCollection
{
    protected $tipe;
    public $result;
    public function __construct($tipe)
    {
        $this->tipe = $tipe;
    }


    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $errors = [];
        unset($rows[0]);

        $totalSuccess = 0;

        if ($this->tipe === 'Redeemtion') {

            foreach ($rows as $row) {
                $invoice = $row[1];
                $period = $row[2];
                // $invoice = $row[3];
                // $period = $row[4];
                $message = [];
                $transaction = Transaction::with('kelas')->where('invoice', $invoice)->first();

                if (!$invoice) {
                    continue;
                }

                /**
                 * Check is invoice exist?
                 */
                if (!$transaction) {
                    $message[] = "Invoice tidak ditemukan";
                } else {
                    if ($transaction->redeem_paid && $transaction->redeem_period) {
                        /**
                         * Is data already have redeem code?
                         */
                        $message[] = "Bayar Redeem sudah terisi";
                        $message[] = "Periode Redeem sudah terisi";
                    }

                    if (!$transaction->redeem_code) {
                        $message[] = "Redeem code belum terisi";
                    }

                    // if (!$transaction->finish_at) {
                    //     $message[] = "100% Pelatihan belum terisi";
                    // }


                    $data = [
                        'redeem_paid' => ($transaction->kelas->price * 30) / 100,
                        'redeem_period' => $period,
                    ];
                }

                if (count($message) > 0) {
                    $error = [
                        'invoice' => $invoice,
                        'redeem_period' => $period
                    ];
                    // $error['name'] = $name;
                    // $error['email'] = $email;
                    $messages = implode(', ', $message);
                    $error['message'] = $messages;
                    $errors[] = $error;
                    /**
                     * Go to next line
                     */
                    continue;
                }

                $transaction->update($data);
                $totalSuccess++;
            }

        } else {
            foreach ($rows as $row) {
                $invoice = $row[1];
                $period = $row[2];
                // $invoice = $row[3];
                // $period = $row[4];
                $message = [];
                $transaction = Transaction::with('kelas')->where('invoice', $invoice)->first();

                if (!$invoice) {
                    continue;
                }

                /**
                 * Check is invoice exist?
                 */
                if (!$transaction) {
                    $message[] = "Invoice tidak ditemukan";
                } else {
                    if ($transaction->finish_paid && $transaction->finish_period) {
                        /**
                         * Is data already have redeem code?
                         */
                        $message[] = "Bayar Completion sudah terisi";
                        $message[] = "Periode Completion sudah terisi";
                    }

                    if (!$transaction->redeem_code) {
                        $message[] = "Redeem code belum terisi";
                    }

                    if (!$transaction->finish_at) {
                        $message[] = "100% Pelatihan belum terisi";
                    }

                    if (!$transaction->redeem_paid) {
                        $message[] = "Bayar Redeem belum terisi";
                    }

                    if (!$transaction->redeem_period) {
                        $message[] = "Periode Redeem belum terisi";
                    }


                    $data = [
                        'finish_paid' => ($transaction->kelas->price * 70) / 100,
                        'finish_period' => $period,
                    ];
                }


                if (count($message) > 0) {
                    $error = [
                        'invoice' => $invoice,
                        'finish_period' => $period,
                    ];
                    // $error['name'] = $name;
                    // $error['email'] = $email;
                    $messages = implode(', ', $message);
                    $error['message'] = $messages;
                    $errors[] = $error;
                    /**
                     * Go to next line
                     */
                    continue;
                }

                $transaction->update($data);
                $totalSuccess++;
            }
        }


        $totalErrors = count($errors);
        // Insert error if have
        if ($totalErrors > 0) {
            DB::table('errors')->insert($errors);
        }

        $this->result = collect([
            'success' => $totalSuccess,
            'errors' => $totalErrors,
        ]);

    }

    public function getResult()
    {
        return $this->result;
    }

}
