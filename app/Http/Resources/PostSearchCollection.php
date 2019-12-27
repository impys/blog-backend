<?php

namespace App\Http\Resources;

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
}
