<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $tags = explode(',',$this->tag);

        return [
            'id' => Str::uuid($this->id)->toString(),
            'blog_title' => $this->title,
            'body' => $this->body,
            'tags' => $tags,
            'blog_image' =>  asset("storage/image/blog/$this->blog_image"),
        ];
    }
}
