<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:branch_A.students,name', 'unique:branch_B.students,name'],
            'grade' => ['required', 'string', 'in:A,B,C,D,F'],
        ];
    }

    public function messages(): array
    {
        return [
            'grade.in' => 'A,B,C,D,F',
        ];
    }
}
