<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class MiniCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at->format('Y-m-d h:m:i'),
            'updated_at' => $this->updated_at->format('Y-m-d h:m:i')
        ];
    }
}
