<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\ListUsers;
use App\DTO\User\UserFilter;
use App\Http\Requests\User\StoreUserRequest;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(ListUsers $useCase)
    {
        $params = request()->only(['q', 'role', 'order_by', 'order_direction']);

        $filter = new UserFilter(
            search: $params['q'] ?? null,
            role: $params['role'] ?? null,
            order_by: $params['order_by'] ?? null,
            order_direction: $params['order_direction'] ?? null,
        );

        $users = $useCase->execute($filter);

        return Inertia::render('Users/Index', [
            ...compact('users'),
            'filters' => $params,
        ]);
    }

    public function store(
        StoreUserRequest $request,
        CreateUser $action
    ) {
        $action->execute($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');;
    }
}
