<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bills_status', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('image');    
        });
    }
    public function down()
    {
        
    }
};
