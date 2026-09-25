<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'complaint_number',
        'complaint_category_id',
        'reporter_id',
        'reporter_name',
        'reporter_phone',
        'location_detail',
        'village_id',
        'description',
        'reported_at',
        'officer_id',
        'status',
        'verification_result',
        'action_taken',
        'duplicate_of_id',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ComplaintStatus::class,
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Complaint $model): void {
            if (blank($model->complaint_number)) {
                $model->complaint_number = NumberSequence::generateNext('ADU', $model->reported_at ?? now());
            }
            if (blank($model->reported_at)) {
                $model->reported_at = now();
            }
        });

        static::created(function (Complaint $model): void {
            $toStatus = $model->status instanceof \BackedEnum ? $model->status->value : (string) $model->status;
            $model->statusHistories()->create([
                'from_status' => null,
                'to_status' => $toStatus,
                'notes' => 'Laporan pengaduan baru diterima',
                'user_id' => auth()->id() ?? $model->reporter_id,
            ]);
        });

        static::updating(function (Complaint $model): void {
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;
                $model->statusHistories()->create([
                    'from_status' => $oldStatus instanceof \BackedEnum ? $oldStatus->value : (string) $oldStatus,
                    'to_status' => $newStatus instanceof \BackedEnum ? $newStatus->value : (string) $newStatus,
                    'notes' => $model->verification_result ?: 'Perubahan status pengaduan',
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    public function transitionTo(ComplaintStatus|string $newStatus, ?string $notes = null): void
    {
        $statusValue = $newStatus instanceof ComplaintStatus ? $newStatus : ComplaintStatus::from((string) $newStatus);
        $this->status = $statusValue;
        if ($notes) {
            $this->verification_result = $notes;
        }
        $this->save();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function duplicateOf(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'duplicate_of_id');
    }

    public function duplicates(): HasMany
    {
        return $this->hasMany(Complaint::class, 'duplicate_of_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function rehabilitationCases(): HasMany
    {
        return $this->hasMany(RehabilitationCase::class);
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
