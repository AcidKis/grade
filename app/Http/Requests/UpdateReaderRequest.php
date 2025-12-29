<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $readerId = $this->route('reader')->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:readers,email,' . $readerId,
            'phone' => 'nullable|string|max:20',
            'membership_date' => 'sometimes|required|date',
            'is_active' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
