<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->id('gender_id');
            $table->string('title',50);
        });

        $array = [
            ['title'=> 'Not Specified'],
            ['title'=> 'Female'],
            ['title'=> 'Male']
        ];
        DB::table('genders')->insert($array);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genders');
    }
};
