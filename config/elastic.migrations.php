<?php

return [
    'table' => env('ELASTIC_MIGRATIONS_TABLE', 'elastic_migrations'),
    'storage_directory' => env('ELASTIC_MIGRATIONS_DIRECTORY', base_path('elastic/migrations')),

    'settings' => [
        'number_of_shards' => 1,
    ],

    'analysis' => [
        'char_filter' => [
            // 自定义一个char filter，名为quotes，用于处理引号
            'quotes' => [
                'type' => 'mapping',
                'mappings' => [
                    "'=>",
                    "‘=>",
                    "’=>",
                    "‛=>",
                ],
            ],
        ],

        'tokenizer' => [
            // ik 分析器
            'ik' => [
                // 可看 https://github.com/medcl/elasticsearch-analysis-ik
                'type' => 'ik_max_word'
            ]
        ],

        // 自定义一些 filter
        'filter' => [
            'ik_pinyin' => [
                'type' => 'pinyin',
                'keep_first_letter' => true,
                'keep_joined_full_pinyin' => true,
                'keep_none_chinese_together' => true,
                'none_chinese_pinyin_tokenize' => false,
                'keep_original' => true,
            ],
            'autocomplete' => [
                'type' => 'edgeNGram',
                'min_gram' => 1,
                'max_gram' => 50,
            ],
            'delimiter_greedy' => [
                'type' => 'word_delimiter',
                'catenate_words' => true,
                'stem_english_possessive' => false,
                'preserve_original' => true,
            ],
        ],

        'analyzer' => [
            // 常规操作，用于中文、中文全拼以及中文首字母
            'std' => [
                'char_filter' => [
                    'html_strip',
                    'quotes',
                ],
                'filter' => [
                    'ik_pinyin',
                ],
                'tokenizer' => 'ik'
            ],

            'std_prefix' => [
                'char_filter' => [
                    'html_strip',
                    'quotes',
                ],
                'filter' => [
                    'ik_pinyin',
                    'autocomplete',
                ],
                'tokenizer' => 'ik',
            ],

            // 纯中文，不带拼音
            'ik' => [
                'char_filter' => [
                    'html_strip',
                    'quotes',
                ],
                'tokenizer' => 'ik'
            ],

            'delimiter_prefix' => [
                'filter' => [
                    'delimiter_greedy',
                    'length',
                    'lowercase',
                    'edge_ngram',
                ],
                'tokenizer' => 'whitespace',
            ],

            // 关键字，用于精确搜索
            'keywords' => [
                'filter' => [
                    'lowercase',
                ],
                // 使用自带的空格分词
                'tokenizer' => 'whitespace'
            ],
        ]
    ],
];
