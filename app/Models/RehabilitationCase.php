<?php

namespace App\Models;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RehabilitationCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'case_number',
        'client_id',
        'service_request_id',
        'complaint_id',
        'officer_id',
        'handling_type',
        'status',
        'handling_result',
        'received_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'handling_type' => HandlingType::class,
            'status' => RehabilitationCaseStatus::class,
            'received_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RehabilitationCase $model): void {
            if (blank($model->case_number)) {
                $model->case_number = NumberSequence::generateNext('RHS', $model->received_at ?? now());
            }
            if (blank($model->received_at)) {
                $model->received_at = now();
            }
        });

        static::created(function (RehabilitationCase $model): void {
            $toStatus = $model->status instanceof \BackedEnum ? $model->status->value : (string) $model->status;
            $model->statusHistories()->create([
                'from_status' => null,
                'to_status' => $toStatus,
                'notes' => 'Kasus rehabilitasi sosial baru dibuka',
                'user_id' => auth()->id() ?? $model->officer_id,
            ]);
        });

        static::updating(function (RehabilitationCase $model): void {
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;
                $model->statusHistories()->create([
                    'from_status' => $oldStatus instanceof \BackedEnum ? $oldStatus->value : (string) $oldStatus,
                    'to_status' => $newStatus instanceof \BackedEnum ? $newStatus->value : (string) $newStatus,
                    'notes' => $model->handling_result ?: 'Perubahan status kasus rehabilitasi',
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    public function transitionTo(RehabilitationCaseStatus|string $newStatus, ?string $notes = null): void
    {
        $statusValue = $newStatus instanceof RehabilitationCaseStatus ? $newStatus : RehabilitationCaseStatus::from((string) $newStatus);
        $this->status = $statusValue;
        if ($notes) {
            $this->handling_result = $notes;
        }
        $this->save();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function sourceServiceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function sourceComplaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function monitoringRecords(): HasMany
    {
        return $this->hasMany(MonitoringRecord::class);
    }

    public function statusHistories(): MorphMany
    {
        return $this->morphMany(StatusHistory::class, 'statusable')->orderBy('created_at', 'desc');
    }

    public function dispositions(): MorphMany
    {
        return $this->morphMany(Disposition::class, 'dispositionable')->orderBy('disposed_at', 'desc');
    }
}
