<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'jadwal_name',
        'jam',
        'date',
        'price',
        'is_prakerja',
        'metode',
        'day',
        'trainer_id',
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
