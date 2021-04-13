<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'amount' => 'float',
        'is_canceled' => 'bool',
        'is_pending' => 'bool',
    ];

    public static function sources()
    {
        return [
            'Check',
            'CreditCard',
            'KickfurtherCredits',
            'KickfurtherFunds',
            'Wire',
        ];
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function cancel()
    {
        $this->update(['is_canceled' => true]);
    }
}
