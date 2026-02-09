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
        $filter = new UserFilter(request('q'));

        $users = $useCase->execute($filter);

        return Inertia::render('Users/Index', [
            ...compact('users'),
            'filters' => request()->only('q'),
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
