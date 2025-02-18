<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class CompletionImport implements ToCollection
{

    protected $result;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $errors = [];
        unset($rows[0]);
        $totalSuccess = 0;


        foreach ($rows as $row) {
            $voucher = $row[1];
            $finish_at = $row[2];
            $message = [];
            $transaction = Transaction::where('voucher', $voucher)->first();

            if (!$voucher) {
                continue;
            }

            /**
             * Check is voucher exist?
             */
            if (!$transaction) {
                $message[] = "Voucher tidak ditemukan";
            } else {


                if ($transaction->finish_at) {
                    /**
                     * Is data already have redeem code?
                     */
                    $message[] = "100% Pelatihan telah terisi";
                }

                /**
                 * Before update completion, check is already finish redemption?
                 */

                if (!$transaction->redeem_code) {
                    $message[] = "Redeem code belum terisi";
                }
            }


            $data = [
                'finish_at' => $finish_at,
            ];


            if (count($message) > 0) {
                $error = $data;
                $error['voucher'] = $voucher;
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
