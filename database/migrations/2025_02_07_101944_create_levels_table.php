<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('color', 7)->default('#FFFFFF');;

            $table->check("color REGEXP '^#[0-9A-Fa-f]{6}$'");
        });
    }

    public function down()
    {
        Schema::dropIfExists('levels');
    }
};