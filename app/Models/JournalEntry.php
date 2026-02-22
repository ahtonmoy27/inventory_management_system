<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    protected $fillable = [
        'entry_number',
        'entry_date',
        'reference_type',
        'reference_id',
        'description',
        'total_debit',
        'total_credit',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public static function generateEntryNumber(): string
    {
        $lastEntry = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastEntry ? intval(substr($lastEntry->entry_number, 3)) + 1 : 1;
        return 'JE-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
