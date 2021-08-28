<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Role as RoleResource;

class User extends JsonResource
{
    private $detailed;

    function __construct($resource, $detailed=false) {
        parent::__construct($resource);
        $this->detailed = $detailed;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'pre_name' => $this->pre_name,
            'last_name' => $this->last_name,
            'profile_picture' => $this->profile_picture,
            'role' => new RoleResource($this->role, true)
        ];

        if ($this->detailed) {
            $data['created_at'] = $this->created_at->format('Y-m-d h:m:i');
            $data['updated_at'] = $this->updated_at->format('Y-m-d h:m:i');
            $data['email_verified_at'] = $this->email_verified_at;
            $data['subscribed_newsletter'] = $this->subscribed_newsletter;
        }

        return $data;
    }
}
