<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class MapStoreRequest extends FormRequest
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
            'latitude' => 'required|decimal:7',
            'longitude' => 'required|decimal:7',
            'name' => 'required|string|min:3|max:100',
            'license' => 'string|min:3|max:100|nullable',
            'url' => 'nullable|url',
            'species' => 'nullable|array',
            'species.*' => 'exists:species,id',
            'environement' => 'nullable|array',
            'environement.*' => 'string',
            'equipments' => 'nullable|array',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'is_public' => 'required|boolean',
        ];
    }
}
