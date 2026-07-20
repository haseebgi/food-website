<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ✅ Store se alag isliye banayi hai kyunke update ke waqt password
// dena zaroori nahi (agar khaali chhoro to purana password rehta hai)
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'phone'    => 'nullable|string|max:20',
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required',
            'password' => 'nullable|min:6|confirmed',
        ];
    }
}
