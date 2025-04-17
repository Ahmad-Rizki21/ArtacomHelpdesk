<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackboneCid extends Model
{
    use HasFactory;

    protected $table = 'backbone_cids';

    protected $fillable = [
        'cid',
        'lokasi',
        'jenis_isp', // Pastikan ini sesuai dengan database
    ];

    public function ticketBackbones(): HasMany
    {
        // return $this->hasMany(TicketBackbone::class, 'cid', 'cid');
        return $this->hasMany(TicketBackbone::class, 'cid', 'id');
    }
}
