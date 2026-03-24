<?php

namespace App\Http\Controllers;

use App\Actions\Services\ChangeServiceStatusAction;
use App\Actions\services\EditService;
use App\Actions\Services\CreateService;
use App\Actions\Services\DeleteService;
use App\Actions\Services\GetService;
use App\Actions\Services\ListServices;
use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
use App\Http\Requests\Service\ChangeServiceStatusRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Requests\Service\StoreServiceRequest;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(ListServices $action)
    {
        $filter = new ServiceFilter(
            search: request('q'),
            is_active: request('is_active'),
            order_by: request('order_by'),
            order_direction: request('order_direction'),
            per_page: request('per_page'),
            page: request('page'),
        );

        $services = $action->execute($filter);

        return Inertia::render('Services/Index', [
            ...compact('services'),
            'filters' => request()->only(['q', 'order_by', 'order_direction', 'is_active']),
        ]);
    }

    public function edit(int $id, GetService $action)
    {
        $service = $action->execute($id);

        return Inertia::render('Services/Edit', [
            'service' => $service,
        ]);
    }

    public function store(
        StoreServiceRequest $request,
        CreateService $action
    ) {
        $data = $request->validated();

        $serviceData = new ServiceData(
            name: $data['name'],
            description: $data['description'] ?? null,
            base_price: $data['base_price'] ?? null,
            price_type: $data['price_type'] ?? null,
            estimated_duration_minutes: $data['estimated_duration_minutes'] ?? null,
            estimated_duration_type: $data['estimated_duration_type'] ?? null,
            other_price_type: $data['other_price_type'] ?? null,
            is_active: $data['is_active'] ?? true
        );

        $action->execute($serviceData);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service created successfully');
    }

    public function update(
        int $id,
        UpdateServiceRequest $request,
        EditService $action
    ) {
        $data = $request->validated();

        $serviceData = new ServiceData(
            name: $data['name'],
            description: $data['description'] ?? null,
            base_price: $data['base_price'] ?? null,
            price_type: $data['price_type'] ?? null,
            estimated_duration_minutes: $data['estimated_duration_minutes'] ?? null,
            estimated_duration_type: $data['estimated_duration_type'] ?? null,
            other_price_type: $data['other_price_type'] ?? null,
            is_active: is_null($data['is_active']) ? true : $data['is_active']
        );

        $action->execute($id, $serviceData);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service updated successfully');
    }

    public function changeStatus(
        int $id,
        ChangeServiceStatusRequest $request,
        ChangeServiceStatusAction $action
    ) {
        $data = $request->validated();
        $action->execute($id, $data['is_active']);

        return redirect()->route('services.index');
    }

    public function destroy(
        int $id,
        DeleteService $action
    ) {
        $action->execute($id);

        return redirect()->route('services.index');
    }
}
