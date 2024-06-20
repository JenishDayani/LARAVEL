<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        // return [
        //     'name' => $this->blog_name,
        // ];
        // return $this->blog_name;

        return [
            'title' => $this->blog_name,
            'body' => $this->blog_description,
            'img' => $this->blog_image,
            'created_at' =>$this->created_at,
            'updated_at' =>$this->updated_at,
            'tag' => TagResource::collection($this->tag),
            'user' => new UserResource($this->user)
        ];
    }
}
