<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('books', 'isbn')->ignore($bookId)
            ],
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'total_quantity' => 'required|integer|min:1',
            'available_quantity' => 'required|integer|min:0|lte:total_quantity'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'author.required' => 'O autor é obrigatório.',
            'isbn.unique' => 'Este ISBN já está cadastrado.',
            'publisher.required' => 'A editora é obrigatória.',
            'publication_year.required' => 'O ano de publicação é obrigatório.',
            'publication_year.integer' => 'O ano de publicação deve ser um número.',
            'publication_year.min' => 'O ano de publicação deve ser maior que 999.',
            'publication_year.max' => 'O ano de publicação não pode ser maior que ' . (date('Y') + 1) . '.',
            'total_quantity.required' => 'A quantidade total é obrigatória.',
            'total_quantity.min' => 'A quantidade total deve ser pelo menos 1.',
            'available_quantity.required' => 'A quantidade disponível é obrigatória.',
            'available_quantity.min' => 'A quantidade disponível não pode ser negativa.',
            'available_quantity.lte' => 'A quantidade disponível não pode ser maior que a quantidade total.'
        ];
    }
}
