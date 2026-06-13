<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.update');
    }

    public function rules(): array
    {
        return [
            'position_id'    => ['required', 'exists:positions,id'],
            'department_id'  => ['required', 'exists:departments,id'],
            'effective_date' => ['required', 'date'],
            'reason'         => ['required', 'string', 'max:255'],
        ];
    }
}