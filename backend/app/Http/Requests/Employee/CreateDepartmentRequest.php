<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.create');
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'code'       => ['required', 'string', 'max:10', 'unique:departments,code'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'is_active'  => ['boolean'],
        ];
    }
}