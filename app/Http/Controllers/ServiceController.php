<?php

namespace App\Http\Controllers;

use App\Actions\Services\CreateService;
use App\DTO\Service\ServiceData;
use App\Http\Requests\Service\StoreServiceRequest;

class ServiceController extends Controller
{
    public function store(
        StoreServiceRequest $request,
        CreateService $action
    ) {
        $data = $request->validated();

        $serviceData = new ServiceData(
            name: $data['name'],
            description: $data['description'],
            base_price: $data['base_price'],
            price_type: $data['price_type'],
            estimated_duration_hours: $data['estimated_duration_hours'],
            other_price_type: $data['other_price_type'] ?? null,
            is_active: $data['is_active'] ?? true
        );

        $action->execute($serviceData);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service created successfully');
    }
}
