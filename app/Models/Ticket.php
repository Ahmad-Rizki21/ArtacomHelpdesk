<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Konstanta untuk target uptime SLA
     */
    const TARGET_UPTIME_PERCENTAGE = 99.5;

    const TICKET_PREFIX = 'TFTTH-';

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
        // Kolom baru untuk timer
        'open_time_seconds',
        'pending_time_seconds',
        'last_status_change_at',
    ];

    protected $casts = [
        'report_date' => 'datetime',
        'closed_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_status_change_at' => 'datetime',
    ];

    /**
     * Menambahkan atribut yang dapat diakses sebagai properti
     */
    protected $appends = [
        'progress_percentage',
        'resolution_time',
        'sla_status',
        'uptime_percentage',
        'allowed_downtime'
    ];
    
    /**
     * Update timer berdasarkan perubahan status
     * Dipanggil saat status tiket berubah
     */
    public function updateTimer(): void
    {
        $now = now();
        $oldStatus = $this->getOriginal('status');
        $newStatus = $this->status;
        
        // Jika tidak ada perubahan status, return
        if ($oldStatus === $newStatus) {
            return;
        }
        
        // Jika ini pertama kali tiket dibuat, set last_status_change_at
        if (!$this->last_status_change_at) {
            $this->last_status_change_at = $now;
            return;
        }
        
        // Hitung durasi sejak terakhir status berubah
        $durationInSeconds = $now->diffInSeconds($this->last_status_change_at);
        
        // Update timer berdasarkan status sebelumnya
        if ($oldStatus === 'OPEN') {
            $this->open_time_seconds = ($this->open_time_seconds ?? 0) + $durationInSeconds;
        } elseif ($oldStatus === 'PENDING') {
            $this->pending_time_seconds = ($this->pending_time_seconds ?? 0) + $durationInSeconds;
        }
        
        // Update last_status_change_at
        $this->last_status_change_at = $now;
        
        // Pastikan nilai tidak negatif
        if ($this->open_time_seconds < 0) {
            $this->open_time_seconds = abs($this->open_time_seconds);
        }
        
        if ($this->pending_time_seconds < 0) {
            $this->pending_time_seconds = abs($this->pending_time_seconds);
        }
    }
    
    /**
     * Perbarui perhitungan timer saat status tiket berubah menjadi CLOSED
     */
    public function updateTimerOnClose(): void
    {
        $now = now();
        
        // Jika last_status_change_at tidak ada, gunakan report_date
        if (!$this->last_status_change_at && $this->report_date) {
            $this->last_status_change_at = $this->report_date;
        }
        
        // Hitung durasi dari perubahan status terakhir hingga sekarang
        if ($this->last_status_change_at) {
            $lastStatusChange = Carbon::parse($this->last_status_change_at);
            $durationInSeconds = $now->diffInSeconds($lastStatusChange);
            
            // Update timer berdasarkan status sebelumnya
            if ($this->getOriginal('status') === 'OPEN') {
                $this->open_time_seconds = ($this->open_time_seconds ?? 0) + $durationInSeconds;
            } elseif ($this->getOriginal('status') === 'PENDING') {
                $this->pending_time_seconds = ($this->pending_time_seconds ?? 0) + $durationInSeconds;
            }
            
            // Update last_status_change_at
            $this->last_status_change_at = $now;
        }
        
        // Pastikan nilai open_time_seconds dan pending_time_seconds tidak negatif
        if ($this->open_time_seconds < 0) {
            $this->open_time_seconds = abs($this->open_time_seconds);
        }
        
        if ($this->pending_time_seconds < 0) {
            $this->pending_time_seconds = abs($this->pending_time_seconds);
        }
    }

    protected static function boot()
    {
        parent::boot();
        
        // Observer untuk update timer saat status berubah
        static::updating(function ($model) {
            if ($model->isDirty('status')) {
                // Jika status berubah menjadi CLOSED, jalankan updateTimerOnClose
                if ($model->status === 'CLOSED' && $model->getOriginal('status') !== 'CLOSED') {
                    $model->updateTimerOnClose();
                } else {
                    $model->updateTimer();
                }
            }
        });
        
        //Nomer ticket acack
    //     static::creating(function ($model) {
    //         if (!$model->ticket_number) {
    //             $model->ticket_number = 'TFTTH-' . strtoupper(uniqid());
    //         }
            
    //         if (!$model->created_by) {
    //             $model->created_by = Auth::id();
    //         }
            
    //         // Set last_status_change_at saat tiket dibuat
    //         $model->last_status_change_at = now();
    //     });
    // }
        static::creating(function ($model) {
            // Generate sequential ticket number
            if (!$model->ticket_number) {
                $model->ticket_number = self::generateSequentialTicketNumber();
            }
            
            if (!$model->created_by) {
                $model->created_by = Auth::id();
            }
            
            // Set last_status_change_at saat tiket dibuat
            $model->last_status_change_at = now();
        });
    }


    public static function generateSequentialTicketNumber(): string
    {
        // Dapatkan tiket terakhir
        $lastTicket = self::orderBy('id', 'desc')->first();
        
        // Jika tidak ada tiket sebelumnya, mulai dari 1
        if (!$lastTicket) {
            return self::TICKET_PREFIX . '0001';
        }
        
        // Cek apakah tiket terakhir sudah menggunakan format baru
        $lastNumber = null;
        
        if (preg_match('/' . self::TICKET_PREFIX . '(\d+)$/', $lastTicket->ticket_number, $matches)) {
            // Jika sudah format baru, ambil nomor dan tambahkan 1
            $lastNumber = (int) $matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum format baru, mulai dari 1
            $newNumber = 1;
        }
        
        // Format nomor dengan padding 4 digit
        return self::TICKET_PREFIX . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mendapatkan timer saat ini untuk status tiket yang sedang aktif
     * 
     * @return array
     */
    public function getCurrentTimer(): array
    {
        $now = now();
        $status = $this->status;
        
        // Jika tiket sudah CLOSED, gunakan nilai dari database
        if ($status === 'CLOSED') {
            // Pastikan nilai tidak negatif
            $openSeconds = abs($this->open_time_seconds ?? 0);
            $pendingSeconds = abs($this->pending_time_seconds ?? 0);
            
            // Jika tidak ada nilai valid, coba hitung dari report_date dan closed_date
            if (($openSeconds == 0 && $pendingSeconds == 0) && $this->report_date && $this->closed_date) {
                $reportDate = Carbon::parse($this->report_date);
                $closedDate = Carbon::parse($this->closed_date);
                $totalDiffSeconds = $closedDate->diffInSeconds($reportDate);
                
                // Asumsikan semua waktu adalah open_time jika kita tidak tahu perincian waktu pending
                $openSeconds = $totalDiffSeconds;
            }
            
            return [
                'open' => [
                    'seconds' => $openSeconds,
                    'formatted' => $this->formatDuration($openSeconds),
                ],
                'pending' => [
                    'seconds' => $pendingSeconds,
                    'formatted' => $this->formatDuration($pendingSeconds),
                ],
                'total' => [
                    'seconds' => $openSeconds + $pendingSeconds,
                    'formatted' => $this->formatDuration($openSeconds + $pendingSeconds),
                ],
            ];
        }
        
        // Base timer dari database
        $openTimeSeconds = $this->open_time_seconds ?? 0;
        $pendingTimeSeconds = $this->pending_time_seconds ?? 0;
        
        // Jika tiket sedang aktif, tambahkan waktu real-time
        if ($this->last_status_change_at) {
            $currentDuration = $now->diffInSeconds($this->last_status_change_at);
            
            if ($status === 'OPEN') {
                $openTimeSeconds += $currentDuration;
            } elseif ($status === 'PENDING') {
                $pendingTimeSeconds += $currentDuration;
            }
        }
        
        // Format waktu untuk display
        return [
            'open' => [
                'seconds' => $openTimeSeconds,
                'formatted' => $this->formatDuration($openTimeSeconds),
            ],
            'pending' => [
                'seconds' => $pendingTimeSeconds,
                'formatted' => $this->formatDuration($pendingTimeSeconds),
            ],
            'total' => [
                'seconds' => $openTimeSeconds + $pendingTimeSeconds,
                'formatted' => $this->formatDuration($openTimeSeconds + $pendingTimeSeconds),
            ],
        ];
    }

    /**
     * Format durasi dari detik ke format jam:menit:detik
     * 
     * @param int $seconds
     * @return string
     */
    protected function formatDuration(int $seconds): string
    {
        // Pastikan nilai selalu positif
        $seconds = abs($seconds);
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    /**
     * Menghitung total waktu dalam periode bulan tertentu (dalam menit)
     * 
     * @param \Carbon\Carbon|null $date
     * @return int
     */
    public static function calculateTotalTimeInMonth(?Carbon $date = null): int
    {
        $date = $date ?? Carbon::now();
        $daysInMonth = $date->daysInMonth;
        
        // Total menit dalam bulan = jumlah hari × 24 jam × 60 menit
        return $daysInMonth * 24 * 60;
    }

    /**
     * Menghitung downtime maksimum yang diizinkan untuk bulan tertentu (dalam menit)
     * 
     * @param \Carbon\Carbon|null $date
     * @return float
     */
    public static function calculateAllowedDowntimeInMonth(?Carbon $date = null): float
    {
        $totalMinutesInMonth = self::calculateTotalTimeInMonth($date);
        
        // Downtime maksimum = total menit × (100% - target uptime)
        return $totalMinutesInMonth * ((100 - self::TARGET_UPTIME_PERCENTAGE) / 100);
    }

    /**
     * Menghitung waktu resolusi tiket dalam menit
     * 
     * @return int|null
     */
    public function calculateResolutionTime(): ?int
    {
        // Jika tiket belum dilaporkan atau belum ditutup, return null
        if (!$this->report_date || !$this->closed_date) {
            return null;
        }
        
        // Hitung total durasi dari tiket dilaporkan hingga ditutup (dalam menit)
        $totalMinutes = $this->closed_date->diffInMinutes($this->report_date);
        
        // Jika ada waktu pending, kurangi dari total durasi
        if ($this->pending_clock && $this->pending_clock > 0) {
            $totalMinutes -= (int) $this->pending_clock;
        }
        
        // Pastikan tidak ada nilai negatif
        return max(0, $totalMinutes);
    }
    
    /**
     * PENTING: Menambahkan metode calculateUptimePercentage() yang dipanggil oleh export
     * 
     * @return float|null
     */
    public function calculateUptimePercentage(): ?float
    {
        return $this->getUptimePercentageAttribute();
    }
    
    /**
     * Mendapatkan uptime percentage berdasarkan waktu resolusi
     * 
     * @return float|null
     */
    public function getUptimePercentageAttribute(): ?float
    {
        $resolutionTime = $this->calculateResolutionTime();
        
        if ($resolutionTime === null) {
            return null;
        }
        
        // Ambil bulan dari report_date
        $reportMonth = $this->report_date->copy();
        
        // Hitung total menit dalam bulan tersebut
        $totalMinutesInMonth = self::calculateTotalTimeInMonth($reportMonth);
        
        // Hitung persentase uptime
        // Uptime = 100% - (Waktu Resolusi / Total Waktu dalam Bulan × 100%)
        return 100 - (($resolutionTime / $totalMinutesInMonth) * 100);
    }
    
    /**
     * Memeriksa apakah tiket memenuhi SLA uptime 99,5%
     * 
     * @return bool|null
     */
    public function isMeetingSlaTarget(): ?bool
    {
        $uptimePercentage = $this->uptime_percentage;
        
        if ($uptimePercentage === null) {
            return null;
        }
        
        return $uptimePercentage >= self::TARGET_UPTIME_PERCENTAGE;
    }
    
    /**
     * Mendapatkan status SLA berdasarkan persentase uptime
     * 
     * @return string
     */
    public function getSlaStatusAttribute(): string
    {
        // Jika tiket belum ditutup, masih dalam proses
        if (!$this->closed_date) {
            return 'Dalam Proses';
        }
        
        $isMeetingSla = $this->isMeetingSlaTarget();
        
        // Jika tidak dapat menghitung SLA
        if ($isMeetingSla === null) {
            return 'Tidak Dapat Dihitung';
        }
        
        return $isMeetingSla ? 'Memenuhi SLA' : 'Melebihi SLA';
    }
    
    /**
     * Mendapatkan waktu resolusi dalam format yang readable
     * 
     * @return string
     */
    public function getResolutionTimeAttribute(): string
    {
        $minutes = $this->calculateResolutionTime();
        
        if ($minutes === null) {
            return 'Belum Selesai';
        }
        
        $days = floor($minutes / 1440); // 1440 = 24 * 60 (menit dalam sehari)
        $minutes %= 1440;
        
        $hours = floor($minutes / 60);
        $minutes %= 60;
        
        $result = '';
        if ($days > 0) {
            $result .= $days . ' hari ';
        }
        
        return $result . sprintf('%02d:%02d', $hours, $minutes);
    }
    
    /**
     * Mendapatkan downtime maksimum yang diizinkan untuk bulan pelaporan tiket (dalam format readable)
     * 
     * @return string
     */
    public function getAllowedDowntimeAttribute(): string
    {
        if (!$this->report_date) {
            return 'Tidak Ada Tanggal Laporan';
        }
        
        $allowedDowntimeMinutes = self::calculateAllowedDowntimeInMonth($this->report_date);
        
        $hours = floor($allowedDowntimeMinutes / 60);
        $minutes = $allowedDowntimeMinutes % 60;
        
        return sprintf('%02d:%02d', $hours, round($minutes));
    }

    /**
     * Mendapatkan persentase kemajuan tiket
     * 
     * @return int
     */
    public function getProgressPercentageAttribute(): int  
    {  
        return match ($this->status) {  
            'OPEN' => 10,  
            'PENDING' => 50,  
            'CLOSED' => 100,  
            default => 0,  
        };  
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

    /**
     * Scope untuk filtering tiket
     */
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

    /**
     * Scope untuk mendapatkan tiket yang memenuhi SLA
     */
    public function scopeMeetingSla(Builder $query): Builder
    {
        return $query->whereNotNull('closed_date')
            ->whereNotNull('report_date')
            ->whereRaw('(
                TIMESTAMPDIFF(MINUTE, report_date, closed_date) - COALESCE(pending_clock, 0)
            ) <= ?', [
                self::calculateAllowedDowntimeInMonth(now())
            ]);
    }

    /**
     * Scope untuk mendapatkan tiket yang melebihi SLA
     */
    public function scopeExceedingSla(Builder $query): Builder
    {
        return $query->whereNotNull('closed_date')
            ->whereNotNull('report_date')
            ->whereRaw('(
                TIMESTAMPDIFF(MINUTE, report_date, closed_date) - COALESCE(pending_clock, 0)
            ) > ?', [
                self::calculateAllowedDowntimeInMonth(now())
            ]);
    }

    /**
     * Scope untuk mendapatkan tiket dalam periode bulan tertentu
     */
    public function scopeInMonth(Builder $query, ?Carbon $date = null): Builder
    {
        $date = $date ?? Carbon::now();
        
        return $query->whereYear('report_date', $date->year)
            ->whereMonth('report_date', $date->month);
    }

    /**
     * Scope untuk mendapatkan tiket FTTH
     */
    public function scopeFtth(Builder $query): Builder
    {
        return $query->where('service', 'FTTH');
    }

    /**
     * Mendapatkan ringkasan SLA untuk periode tertentu
     * 
     * @param Carbon|null $date
     * @return array
     */
    public static function getSlaStats(?Carbon $date = null): array
    {
        $date = $date ?? Carbon::now();
        
        // Mendapatkan semua tiket dalam bulan tersebut
        $tickets = self::inMonth($date)
            ->ftth()
            ->whereNotNull('closed_date')
            ->get();
        
        // Inisialisasi statistik
        $stats = [
            'total' => $tickets->count(),
            'meeting_sla' => 0,
            'exceeding_sla' => 0,
            'uptime_percentage' => 0,
            'allowed_downtime' => self::calculateAllowedDowntimeInMonth($date),
            'month_name' => $date->translatedFormat('F Y'),
        ];
        
        // Jika tidak ada tiket, kembalikan statistik kosong
        if ($stats['total'] === 0) {
            return $stats;
        }
        
        // Hitung statistik
        $totalUptimePercentage = 0;
        
        foreach ($tickets as $ticket) {
            $uptimePercentage = $ticket->uptime_percentage;
            
            if ($uptimePercentage !== null) {
                $totalUptimePercentage += $uptimePercentage;
                
                if ($uptimePercentage >= self::TARGET_UPTIME_PERCENTAGE) {
                    $stats['meeting_sla']++;
                } else {
                    $stats['exceeding_sla']++;
                }
            }
        }
        
        // Hitung rata-rata uptime
        $stats['uptime_percentage'] = $stats['total'] > 0 ? 
            $totalUptimePercentage / $stats['total'] : 0;
        
        // Persentase kepatuhan SLA
        $ticketsWithSla = $stats['meeting_sla'] + $stats['exceeding_sla'];
        $stats['compliance_percentage'] = $ticketsWithSla > 0 ? 
            ($stats['meeting_sla'] / $ticketsWithSla) * 100 : 0;
        
        return $stats;
    }
}