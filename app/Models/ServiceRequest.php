<?php

namespace App\Models;

use App\Enums\ServiceRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_number',
        'service_type_id',
        'submitter_id',
        'applicant_name',
        'applicant_nik',
        'family_card_number',
        'address',
        'village_id',
        'phone',
        'submitted_at',
        'officer_id',
        'work_unit_id',
        'status',
        'is_priority',
        'verification_result',
        'officer_notes',
        'assessment_notes',
        'service_result',
        'rejection_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceRequestStatus::class,
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
            'is_priority' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceRequest $model): void {
            if (blank($model->request_number)) {
                $serviceType = $model->serviceType ?? ServiceType::find($model->service_type_id);
                $prefix = match ($serviceType?->code) {
                    'DTSEN' => 'DTSEN',
                    'PBI' => 'PBI',
                    'REHSOS' => 'RHS',
                    default => strtoupper($serviceType?->code ?? 'REQ'),
                };
                $model->request_number = NumberSequence::generateNext($prefix, $model->submitted_at ?? now());
            }
            if (blank($model->submitted_at)) {
                $model->submitted_at = now();
            }
        });

        static::created(function (ServiceRequest $model): void {
            $toStatus = $model->status instanceof \BackedEnum ? $model->status->value : (string) $model->status;
            $model->statusHistories()->create([
                'from_status' => null,
                'to_status' => $toStatus,
                'notes' => 'Pengajuan layanan baru dibuat',
                'user_id' => auth()->id() ?? $model->submitter_id,
            ]);
        });

        static::updating(function (ServiceRequest $model): void {
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;
                $model->statusHistories()->create([
                    'from_status' => $oldStatus instanceof \BackedEnum ? $oldStatus->value : (string) $oldStatus,
                    'to_status' => $newStatus instanceof \BackedEnum ? $newStatus->value : (string) $newStatus,
                    'notes' => $model->officer_notes ?: 'Perubahan status pengajuan layanan',
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    public function transitionTo(ServiceRequestStatus|string $newStatus, ?string $notes = null): void
    {
        $statusValue = $newStatus instanceof ServiceRequestStatus ? $newStatus : ServiceRequestStatus::from((string) $newStatus);
        $this->status = $statusValue;
        if ($notes) {
            $this->officer_notes = $notes;
        }
        $this->save();
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ServiceRequestDocument::class);
    }

    public function dtsenCertificate(): HasOne
    {
        return $this->hasOne(DtsenCertificate::class);
    }

    public function pbiReactivation(): HasOne
    {
        return $this->hasOne(PbiReactivation::class);
    }

    public function rehabilitationCase(): HasOne
    {
        return $this->hasOne(RehabilitationCase::class);
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
