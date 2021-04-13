<?php

namespace App\Models;

use App\Events\PurchaseCreated;
use App\Events\PurchaseRefunded;
use App\Actions\Stripe\RefundChargeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'coop_canceled' => 'bool',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => PurchaseCreated::class,
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

    public function hasBeenRefunded(): bool
    {
        return $this->coop_canceled
            && $this->refundTransaction !== null;
    }

    public function refund()
    {
        $this->purchaseWasDoneUsingOwnFundsOrCredits()
            ? $this->refundToOwnCredits()
            : $this->refundByCreditCard();

        $this->update(['coop_canceled' => true]);

        // Create transaction
        PurchaseRefunded::dispatch($this);
    }

    protected function purchaseWasDoneUsingOwnFundsOrCredits(): bool
    {
        return in_array($this->purchaseTransaction->source, [
            'KickfurtherCredits',
            'KickfurtherFunds'
        ]);
    }

    protected function refundToOwnCredits()
    {
        // refunding..
    }

    protected function refundByCreditCard()
    {
        $transaction = $this->purchaseTransaction;

        if ($transaction->is_pending) {
            $transaction->cancel();
            return;
        }

        // Credit card has already been charged
        $this->refundByBuyerPreference();
    }

    protected function refundByBuyerPreference()
    {
        if ($this->buyer->prefersCreditRefund()) {
            // Refund to buyer's credit
            return;
        }

        info('starting stripe refund: ' . $this->id . ' - ' . now()->toTimeString());

        app(RefundChargeInterface::class)->refund(
            $this->banking_customer_token,
            $this->amount
        );

        info('finished stripe refund: ' . $this->id . ' - ' . now()->toTimeString());
    }
}
