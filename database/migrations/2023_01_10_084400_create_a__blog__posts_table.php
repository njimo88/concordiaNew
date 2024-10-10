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
        Schema::create('a__blog__posts', function (Blueprint $table) {
            $table->id('id_blog_post_primaire');
            $table->dateTime('date_post'); 
            $table->string('titre', 100);
            $table->text('contenu'); 
            $table->json('categorie1');
            $table->json('categorie2');
            $table->string('status', 100);
            $table->string('post_url Index', 255);
            $table->integer('id_user');
            $table->biginteger('id_last_editor');
            $table->tinyInteger('highlighted');
            $table->text('highlight_img');
            $table->tinyinteger('private');

            $table->timestamps('');






        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a__blog__posts');
    }
};
