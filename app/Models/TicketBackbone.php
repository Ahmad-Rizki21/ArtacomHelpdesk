<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\BackboneCID;

class TicketBackbone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_ticket',
        'cid',
        'jenis_isp',
        'lokasi_id',
        'extra_description',
        'action_description', // Add this line
        'status',
        'open_date',
        'pending_date',
        'closed_date',
        'created_by',
        'action_description', // Add action_description for resolutions
    ];

    protected $casts = [
        'open_date' => 'datetime',
        'pending_date' => 'datetime',
        'closed_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            $ticket->no_ticket = 'BackBone-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $ticket->open_date = now(); // Ensure open_date is set
        });

        static::creating(function ($ticket) {
            $ticket->jenis_isp = BackboneCID::where('id', $ticket->cid)->value('jenis_isp') ?? 'Tidak Diketahui';
        });

        static::updating(function ($ticket) {
            // Jika status diubah menjadi PENDING, atur pending_date otomatis
            if ($ticket->isDirty('status') && $ticket->status === 'PENDING' && !$ticket->pending_date) {
                $ticket->pending_date = now();
            }

            // Jika status diubah menjadi CLOSED, atur closed_date otomatis
            if ($ticket->isDirty('status') && $ticket->status === 'CLOSED' && !$ticket->closed_date) {
                $ticket->closed_date = now();
            }
        });
    }

    // Relationship to TicketBackboneAction
    public function actions(): HasMany
    {
        return $this->hasMany(TicketBackboneAction::class, 'ticket_backbone_id');
    }

    // Format Pending Date
    public function getPendingDateFormattedAttribute()
    {
        return $this->pending_date ? Carbon::parse($this->pending_date)->format('d/m/Y H:i') : 'Belum ada Pending';
    }
     
    public function getClosedDateFormattedAttribute()
    {
        return $this->closed_date ? Carbon::parse($this->closed_date)->format('d/m/Y H:i') : 'Belum ada Ticket Closed';
    }

    // Relasi ke BackboneCID (Pastikan 'cid' cocok dengan tipe data di BackboneCID)
    public function cidRelation()
    {
        return $this->belongsTo(BackboneCid::class, 'cid', 'id');
    }
    
    public function lokasiRelation()
    {
        return $this->belongsTo(BackboneCid::class, 'lokasi_id', 'id');
    }

    // Relasi ke model Lokasi
    public function lokasi()
    {
        return $this->belongsTo(BackboneCid::class, 'lokasi_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getExtraDescriptionAttribute($value)
    {
        return $value ?: 'Belum Ada Deskripsi Tambahan';
    }

    public static function lokasiList()
    {
        return BackboneCid::pluck('lokasi', 'id')->toArray();
    }
}