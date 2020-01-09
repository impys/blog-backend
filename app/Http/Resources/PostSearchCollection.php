<?php

namespace App\Http\Resources;

use App\Tag;

class PostSearchCollection extends BaseSearchCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->getHits(),
            'meta' => $this->getMeta(),
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'tags' => Tag::getAllValidTags(),
            ],
        ];
    }
}
