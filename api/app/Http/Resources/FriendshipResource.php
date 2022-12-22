<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'source' => new UserResource($this->whenLoaded('source')),
            'target' => new UserResource($this->whenLoaded('target')),
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
