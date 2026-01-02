<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ActivityStoreRequest extends FormRequest
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
            'specie_id' => 'required|exists:species,id',
            'lure_id' => 'required|exists:lures,id',
            'size' => 'required|integer|min:1|max:200',
            'weight' => 'required|numeric|min:0.1|max:50',
        ];
    }
}
