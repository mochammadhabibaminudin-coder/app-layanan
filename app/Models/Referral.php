<?php

namespace App\Models;

use App\Enums\ReferralStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'referral_number',
        'rehabilitation_case_id',
        'assessment_id',
        'referral_institution_id',
        'officer_id',
        'referral_date',
        'status',
        'service_result',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReferralStatus::class,
            'referral_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Referral $model): void {
            if (blank($model->referral_number)) {
                $model->referral_number = NumberSequence::generateNext('RJK', $model->referral_date ?? now());
            }
            if (blank($model->referral_date)) {
                $model->referral_date = now()->toDateString();
            }
        });

        static::created(function (Referral $model): void {
            $toStatus = $model->status instanceof \BackedEnum ? $model->status->value : (string) $model->status;
            $model->statusHistories()->create([
                'from_status' => null,
                'to_status' => $toStatus,
                'notes' => 'Rujukan baru dibuat',
                'user_id' => auth()->id() ?? $model->officer_id,
            ]);
        });

        static::updating(function (Referral $model): void {
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;
                $model->statusHistories()->create([
                    'from_status' => $oldStatus instanceof \BackedEnum ? $oldStatus->value : (string) $oldStatus,
                    'to_status' => $newStatus instanceof \BackedEnum ? $newStatus->value : (string) $newStatus,
                    'notes' => $model->service_result ?: 'Perubahan status rujukan',
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    public function transitionTo(ReferralStatus|string $newStatus, ?string $notes = null): void
    {
        $statusValue = $newStatus instanceof ReferralStatus ? $newStatus : ReferralStatus::from((string) $newStatus);
        $this->status = $statusValue;
        if ($notes) {
            $this->service_result = $notes;
        }
        $this->save();
    }

    public function rehabilitationCase(): BelongsTo
    {
        return $this->belongsTo(RehabilitationCase::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(ReferralInstitution::class, 'referral_institution_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function monitoringRecords(): HasMany
    {
        return $this->hasMany(MonitoringRecord::class);
    }

    public function statusHistories(): MorphMany
    {
        return $this->morphMany(StatusHistory::class, 'statusable')->orderBy('created_at', 'desc');
    }
}
