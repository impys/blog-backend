<?php

namespace App\Http\Resources;

class PostSearchList extends PostList
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
        $data['body_snippet'] = $this->body_snippet;
        $data['body_snippets'] = $this->body_snippets;

        return $data;
    }
}
