<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'period',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'last_number' => 'integer',
        ];
    }

    /**
     * Generate unique ticket/reference number with row locking to prevent race conditions.
     * Format: {prefix}-{period}-{padded_number} (e.g., DTSEN-202610-00001)
     */
    public static function generateNext(string $prefix, ?DateTimeInterface $date = null, int $padLength = 5): string
    {
        $period = $date ? Carbon::instance($date)->format('Ym') : now()->format('Ym');

        return DB::transaction(function () use ($prefix, $period, $padLength): string {
            $sequence = static::query()
                ->where('prefix', $prefix)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = static::create([
                    'prefix' => $prefix,
                    'period' => $period,
                    'last_number' => 0,
                ]);
                // Re-lock the newly created sequence
                $sequence = static::query()
                    ->where('id', $sequence->id)
                    ->lockForUpdate()
                    ->first();
            }

            $sequence->increment('last_number');

            return sprintf('%s-%s-%s', $prefix, $period, str_pad((string) $sequence->last_number, $padLength, '0', STR_PAD_LEFT));
        });
    }
}
