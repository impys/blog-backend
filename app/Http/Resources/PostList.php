<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data["id"] = $this->id;
        $data["title"] = $this->title;
        $data["is_top"] = $this->is_top;
        $data["visited_count"] = $this->visited_count;
        $data["created_at_human"] = $this->created_at_human;
        $data["updated_at_human"] = $this->updated_at_human;
        $data["cover_media"] = $this->cover_media;
        $data["summary"] = $this->summary;
        $data["tags"] = $this->tags;

        return $data;
    }
}
