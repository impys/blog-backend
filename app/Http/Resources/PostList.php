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
        $data["created_at"] = $this->created_at;
        $data["updated_at"] = $this->updated_at;
        $data["cover_media"] = $this->getCoverMedia();
        $data["summary"] = $this->summary;
        $data["tags"] = $this->tags;
        $data["audio_count"] = $this->audio_count;
        $data["book"] = $this->book;

        return $data;
    }
}
