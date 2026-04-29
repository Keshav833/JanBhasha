<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganisationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Checkbox sends "1" or nothing — normalize to boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['nullable', 'email', 'max:255'],
            'website'            => ['nullable', 'url', 'max:255'],
            'department'         => ['nullable', 'string', 'max:255'],
            'is_active'          => ['boolean'],
            'monthly_char_limit' => ['required', 'integer', 'min:1000'],
        ];
    }
}
