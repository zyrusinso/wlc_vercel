<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userinfos', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('region')->nullable();
            $table->string('region_code')->nullable();
            $table->string('province')->nullable();
            $table->string('province_code')->nullable();
            $table->string('city')->nullable();
            $table->string('city_code')->nullable();
            $table->string('barangay')->nullable();
            $table->string('barangay_code')->nullable();
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
        Schema::dropIfExists('userinfos');
    }
};
