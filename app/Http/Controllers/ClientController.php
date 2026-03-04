<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\Clients\ListClients;
use App\DTO\Client\ClientFilter;

class ClientController extends Controller
{
    public function index(ListClients $action)
    {
        $filter = new ClientFilter(
            search: request('q'),
            order_by: request('order_by'),
            order_direction: request('order_direction'),
            per_page: request('per_page'),
            page: request('page'),
        );

        $users = $action->execute($filter);

        return Inertia::render('Clients/Index', [
            ...compact('users'),
            'filters' => request()->only(['q', 'order_by', 'order_direction']),
        ]);
    }
}
