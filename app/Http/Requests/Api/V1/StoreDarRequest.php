<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dar_date' => 'required|date|before_or_equal:today',
            'activity' => 'required|string|min:10|max:1000',
            'result' => 'required|string|min:10|max:1000',
            'plan' => 'required|string|min:10|max:1000',
            'tag' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'dar_date.required' => 'DAR date is required',
            'dar_date.date' => 'DAR date must be a valid date',
            'dar_date.before_or_equal' => 'DAR date cannot be in the future',
            'activity.required' => 'Activity description is required',
            'activity.min' => 'Activity description must be at least 10 characters',
            'activity.max' => 'Activity description cannot exceed 1000 characters',
            'result.required' => 'Result description is required',
            'result.min' => 'Result description must be at least 10 characters',
            'result.max' => 'Result description cannot exceed 1000 characters',
            'plan.required' => 'Plan description is required',
            'plan.min' => 'Plan description must be at least 10 characters',
            'plan.max' => 'Plan description cannot exceed 1000 characters',
        ];
    }
}
