<?php

use App\Models\Brand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coops', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('brand_id');
            $table->foreignIdFor(Brand::class)
                ->constrained();
            $table->string('name');
            $table->date('expiration_date');
            $table->decimal('goal');
            $table->string('status');
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
        Schema::dropIfExists('coops');
    }
}
