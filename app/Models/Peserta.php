<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'digital_platform_id',
        'name',
        'email',
        'phone',
        'user_create',
        'user_update',
    ];


    public function uc()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }


    public function uu()
    {
        return $this->belongsTo(User::class, 'user_create', 'id');
    }
}
