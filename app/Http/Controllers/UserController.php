<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::select('id', 'name', 'email', 'created_at', 'role')
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    public function store(
        StoreUserRequest $request,
        CreateUser $action
    ) {
        $action->execute($request->validated());

        return redirect()->route('users.index');
    }
}
