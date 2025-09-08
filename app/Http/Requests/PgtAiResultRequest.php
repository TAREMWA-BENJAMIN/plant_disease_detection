<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PgtAiResultRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'plant_image' => ['required', 'string'],
            'plant_name' => ['required', 'string', 'max:255'],
            'disease_name' => ['nullable', 'string', 'max:255'],
            'disease_details' => ['nullable', 'string'],
            'suggested_solution' => ['nullable', 'string'],
            'prevention_tips' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plant_image.required' => 'The plant image is required.',
            'plant_name.required' => 'The plant name is required.',
            'plant_name.max' => 'The plant name may not be greater than 255 characters.',
            'disease_name.max' => 'The disease name may not be greater than 255 characters.',
        ];
    }
} 