<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequestRequest extends FormRequest
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
            'student_id' => [
                'required',
                'exists:branch_A.students,id',
                'exists:branch_B.students,id',
                Rule::unique('branch_A.requests', 'student_id')->where('status', 'pending'),
                Rule::unique('branch_B.requests', 'student_id')->where('status', 'pending'),
            ],
            'to_branch_id' => ['required', 'exists:branch_A.branches,id',
            'exists:branch_B.branches,id'],
        ];
    }
}