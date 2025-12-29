<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')->id;

        return [
            'title' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|size:13|unique:books,isbn,' . $bookId,
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'total_copies' => 'sometimes|required|integer|min:1',
            'available_copies' => 'nullable|integer|min:0',
            'author_ids' => 'sometimes|array|min:1',
            'author_ids.*' => 'exists:authors,id',
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
