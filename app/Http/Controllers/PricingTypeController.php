<?php

namespace App\Http\Controllers;

use App\Actions\PricingTypes\CreatePricingType;
use App\DTO\PricingType\PricingTypeData;
use App\Http\Requests\PricingType\StorePricingTypeRequest;

class PricingTypeController extends Controller
{

    public function store(
        StorePricingTypeRequest $request,
        CreatePricingType $action
    ) {
        $data = $request->validated();

        $clientData = new PricingTypeData(
            name: $data['name'],
        );

        $pricingType = $action->execute($clientData);

        return response()->json([
            'message' => 'Pricing type created successfully',
            'pricing_type' => $pricingType
        ]);
    }
}
