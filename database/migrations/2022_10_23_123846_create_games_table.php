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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_theloai');
            $table->string('thumb_image');
            $table->string('link_game');
            $table->string('tengame',1000);
            $table->string('slug');
            $table->longText('mota');
            $table->integer('soluotchoi');
            $table->text('image1');
            $table->text('image2');
            $table->text('image3');
            $table->text('image4');
            $table->string('gh_dotuoi');
            $table->bigInteger('like');
            $table->bigInteger('unlike');
            $table->smallInteger('trangthai');
            $table->timestamps();

            $table->foreign('id_theloai')->references('id')->on('theloaigames');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};
