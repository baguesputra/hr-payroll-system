<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.create');
    }

    public function rules(): array
    {
        return [
            'nik'               => ['required', 'string', 'size:16', 'unique:employees,nik'],
            'employee_number'   => ['required', 'string', 'unique:employees,employee_number'],
            'full_name'         => ['required', 'string', 'max:255'],
            'gender'            => ['required', 'in:male,female'],
            'birth_date'        => ['nullable', 'date', 'before:today'],
            'marital_status'    => ['required', 'in:single,married,divorced,widowed'],
            'ptkp_status'       => ['required', 'in:TK/0,K/0,K/1,K/2,K/3'],
            'employment_status' => ['required', 'in:permanent,contract,probation'],
            'position_id'       => ['required', 'exists:positions,id'],
            'department_id'     => ['required', 'exists:departments,id'],
            'join_date'         => ['required', 'date'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string'],
            'npwp'              => ['nullable', 'string', 'max:20'],
            'bpjs_tk_number'    => ['nullable', 'string'],
            'bpjs_kes_number'   => ['nullable', 'string'],
            'bank_name'         => ['nullable', 'string'],
            'bank_account'      => ['nullable', 'string'],
            'base_salary'       => ['required', 'numeric', 'min:0'],
        ];
    }
}