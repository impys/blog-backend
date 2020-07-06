<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeView extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data["books"] = BookList::collection($this->resource['books']);
        $data["posts"] = PostList::collection($this->resource['posts']);

        return $data;
    }
}
