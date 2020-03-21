<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostView extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['tags'] = $this->tags;

        $data['book'] = new BookView($this->book);

        $data['prev_chapter'] =  new ChapterView($this->getPrevChapter());

        $data['next_chapter'] = new ChapterView($this->getNextChapter());

        return $data;
    }
}
