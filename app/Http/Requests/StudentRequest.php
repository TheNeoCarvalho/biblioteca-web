<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $studentId = $this->route('student') ? $this->route('student')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'registration' => 'required|string|max:50|unique:students,registration,' . $studentId,
            'course' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
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
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'registration.required' => 'A matrícula é obrigatória.',
            'registration.unique' => 'Esta matrícula já está cadastrada.',
            'course.required' => 'O curso é obrigatório.',
            'grade.required' => 'A série é obrigatória.',
        ];
    }
}
