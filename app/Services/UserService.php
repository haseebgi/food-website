<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;

// ✅ Sirf User se related database ka kaam yahan hoga.
// Password hashing manually nahi ki jaati — User model mai
// 'password' => 'hashed' cast lagi hui hai, wo khud hash kar deti hai.
class UserService
{
    public function getAllUsers()
    {
        return User::with('role')->latest()->get();
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'role_id'  => $data['role_id'],
            'password' => $data['password'], // cast khud hash kar dega
            'status'   => $data['status'],
        ]);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'role_id' => $data['role_id'],
            'status'  => $data['status'],
        ]);

        // Password sirf tab update karo jab user ne naya diya ho
        if (!empty($data['password'])) {
            $user->update([
                'password' => $data['password'], // cast khud hash kar dega
            ]);
        }

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
