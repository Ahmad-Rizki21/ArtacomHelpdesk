<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SLA extends Model
{
    // Menentukan atribut yang dapat diisi secara massal
    protected $fillable = [
        'name',
        'response_time',
        'resolution_time',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}