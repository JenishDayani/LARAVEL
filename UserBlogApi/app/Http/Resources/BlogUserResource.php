<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;


class BlogUserResource extends JsonResource
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
            'id' => Str::uuid($this->id)->toString(),
            'title' => $this->title,
            'body' => $this->body,
            'image' => asset("storage/image/blog/$this->blog_image"),
            'tags' => $tag,
            'create' => date('d-M-Y',strToTime($this->created_at)),
            'user_info' => new UserResource($this->user)
        ];
    }
}
