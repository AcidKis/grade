<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'reader_id' => ['required', 'integer', 'exists:readers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'ID книги обязателен',
            'book_id.exists' => 'Книга не найдена',
            'reader_id.required' => 'ID читателя обязателен',
            'reader_id.exists' => 'Читатель не найден',
        ];
    }
}