<?php

namespace App\Indices;

use ScoutElastic\Migratable;
use Illuminate\Support\Facades\App;
use ScoutElastic\IndexConfigurator;

class BaseIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        //
    ];

    protected $charFilters = [
        'punctuation_strip' => [
            'type' => 'mapping',
            'mappings' => [
                // Apostrophe variants
                "'=>",
                "‘=>",
                "’=>",
                "‛=>",
                // TODO: what else to strip
            ],
        ],
    ];

    protected $tokenizers = [
        'ik' => [
            'type' => 'ik_max_word'
        ]
    ];

    protected $filters = [
        'ik_pinyin' => [
            'type' => 'pinyin',
            'keep_first_letter' => true,
            'keep_joined_full_pinyin' => true,
            'keep_none_chinese_together' => true,
            'none_chinese_pinyin_tokenize' => false,
            'keep_original' => true,
        ],
        'edge_ngram' => [
            'type' => 'edgeNGram',
            'min_gram' => 1,
            'max_gram' => 50,
        ],
        // greedy delimiter (keep original words, catenate words)
        'delimiter_greedy' => [
            'type' => 'word_delimiter',
            'catenate_words' => true,
            'stem_english_possessive' => false,
            'preserve_original' => true,
        ],
        // min length filter
        'length' => [
            'type' => 'length',
            'min' => 2,
        ],
    ];

    protected $analyzers = [
        // chinese characters, full pinyin, pinyin first letters
        'std' => [
            'char_filter' => [
                'html_strip',
                'punctuation_strip',
            ],
            'filter' => [
                'ik_pinyin',
            ],
            'tokenizer' => 'ik'
        ],
        // prefix std for autocomplete functionality
        'std_prefix' => [
            'char_filter' => [
                'html_strip',
                'punctuation_strip',
            ],
            'filter' => [
                'ik_pinyin',
                'edge_ngram',
            ],
            'tokenizer' => 'ik',
        ],
        // tokenize chinese characters without converting to pinyin
        'ik' => [
            'char_filter' => [
                'html_strip',
                'punctuation_strip',
            ],
            'tokenizer' => 'ik'
        ],
        // chinese keywords (full pinyin + pinyin first letters) without IK tokenizer
        // good for chinese keywords to be converted to pinyin
        'cn_keywords_prefix' => [
            'filter' => [
                'ik_pinyin',
                'edge_ngram',
            ],
            'tokenizer' => 'whitespace',
        ],
        // Analyzer to delimit and catenate words in various ways
        // Good for english brand names
        'delimiter_prefix' => [
            'filter' => [
                'delimiter_greedy',
                'length',
                'lowercase',
                'edge_ngram',
            ],
            'tokenizer' => 'whitespace',
        ],
        // case-insensitve tags and keywords (whitespace delimited)
        'keywords' => [
            'filter' => [
                'lowercase',
            ],
            'tokenizer' => 'whitespace'
        ],
        'keywords_prefix' => [
            'filter' => [
                'lowercase',
                'edge_ngram'
            ],
            'tokenizer' => 'whitespace'
        ],
    ];

    public function __construct()
    {
        $this->settings['analysis'] = [
            'char_filter' => $this->charFilters,
            'tokenizer' => $this->tokenizers,
            'filter' => $this->filters,
            'analyzer' => $this->analyzers,
        ];

        if (!App::environment(['production'])) {
            // See https://www.elastic.co/guide/en/elasticsearch/guide/2.x/relevance-is-broken.html
            $this->settings['number_of_shards'] = 1;
        }
    }
}
