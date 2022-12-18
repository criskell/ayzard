<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Post extends Model
{
    use HasFactory, HasEagerLimit;

    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function shares()
    {
        return $this->hasMany(PostShare::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class);
    }
}
