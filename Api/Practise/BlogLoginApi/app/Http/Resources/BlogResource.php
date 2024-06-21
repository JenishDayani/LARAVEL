<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

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
        $tag = explode(',',$this->tag);

        return [
            // 'id' => $this->id,
            // 'uuid' => $this->uuid,
            // 'id' =>$this->uuid,
            'blog_number' => $this->id,
            'title' => $this->title,
            'body' => $this->des,
            'img' => $this->blog_image,
            // 'img' => asset('storage/images/blog/'.$this->blog_image),
            'created_at' => $this->created_at,
            'updated_at' => date('d-M:/Y h:i',strToTime($this->updated_at)),
            'tag' => $tag,
            'user' => new UserResource($this->user)
            // 'user' => $this->user
        ];
    }
}