<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;
use Illuminate\Support\Facades\App;

final class CreatePostsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('posts', function (Mapping $mapping, Settings $settings) {
            $fields = [
                'std' => [
                    'type' => 'text',
                    'analyzer' => 'std',
                    'search_analyzer' => 'ik',
                ],
                'std_prefix' => [
                    'type' => 'text',
                    'analyzer' => 'std_prefix',
                    'search_analyzer' => 'ik',
                ],
                'cn_chars' => [
                    'type' => 'text',
                    'analyzer' => 'standard',
                    'search_analyzer' => 'standard',
                ],
            ];

            $mapping->text('title', ['fields' => $fields]);

            $mapping->text('body', ['fields' => $fields]);

            if (App::environment(['local', 'testing'])) {
                $settings->index(config('elastic.migrations.settings'));
            }

            $settings->analysis(config('elastic.migrations.analysis'));
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
