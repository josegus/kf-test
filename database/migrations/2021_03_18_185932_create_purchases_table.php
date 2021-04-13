<?php

use App\Models\Buyer;
use App\Models\Coop;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('coop_id');
            $table->foreignIdFor(Coop::class)
                ->constrained();
            //$table->unsignedBigInteger('buyer_id');
            $table->foreignIdFor(Buyer::class)
                ->constrained();
            $table->decimal('amount');
            $table->unsignedInteger('package_quantity');
            $table->unsignedInteger('package_id');
            $table->string('banking_customer_token')->nullable();
            $table->boolean('coop_canceled')->default(false);
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
        Schema::dropIfExists('purchases');
    }
}
