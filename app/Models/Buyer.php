<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Buyer extends Authenticatable
{
    use HasFactory, Notifiable;

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
