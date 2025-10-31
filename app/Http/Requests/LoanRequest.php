<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assumindo que o middleware de autenticação já verifica o acesso
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'integer',
                'exists:students,id'
            ],
            'book_id' => [
                'required',
                'integer',
                'exists:books,id'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'É necessário selecionar um aluno.',
            'student_id.exists' => 'O aluno selecionado não existe.',
            'book_id.required' => 'É necessário selecionar um livro.',
            'book_id.exists' => 'O livro selecionado não existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'aluno',
            'book_id' => 'livro',
        ];
    }
}
