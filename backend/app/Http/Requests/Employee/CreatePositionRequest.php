<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.create');
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:10', 'unique:positions,code'],
            'level'         => ['integer', 'min:1', 'max:10'],
            'is_active'     => ['boolean'],
        ];
    }
}