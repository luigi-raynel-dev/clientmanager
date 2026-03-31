<?php

namespace App\Http\Controllers;

use App\Actions\PricingTypes\CreatePricingType;
use App\DTO\PricingType\PricingTypeData;
use App\Http\Requests\PricingType\StorePricingTypeRequest;
use Illuminate\Support\Facades\Redirect;

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

        return redirect()->back()->with('info', $pricingType->toArray());
    }
}
