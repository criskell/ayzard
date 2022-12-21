<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Group $group, ?GroupMember $member)
    {
        return $this->admin($user, $group, $member);
    }

    public function delete(User $user, Group $group)
    {
        return $group->creator->is($user);
    }

    public function admin(User $user, Group $group, ?GroupMember $member)
    {
        return $group->creator->is($user) || $member?->is_admin;
    }

    public function member(User $user, Group $group, ?GroupMember $member)
    {
        return $member;
    }
}
