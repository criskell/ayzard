<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type ?? 'regular',
            'content' => $this->content,
            'comments_count' => $this->whenCounted('comments'),
            'likes_count' => $this->whenCounted('likes'),
            'shares_count' => $this->whenCounted('shares'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'user' => new UserResource($this->whenLoaded('user')),
            'shared_at' => $this->whenNotNull($this->shared_at),
            'shared_by' => $this->when(
                $this->shared_by_id,
                fn () =>
                    new UserResource(User::find($this->shared_by_id))
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
