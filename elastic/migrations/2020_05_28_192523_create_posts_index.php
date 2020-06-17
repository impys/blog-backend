<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreatePostsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('posts', function (Mapping $mapping, Settings $settings) {
            $settings->index([
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
            ]);

            $mapping->text('title', [
                'type' => 'text',
                'analyzer' => 'ik_smart',
                'search_analyzer' => 'ik_smart',
            ]);

            $mapping->text('body', [
                'type' => 'text',
                'analyzer' => 'ik_max_word',
                'search_analyzer' => 'ik_smart',
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('posts');
    }
}
