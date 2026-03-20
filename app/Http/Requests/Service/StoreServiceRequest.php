<?php

namespace App\Http\Requests\Service;

use App\Concerns\ProfileValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreServiceRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:services,name'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'price_type' => ['nullable', 'string', 'in:fixed,unit,hourly,daily'],
            'estimated_duration_minutes' => ['nullable', 'numeric', 'min:0'],
            'other_price_type' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
