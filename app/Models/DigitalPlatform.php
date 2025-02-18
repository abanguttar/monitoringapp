<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalPlatform extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
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
