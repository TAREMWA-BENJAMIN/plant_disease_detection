<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'phone_number' => 'nullable|string|unique:users,phone_number,' . $userId,
            'email' => 'sometimes|required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string',
            'user_type' => 'in:admin,user',
            'region_id' => 'required|exists:regions,id',
            'district_id' => [
                'nullable',
                'exists:districts,id',
                function ($attribute, $value, $fail) {
                    $regionId = $this->input('region_id');
                    if ($value && $regionId) {
                        $district = \App\Models\District::find($value);
                        if ($district && $district->region_id != $regionId) {
                            $fail('The selected district does not belong to the selected region.');
                        }
                    }
                },
            ],
            'title' => 'nullable|string',
            'specialization' => 'nullable|string',
            'is_verified' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'last_login' => 'nullable|date',
            'organization' => 'nullable|string',
        ];
    }
}
