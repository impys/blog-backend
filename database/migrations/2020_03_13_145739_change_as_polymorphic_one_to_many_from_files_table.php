<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeAsPolymorphicOneToManyFromFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->nullableMorphs('entity');
        });

        DB::table('files')
            ->whereNotNull('post_id')
            ->update(
                [
                    'entity_id' => DB::raw('post_id'),
                    'entity_type' => 'post',
                ]
            );

        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign('files_post_id_foreign');
            $table->dropColumn('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->references('id')->on('posts');
        });

        DB::table('files')
            ->update(
                [
                    'post_id' => DB::raw('entity_id')
                ]
            );

        Schema::table('files', function (Blueprint $table) {
            $table->dropMorphs('entity');
        });
    }
}
