<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterList extends JsonResource
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
        $data["created_at"] = $this->created_at;
        $data["updated_at"] = $this->updated_at;
        $data['chapter'] = $this->chapter;
        $data['chapter_index'] = $this->chapter_index;

        return $data;
    }
}
