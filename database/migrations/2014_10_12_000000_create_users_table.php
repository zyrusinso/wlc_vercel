<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('endorsers_id')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('role')->default('user')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('cp_num')->nullable();
            $table->string('level', 15)->nullable();
            $table->string('tpp', 20)->nullable()->default(0);
            $table->string('cbb', 20)->nullable()->default(3);
            $table->string('available_cash_bal', 20)->nullable()->default(0);
            $table->string('total_cash_bonus', 20)->nullable()->default(0);
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
