<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venue_id'       => ['required', 'integer', 'exists:venues,id'],
            'name'           => ['required', 'string', 'max:100'],
            'sport_type'     => ['required', 'in:futsal,badminton'],
            'description'    => ['nullable', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'status'         => ['required', 'in:available,maintenance,inactive'],
        ];
    }
}
