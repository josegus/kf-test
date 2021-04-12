<?php

namespace App\Models;

use App\Events\CoopCanceled;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coop extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'expiration_date' => 'date'
    ];

    public function __construct()
    {
        parent::__construct();

        Coop::flushEventListeners();
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => \App\Events\CoopCreating::class,
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeToBeCancelToday($query)
    {
        return $query->where('status', 'approved')
            ->whereDate('expiration_date', '<=', today());
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchasesCanceled()
    {
        return $this->purchases()->where('coop_canceled', true);
    }

    public function owner()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function cancel()
    {
        if ($this->isCanceled()) {
            return;
        }

        // If has not reached expiration date, don't cancel it
        if (! $this->hasReachedExpirationDate()) {
            return;
        }

        // Perform cancelation
        $this->update(['status' => 'canceled']);

        CoopCanceled::dispatch($this);
    }

    public function hasReachedExpirationDate()
    {
        return today()->greaterThanOrEqualTo($this->expiration_date);
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function hasBeenFullyFunded()
    {
        return $this->purchases->sum->amount >= $this->goal;
    }
}
