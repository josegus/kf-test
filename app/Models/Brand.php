<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Brand extends Model
{
    use HasFactory, Notifiable;

    public function coops()
    {
        return $this->hasMany(Coop::class);
    }

    // Tal vez no se usa
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
