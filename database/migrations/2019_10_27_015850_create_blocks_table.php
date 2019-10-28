<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('label');
            $table->string('color');
            $table->boolean('is_top')->default(false);
            $table->unsignedInteger('sort')->default(0);
        });

        DB::table('blocks')
            ->insert([
                [
                    'name' => 'article',
                    'label' => '文章',
                    'color' => '#fc8181',
                ],
                [
                    'name' => 'sentence',
                    'label' => '句子',
                    'color' => '#68d391',
                ],
                [
                    'name' => 'video',
                    'label' => '视频',
                    'color' => '#63b3ed'
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
