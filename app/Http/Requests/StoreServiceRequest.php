<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name' . ($this->method() === 'PUT' ? ',' . $this->route('service')->id : ''),
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Service name is required.',
            'name.unique' => 'This service name already exists.',
            'name.max' => 'Service name must not exceed 255 characters.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'duration.required' => 'Duration is required.',
            'duration.integer' => 'Duration must be a whole number.',
            'duration.min' => 'Duration must be at least 15 minutes.',
            'description.max' => 'Description must not exceed 500 characters.',
        ];
    }
}
