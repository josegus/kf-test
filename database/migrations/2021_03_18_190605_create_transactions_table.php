<?php

use App\Models\Buyer;
use App\Models\Coop;
use App\Models\Purchase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('buyer_id');
            $table->foreignIdFor(Buyer::class)->constrained();
            //$table->unsignedBigInteger('coop_id')->nullable();
            $table->foreignIdFor(Coop::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            //$table->unsignedBigInteger('purchase_id')->nullable();
            $table->foreignIdFor(Purchase::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->unsignedBigInteger('refund_transaction_id')->nullable();
            $table->string('type');
            $table->decimal('amount');
            $table->string('source');
            $table->string('memo');
            $table->boolean('is_canceled')->default(false);
            $table->boolean('is_pending')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
