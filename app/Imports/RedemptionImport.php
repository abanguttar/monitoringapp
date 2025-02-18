<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class RedemptionImport implements ToCollection
{

    protected $result;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $redeem_codes = Transaction::pluck('redeem_code')->toArray();
        $errors = [];
        unset($rows[0]);
        $totalSuccess = 0;
        foreach ($rows as $row) {
            $voucher = $row[1];
            $redeem_code = $row[2];
            $redeem_at = $row[3];
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
            } else if ($transaction->redeem_code) {
                /**
                 * Is data already have redeem code?
                 */
                $message[] = "Redeem code terisi";
            }

            /**
             * Check is redeem code exists?
             */
            if (in_array($redeem_code, $redeem_codes)) {
                $message[] = "Redeem code sudah digunakan";
            }

            $data = [
                'redeem_code' => $redeem_code,
                'redeem_at' => $redeem_at,
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
            $redeem_codes[] = $redeem_code;
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
