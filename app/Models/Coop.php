<?php

namespace App\Models;

use Carbon\Carbon;
use App\Events\CoopCanceled;
use App\Events\CoopCreating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coop extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'expiration_date' => 'date'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => CoopCreating::class,
    ];

    public function scopeInDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeToBeCancelToday($query)
    {
        return $query
            ->where('status', 'draft')
            // TODO: se peude usar hasBeenFullyFunded
            ->whereDate('expiration_date', '<=', today());
    }

    public function scopeExpiresAt($query, Carbon $expirationDate)
    {
        return $query
            ->where('status', 'draft')
            ->whereDate('expiration_date', '<=', $expirationDate);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function owner()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function cancel()
    {
        if ($this->isCanceled() || $this->hasBeenFullyFunded()) {
            return;
        }

        CoopCanceled::dispatch($this);
    }

    public function hasBeenFullyFunded()
    {
        return $this->purchases->sum->amount >= $this->goal;
    }
}
