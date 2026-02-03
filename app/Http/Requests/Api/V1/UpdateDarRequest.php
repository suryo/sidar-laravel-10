<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dar_date' => 'sometimes|date|before_or_equal:today',
            'activity' => 'sometimes|string|min:10|max:1000',
            'result' => 'sometimes|string|min:10|max:1000',
            'plan' => 'sometimes|string|min:10|max:1000',
            'tag' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'dar_date.date' => 'DAR date must be a valid date',
            'dar_date.before_or_equal' => 'DAR date cannot be in the future',
            'activity.min' => 'Activity description must be at least 10 characters',
            'activity.max' => 'Activity description cannot exceed 1000 characters',
            'result.min' => 'Result description must be at least 10 characters',
            'result.max' => 'Result description cannot exceed 1000 characters',
            'plan.min' => 'Plan description must be at least 10 characters',
            'plan.max' => 'Plan description cannot exceed 1000 characters',
        ];
    }
}
