<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
