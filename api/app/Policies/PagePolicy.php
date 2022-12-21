<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Page $page)
    {
        return $page->user->is($user);
    }

    public function delete(User $user, Page $page)
    {
        return $page->user->is($user);
    }
}
