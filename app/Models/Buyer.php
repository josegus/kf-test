<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Buyer extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public static function refundPreferences()
    {
        return ['credit', 'cc'];
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function prefersCreditRefund(): bool
    {
        return $this->refund_pref === 'credit';
    }
}
