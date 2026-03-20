<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Concerns\ProfileValidationRules;
use App\Models\Service;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $service_id = request()->route('id');

        return [
            'name' => ['required', 'string', 'max:255',  $service_id === null
                ? Rule::unique(Service::class)
                : Rule::unique(Service::class)->ignore($service_id),],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'price_type' => ['nullable', 'string', 'in:fixed,unit,hourly,daily'],
            'estimated_duration_minutes' => ['nullable', 'numeric', 'min:0'],
            'estimated_duration_type' => ['nullable', 'string', 'in:minutes,hours,days,weeks,months'],
            'other_price_type' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
