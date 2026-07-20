<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->userService->getAllRoles();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(User $user)
    {
        $roles = $this->userService->getAllRoles();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
