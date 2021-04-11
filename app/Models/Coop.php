<?php

namespace App\Models;

use App\Notifications\CoopCanceled;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coop extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

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

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function owner()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function cancel(string $reason = 'Unprocessable coop')
    {
        if ($this->isCanceled()) {
            return;
        }

        // Perform cancelation
        $this->update(['status' => 'canceled']);

        $this->owner->notify(new CoopCanceled($reason));
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
