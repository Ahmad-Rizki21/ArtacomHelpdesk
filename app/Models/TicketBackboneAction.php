<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketBackboneAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_backbone_id',
        'user_id',
        'action_type',
        'description',
        'status'
    ];

    public function ticketBackbone()
    {
        return $this->belongsTo(TicketBackbone::class, 'ticket_backbone_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}