<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:readers,email',
            'phone' => 'nullable|string|max:20',
            'membership_date' => 'required|date',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя читателя обязательно',
            'email.required' => 'Email обязателен',
            'email.email' => 'Некорректный формат email',
            'email.unique' => 'Читатель с таким email уже существует',
            'membership_date.required' => 'Дата регистрации обязательна',
            'membership_date.date' => 'Дата регистрации должна быть в формате даты',
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
