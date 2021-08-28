<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Ban extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'target' => new UserResource($this->target),
            'staff' => new UserResource($this->staff),
            'reason' => $this->reason,
            'description' => $this->description,
            'ban_until' => $this->ban_until,
            'type' => $this->type,
            'active' => $this->is_active(),
            'created_at' => $this->created_at->format('Y-m-d h:m:i'),
            'updated_at' => $this->updated_at->format('Y-m-d h:m:i')
        ];
    }
}
