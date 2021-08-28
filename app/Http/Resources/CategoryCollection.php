<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    protected $onlyVerified;

    public function onlyVerified($onlyVerified){
        $this->onlyVerified = $onlyVerified;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function(Category $resource) use($request){
            return $resource->onlyVerified($this->onlyVerified)->toArray($request);
        })->all();
    }
}
