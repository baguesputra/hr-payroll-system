<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.update');
    }

    public function rules(): array
    {
        return [
            'base_salary'    => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'reason'         => ['required', 'string', 'max:255'],
        ];
    }
}