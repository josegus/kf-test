<?php

namespace App\Models;

use App\Actions\Stripe\CancelCharge;
use App\Actions\Stripe\RefundCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'coop_canceled' => 'bool',
    ];

    public function coop()
    {
        return $this->belongsTo(Coop::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function purchaseTransaction()
    {
        return $this->hasOne(Transaction::class)->ofType('purchase');
    }

    public function refundTransaction()
    {
        return $this->hasOne(Transaction::class)->ofType('refund');
    }

    public function refund()
    {
        $this->purchaseWasDoneUsingOwnFundsOrCredits()
            ? $this->refundToOwnCredits()
            : $this->refundByCreditCard();

        $this->update(['coop_canceled' => true]);
    }

    public function purchaseWasDoneUsingOwnFundsOrCredits(): bool
    {
        return in_array($this->purchaseTransaction->source(), [
            'KickfurtherCredits',
            'KickfurtherFunds'
        ]);
    }

    public function refundToOwnCredits()
    {
        // refunding..
    }

    public function refundByCreditCard()
    {
        $transaction = $this->purchaseTransaction;

        if ($transaction->is_pending) {
            $transaction->cancel();
            return;
        }

        // Credit card has already been charged
        $this->refundByBuyerPreference();
    }

    public function refundByBuyerPreference()
    {
        if ($this->buyer->prefersCreditRefund()) {
            // Refund to buyer's credit
            return;
        }

        (new RefundCharge)->refund(
            $this->banking_customer_token,
            $this->amount
        );
    }
}
