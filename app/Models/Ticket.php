<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'service',
        'ticket_number',
        'customer_id',
        'report_date',
        'status',
        'pending_clock',
        'closed_date',
        'problem_summary',
        'extra_description',
        'action_description',
        'title',
        'description',
        'sla_id',
        'evidance_path',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'report_date' => 'datetime',
        'closed_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->ticket_number) {
                $model->ticket_number = 'TFTTH-' . strtoupper(uniqid());
            }
        });

        static::creating(function ($model) {
            if (!$model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function sla(): BelongsTo
    {
        return $this->belongsTo(Sla::class, 'sla_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function actions()
    {
        return $this->hasMany(\App\Models\TicketAction::class, 'ticket_id')->orderByDesc('created_at');
    }

    public function getProgressPercentageAttribute(): int  
    {  
        return match ($this->status) {  
            'OPEN' => 10,  
            'PENDING' => 50,  
            'CLOSED' => 100,  
            default => 0,  
        };  
    }  

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when(data_get($filters, 'created_at_period.year'), function ($q, $year) {
                $q->whereYear('created_at', $year);
            })
            ->when(data_get($filters, 'created_at_period.month'), function ($q, $month) {
                $q->whereMonth('created_at', $month);
            })
            ->when(data_get($filters, 'created_at_period.date'), function ($q, $date) {
                $q->whereDay('created_at', $date);
            })
            ->when(data_get($filters, 'status.value'), function ($q, $status) {
                $q->where('status', $status);
            })
            ->when(data_get($filters, 'problem_summary.value'), function ($q, $problemType) {
                $q->where('problem_summary', $problemType);
            });
    }
}