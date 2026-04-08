<?php

namespace App\Http\Requests\Project;

use App\Concerns\ProfileValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:projects,name'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', 'string', 'in:Low,Medium,High'],
            'status_id' => ['nullable', 'exists:project_statuses,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['exists:services,id'],
            'clients' => ['required', 'array'],
            'clients.*' => ['exists:clients,id'],
            'professionals' => ['required', 'array'],
            'professionals.*' => ['exists:users,id'],
        ];
    }
}
