<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 15)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('amount', 20)->nullable();
            $table->string('transaction_id', 15)->nullable();
            $table->string('product_id', 10)->nullable();
            $table->string('description', 150)->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
