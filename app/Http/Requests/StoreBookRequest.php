<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|size:13|unique:books,isbn',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'nullable|integer|min:0',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Название книги обязательно',
            'isbn.required' => 'ISBN обязателен',
            'isbn.size' => 'ISBN должен содержать 13 символов',
            'isbn.unique' => 'Книга с таким ISBN уже существует',
            'total_copies.min' => 'Общее количество копий должно быть не менее 1',
            'author_ids.required' => 'Необходимо указать хотя бы одного автора',
            'author_ids.*.exists' => 'Выбранный автор не существует',
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
