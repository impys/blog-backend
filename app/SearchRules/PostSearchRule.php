<?php

namespace App\SearchRules;

use App\Post;
use ScoutElastic\SearchRule;
use ScoutElastic\Builders\SearchBuilder;
use Illuminate\Contracts\Pagination\Paginator;

class PostSearchRule extends SearchRule
{
    /**
     * Subfields in elasticsearch uses different analyzers
     *
     * @var array
     */
    protected $subfields = [
        'std',
        'std_prefix',
        'cn_chars',
    ];

    protected $nameFields = [
        'title',
        'slug',
        'body',
    ];


    /**
     * Return raw results from elasticsearch
     *
     * @param array $options
     * @param $accountId
     * @return Paginator
     */
    public static function build(array $options): Paginator
    {
        $query = $options['q'] ?? null;
        $size = $options['size'] ?? 15;

        $searcher = Post::search($query)->rule(self::class);

        self::buildPostFilter($searcher, $options);

        self::buildSort($searcher, $options);

        info($searcher->buildPayload());

        return $searcher->paginate($size);
    }

    /**
     * Add ordering to builder
     *
     * @param SearchBuilder $builder
     * @param array $options
     * @return SearchBuilder
     */
    protected static function buildSort(SearchBuilder $builder, array $options)
    {
        // Default search order by staff defined vote, descending
        $orderBy = $options['order_by'] ?? 'updated_at';
        $orderDir = $options['order_dir'] ?? 'desc';

        // First sort by is_top
        // $builder->orderBy($orderBy, $orderDir);

        return $builder;
    }

    protected static function buildPostFilter(SearchBuilder $builder, array $filters)
    {
        $postIds = $filters['post_ids'] ?? [];
        $tagIds = $filters['tag_ids'] ?? [];
        $bookIds = $filters['book_ids'] ?? [];

        $postFilter = $builder->postFilter;

        if ($postIds) {
            $postFilter->whereIn('id', $postIds);
        }

        if ($tagIds) {
            $postFilter->whereIn('id', $tagIds);
        }

        if ($bookIds) {
            $postFilter->whereIn('id', $bookIds);
        }

        return $builder;
    }

    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
    }

    public function buildQueryPayload()
    {
        if (!$this->builder->query) {
            return [];
        }


        $should = $this->buildFieldsQuery();

        return [
            // Wrap query in must to ensure we always apply these conditions even
            // when other filters are added by the searcher
            'must' => [
                'bool' => [
                    'should' => $should,
                ],
            ],
        ];
    }

    protected function buildFieldsQuery(string $path = ''): array
    {
        return [
            $this->buildNameMatch($path),
        ];
    }

    /**
     * Build multi-match query for the specified fields using the given analyzer subfield
     *
     * @param string $searchTerm
     * @param $fields
     * @param string $analyzer
     * @param array $options
     * @return array
     */
    protected function buildMultiMatch(string $searchTerm, $fields, string $analyzer = "", $options = []): array
    {

        $fields = array_map(function ($field) use ($analyzer) {
            return $analyzer ? "{$field}.{$analyzer}" : $field;
        }, $fields);

        // Match all search terms in combined fields by default
        $params = [
            'query' => $searchTerm,
            'fields' => $fields,
        ];
        $params = array_merge($params, [
            'operator' => 'and',
            'type' => 'cross_fields',
        ]);

        return [
            'multi_match' => $params,
        ];
    }

    /**
     * @param string $path
     * @return array
     */
    protected function buildNameMatch(string $path = ''): array
    {
        if (!$this->builder->query) {
            return ['match_all' => (object) []];
        }

        $should = [];

        // prefix field names with the desired path
        $nameFields = array_map(function ($f) use ($path) {
            return "{$path}$f";
        }, $this->nameFields);

        foreach ($this->subfields as $subfield) {
            $should[] = $this->buildMultiMatch($this->builder->query, $nameFields, $subfield);
        }

        // constant sum for normal match and phrase match gives more relevant results
        return [
            'constant_score' => [
                'boost' => 1.5,
                'filter' => [
                    'bool' => [
                        'should' => $should,
                    ],
                ],
            ],
        ];
    }
}
