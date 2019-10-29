<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('body');
            $table->boolean('is_enable')->default(false);
            $table->boolean('is_top')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedInteger('visited_count')->default(0);
            $table->unsignedInteger('upvote_count')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
