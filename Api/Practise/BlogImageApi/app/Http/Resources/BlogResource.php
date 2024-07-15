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
        $tags = explode(',',$this->tags);
        return [
            'id' => Str::uuid($this->id),
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $tags,
            'blogImage' => asset("storage/images/blog/$this->blog_image"),
            'blog_created' => $this->created_at,
            'last_updated' => $this->updated_at,
            'userDetail' => new UserResource($this->user),
        ];
    }
}
