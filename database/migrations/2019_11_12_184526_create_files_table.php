<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('status');
            $table->string('type');
            $table->string('mime');
            $table->string('name');
            $table->string('size');
            $table->string('original_ext');
            $table->string('encode_ext')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();

            $table->unsignedBigInteger('poster_id')->nullable();
            $table->foreign('poster_id')->references('id')->on('files');

            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->references('id')->on('post');

            $table->string('sort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
