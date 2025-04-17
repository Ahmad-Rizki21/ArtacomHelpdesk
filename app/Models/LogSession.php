<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSession extends Model
{
    protected $table = 'sessions'; // Nama tabel
    public $timestamps = false; // Karena tabel sessions tidak memiliki timestamps

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];
}
