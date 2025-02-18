<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'mitra_id',
        'digital_platform_id',
        'peserta_id',
        'kelas_id',
        'voucher',
        'invoice',
        'redeem_code',
        'redeem_at',
        'finish_at',
        'redeem_period',
        'redeem_paid',
        'redeem_refund',
        'redeem_note',
        'finish_period',
        'finish_paid',
        'finish_refund',
        'finish_note',
        'user_create',
        'user_update',
    ] ;


    public function uc()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }

    public function uu()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }


    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id');
    }

    public function dp()
    {
        return $this->belongsTo(DigitalPlatform::class, 'digital_platform_id', 'id');
    }

}
