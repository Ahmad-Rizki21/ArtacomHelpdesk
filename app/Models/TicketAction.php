<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action_type',
        'description',
        'status'
    ];

    public function ticket()
{
    return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}