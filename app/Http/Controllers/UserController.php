<?php

namespace App\Http\Controllers;

use App\Actions\Users\ChangeUserStatusAction;
use App\Actions\Users\CreateUser;
use App\Actions\Users\EditUser;
use App\Actions\Users\GetUser;
use App\Actions\Users\ListUsers;
use App\Actions\Users\DeleteUser;
use App\DTO\User\UserData;
use App\DTO\User\UserFilter;
use App\Http\Requests\User\ChangeUserStatusRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(ListUsers $action)
    {
        $filter = new UserFilter(
            search: request('q'),
            role: request('role'),
            order_by: request('order_by'),
            order_direction: request('order_direction'),
            per_page: request('per_page'),
            page: request('page'),
        );

        $users = $action->execute($filter);

        return Inertia::render('Users/Index', [
            ...compact('users'),
            'filters' => request()->only(['q', 'role', 'order_by', 'order_direction']),
        ]);
    }

    public function edit(int $id, GetUser $action)
    {
        $user = $action->execute($id);

        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    public function store(
        StoreUserRequest $request,
        CreateUser $action
    ) {
        $data = $request->validated();

        $userData = new UserData(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );

        $action->execute($userData);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function update(
        int $id,
        UpdateUserRequest $request,
        EditUser $action
    ) {
        $data = $request->validated();

        $userData = new UserData(
            name: $data['name'],
            email: $data['email'],
            role: $data['role'],
        );

        $action->execute($id, $userData);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function changeStatus(
        int $id,
        ChangeUserStatusRequest $request,
        ChangeUserStatusAction $action
    ) {
        $data = $request->validated();
        $action->execute($id, $data['is_blocked']);

        return redirect()->route('users.index');
    }

    public function destroy(
        int $id,
        DeleteUser $action
    ) {
        $action->execute($id);

        return redirect()->route('users.index');
    }
}
