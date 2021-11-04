<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Document extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user' => new User($this->user),
            'title' => $this->title,
            'downloads' => $this->downloads()->count(),
            'require_password' => $this->password != null,
            'mime_type' => Storage::mimeType($this->file_name),
            'size' => Storage::size($this->file_name),
            'created_at' => $this->created_at->format('Y-m-d h:m:i'),
            'updated_at' => $this->updated_at->format('Y-m-d h:m:i')
        ];

        if ($this->is_category) $data['category'] = $this->category;
        if ($this->is_post) $data['post'] = $this->post;
        if ($this->file_name) $data['file_name'] = $this->file_name;
        if ($this->expires_at) $data['expires_at'] = $this->expires_at->format('Y-m-d h:m:i');
        if ($this->max_downloads) $data['max_downloads'] = $this->max_downloads;

        return $data;
    }
}
