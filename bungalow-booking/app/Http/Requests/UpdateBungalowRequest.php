<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBungalowRequest extends FormRequest
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
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'capacity' => ['required', 'integer', 'min:1'],
            'bedrooms' => ['required', 'integer', 'min:1'],
            'bathrooms' => ['required', 'integer', 'min:1'],
            'nightly_rate' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'maintenance', 'hidden'])],
            'featured' => ['nullable', 'boolean'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'amenity_ids' => ['nullable', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
            'photos' => ['nullable', 'array', 'max:6'],
            'photos.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'primary_image_id' => ['nullable', 'integer', 'exists:bungalow_images,id'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:bungalow_images,id'],
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
