<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestStatusRequest extends FormRequest
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
            'status' => ['required', 'in:accepted,rejected'],
            'requestId' => ['required',  'exists:branch_A.requests,id','exists:branch_B.requests,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'status.in' =>'in:accepted,rejected',
        ];
    }
}
