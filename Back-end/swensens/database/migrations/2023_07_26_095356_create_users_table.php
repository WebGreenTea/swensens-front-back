<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->string('firstname', 50);
            $table->string('surname', 50);
            $table->string('phone', 10);
            $table->string('email', 50);
            $table->text('password');
            $table->integer('gender_id');
            $table->timestamp('birthday')->nullable();
            $table->boolean('rule_accept')->default(false);
            $table->boolean('noti_accept')->default(true);
            
            $table->index('gender_id');
            $table->unique('email');
            $table->unique('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
