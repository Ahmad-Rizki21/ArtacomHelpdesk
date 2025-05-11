<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}")
            ->logOnlyDirty();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function actions()
    {
        return $this->hasMany(TicketAction::class);
    }

    /**
     * Menghitung skor user berdasarkan ticket yang dibuat dan tindakan yang dilakukan
     *
     * @return int
     */
    public function calculateScore(): int
    {
        $score = 0;

        // Skor dari ticket yang dibuat
        $tickets = $this->tickets;
        foreach ($tickets as $ticket) {
            // Tambah 10 poin untuk setiap ticket yang dibuat
            $score += 10;

            // Jika ticket sudah ditutup
            if ($ticket->status === 'CLOSED') {
                // Tambah 20 poin jika ticket memenuhi SLA
                if ($ticket->isMeetingSlaTarget()) {
                    $score += 20;
                } else {
                    // Kurangi 5 poin jika ticket melebihi SLA
                    $score -= 5;
                }
            }
        }

        // Skor dari tindakan yang dilakukan
        $actions = $this->actions;
        foreach ($actions as $action) {
            // Skip tindakan otomatis seperti "Assignment"
            if ($action->action_type === 'Assignment') {
                continue;
            }

            // Tambah 5 poin untuk setiap tindakan
            $score += 5;

            // Bonus 10 poin untuk tindakan "Completed"
            if ($action->action_type === 'Completed') {
                $score += 10;
            }
        }

        // Pastikan skor tidak negatif
        return max(0, $score);
    }

    /**
     * Memperbarui skor user di database
     *
     * @return void
     */
    public function updateScore(): void
    {
        $this->score = $this->calculateScore();
        $this->save();
    }
}