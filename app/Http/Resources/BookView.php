<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookView extends JsonResource
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
        $data["intro"] = $this->intro;
        $data["cover_url"] = $this->cover_url;
        $data['chapters'] = ChapterList::collection($this->getChapters());

        return $data;
    }
}
