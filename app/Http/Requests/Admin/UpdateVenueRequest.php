<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'address'      => ['required', 'string'],
            'description'  => ['nullable', 'string'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'status'       => ['required', 'in:active,inactive'],
        ];
    }
}
