<?php

namespace App\Http\Requests;

use App\Models\Bungalow;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Bungalow $bungalow */
        $bungalow = $this->route('bungalow');

        return $this->user() !== null && $bungalow->status === 'available';
    }

    public function rules(): array
    {
        /** @var Bungalow $bungalow */
        $bungalow = $this->route('bungalow');

        return [
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:'.$bungalow->capacity],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
