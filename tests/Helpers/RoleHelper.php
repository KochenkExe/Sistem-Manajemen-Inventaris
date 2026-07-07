<?php

namespace Tests\Helpers;

use App\Models\Role;
use App\Models\User;

trait RoleHelper
{
    protected function createUserWithRole(string $roleName): User
    {
        $role = Role::where('name', $roleName)->first();
        $user = User::factory()->create(['role_id' => $role->id]);
        // Mark email as verified so 'verified' middleware passes
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }

    protected function createAdmin(): User
    {
        return $this->createUserWithRole('admin');
    }

    protected function createStaff(): User
    {
        return $this->createUserWithRole('staff');
    }

    protected function createManager(): User
    {
        return $this->createUserWithRole('manager');
    }
}
