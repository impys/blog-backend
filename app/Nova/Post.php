<?php

namespace App\Nova;

use App\Tag;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Qingfengbaili\PostEditor\PostEditor;
use Qingfengbaili\TagAutocomplete\TagAutocomplete;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Post';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'slug',
    ];

    public static $with = [
        'tags',
        'files',
        'book',
        'user',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')->sortable(),

            Text::make('Slug')->onlyOnDetail(),

            Boolean::make('Is Enabled'),

            Boolean::make('Is Top'),

            BelongsTo::make('Book')->searchable()->nullable(),

            Number::make('chapter')->step(1)->help('默认为最后一个章节'),

            PostEditor::make('body')->onlyOnForms(),

            Text::make('Tags', function () {
                return $this->tags->pluck('name')->implode('、');
            }),

            Text::make('Length')->exceptOnForms(),

            Text::make('Audio Count')->exceptOnForms(),

            BelongsTo::make('User')->hideWhenCreating()->hideWhenUpdating(),

            TagAutocomplete::make()->withMeta([
                'tags' => $this
                    ->tags
                    ->pluck('name')
                    ->toArray(),
                'allTags' => Tag::all()
                    ->pluck('name')
                    ->toArray()
            ]),

            HasMany::make('Files'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
