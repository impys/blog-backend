<?php

namespace App\Nova;

use App\Tag;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
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
    public static $model = \App\Post::class;

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
    ];

    public static $with = [
        'tags',
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

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Image::make('Cover')
                ->preview(function () {
                    return $this->cover;
                })
                ->thumbnail(function () {
                    return $this->cover;
                })
                ->onlyOnDetail(),

            Text::make('Tags', function () {
                return $this->tags->pluck('name')->implode('、');
            }),

            Text::make('Length')->exceptOnForms(),

            Text::make('Audio Count')->exceptOnForms(),

            Text::make('Video Count')->exceptOnForms(),

            PostEditor::make('body')->hideFromIndex(),

            TagAutocomplete::make()->withMeta([
                'tags' => $this
                    ->tags()
                    ->latest()
                    ->get()
                    ->pluck('name')
                    ->toArray(),
                'allTags' => Tag::all()
                    ->pluck('name')
                    ->toArray()
            ])
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
