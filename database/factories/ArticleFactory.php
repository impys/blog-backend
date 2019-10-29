<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use App\Block;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Article::class, function (Faker $faker) {
    $titles = [
        '说出你的心里话',
        '他们让你收着，我偏让你作',
        '真相只有少数人知道',
        '主人公的反转人生'
    ];

    $updated_at = $faker->dateTimeThisMonth();
    $created_at = $faker->dateTimeThisMonth($updated_at);

    $body = "## 真相只有少数人知道 \n真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道\n## h2 Heading\n### h3 Heading\n#### h4 Heading\n##### h5 Heading\n###### h6 Heading\n\n\n## Horizontal Rules\n\n___\n\n---\n\n***\n\n\n## 真相只有少数人知道\n\n**真相只有少数人知道**\n\n__真相只有少数人知道__\n\n*真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道真相只有少数人知道*\n\n_This is italic text_\n\n~~Strikethrough~~\n\n\n## Blockquotes\n\n\n> Blockquotes can also be nested...\n>> ...by using additional greater-than signs right next to each other...\n> > > ...or with spaces between arrows.\n\n\n## Lists\n\nUnordered\n\n+ Create a list by starting a line with `+`, `-`, or `*`\n+ Sub-lists are made by indenting 2 spaces:\n  - Marker character change forces new list start:\n    * Ac tristique libero volutpat at\n    + Facilisis in pretium nisl aliquet\n    - Nulla volutpat aliquam velit\n+ Very easy!\n\nOrdered\n\n1. Lorem ipsum dolor sit amet\n2. Consectetur adipiscing elit\n3. Integer molestie lorem at massa\n\n\n1. You can use sequential numbers...\n1. ...or keep all the numbers as `1.`\n\nStart numbering with offset:\n\n57. foo\n1. bar\n\n\n## Code\n\nInline `code`\n\n```php\n<?php\n\nuse Illuminate\\Support\\Facades\\Schema;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Database\\Migrations\\Migration;\n \nclass CreateArticlesTable extends Migration\n{\n    /**\n     * Run the migrations.\n     *\n     * @return void\n     */\n    public function up()\n    {\n        Schema::create('articles', function (Blueprint \$table) {\n            \$table->increments('id');\n            \$table->string('title')->index();\n            \n        });\n    }\n}\n\n```\n\n## Tables\n\n| Option | Description |\n| ------ | ----------- |\n| data   | path to data files to supply the data that will be passed into templates. |\n| engine | engine to be used for processing templates. Handlebars is the default. |\n| ext    | extension to be used for dest files. |\n\n\n## Images\n\n![Minion](https://octodex.github.com/images/minion.png)\n\n\n## Plugins\n\n### [Emojies](https://github.com/markdown-it/markdown-it-emoji)\n\n> Classic markup: :wink: :crush: :cry: :tear: :laughing: :yum:\n>\n> Shortcuts (emoticons): :-) :-( 8-) ;)\n\n";

    return [
        'title' => $faker->randomElement($titles),
        'body' => $body,
        'slug' => Str::slug($faker->sentence(), '-'),
        'is_top' => false,
        'user_id' => 1,
        'visited_count' => mt_rand(0, 500),
        'upvote_count' => mt_rand(0, 500),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
