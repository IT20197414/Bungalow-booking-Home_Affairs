<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBungalowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'check_in_time' => $this->normalizeTime($this->input('check_in_time')),
            'check_out_time' => $this->normalizeTime($this->input('check_out_time')),
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1'],
            'bedrooms' => ['required', 'integer', 'min:1'],
            'bathrooms' => ['required', 'integer', 'min:1'],
            'nightly_rate' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'maintenance', 'hidden'])],
            'featured' => ['nullable', 'boolean'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();
        $data['featured'] = $this->boolean('featured');

        return $data;
    }

    private function normalizeTime(?string $time): ?string
    {
        if ($time === null || trim($time) === '') {
            return null;
        }

        return substr($time, 0, 5);
    }
}
