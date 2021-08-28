<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Role extends JsonResource
{
    private $exclude_creator;

    public function __construct($resource, $exclude_creator=false) {
        parent::__construct($resource);
        $this->exclude_creator = $exclude_creator;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'is_default' => $this->is_default,
            'created_at' => $this->created_at->format('Y-m-d h:m:i'),
            'updated_at' => $this->updated_at->format('Y-m-d h:m:i')
        ];

        if (!$this->exclude_creator) {
            $data['user'] = new UserResource($this->user);
        }

        return $data;
    }
}
