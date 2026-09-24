<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehabilitation_case_id',
        'officer_id',
        'assessment_date',
        'result',
        'service_needs',
        'recommendation',
        'needs_referral',
    ];

    protected function casts(): array
    {
        return [
            'needs_referral' => 'boolean',
            'assessment_date' => 'date',
        ];
    }

    public function rehabilitationCase(): BelongsTo
    {
        return $this->belongsTo(RehabilitationCase::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
}
