<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupMemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->user),
            'group' => new GroupResource($this->group),
            'is_admin' => (bool) $this->is_admin,
        ];
    }
}
