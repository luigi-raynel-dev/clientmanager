<?php

namespace App\Http\Controllers;

use App\Actions\Clients\CreateClient;
use App\Actions\Clients\EditClient;
use App\Actions\Clients\GetClient;
use Inertia\Inertia;
use App\Actions\Clients\ListClients;
use App\DTO\Client\ClientData;
use App\DTO\Client\ClientFilter;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;

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

        $clients = $action->execute($filter);

        return Inertia::render('Clients/Index', [
            ...compact('clients'),
            'filters' => request()->only(['q', 'order_by', 'order_direction']),
        ]);
    }

    public function edit(int $id, GetClient $action)
    {
        $client = $action->execute($id);

        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    public function store(
        StoreClientRequest $request,
        CreateClient $action
    ) {
        $data = $request->validated();

        $clientData = new ClientData(
            name: $data['name'],
            email: $data['email'],
        );

        $action->execute($clientData);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully');
    }

    public function update(
        int $id,
        UpdateClientRequest $request,
        EditClient $action
    ) {
        $data = $request->validated();

        $clientData = new ClientData(
            name: $data['name'],
            email: $data['email']
        );

        $action->execute($id, $clientData);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully');
    }
}
