<?php

namespace App\Http\Controllers;

use App\Actions\Services\CreateService;
use App\Actions\Services\ListServices;
use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
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
            estimated_duration_hours: $data['estimated_duration_hours'] ?? null,
            other_price_type: $data['other_price_type'] ?? null,
            is_active: $data['is_active'] ?? true
        );

        $action->execute($serviceData);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service created successfully');
    }
}
