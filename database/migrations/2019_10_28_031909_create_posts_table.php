<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('title')->nullable();
            $table->text('body');
            $table->boolean('is_enable')->default(true);
            $table->boolean('is_top')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedInteger('visited_count')->default(0);
            $table->unsignedInteger('upvote_count')->default(0);
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
