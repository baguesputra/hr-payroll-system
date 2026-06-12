<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employee.update');
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'nik'               => ['sometimes', 'string', 'size:16', "unique:employees,nik,{$employeeId}"],
            'full_name'         => ['sometimes', 'string', 'max:255'],
            'gender'            => ['sometimes', 'in:male,female'],
            'birth_date'        => ['nullable', 'date', 'before:today'],
            'marital_status'    => ['sometimes', 'in:single,married,divorced,widowed'],
            'ptkp_status'       => ['sometimes', 'in:TK/0,K/0,K/1,K/2,K/3'],
            'employment_status' => ['sometimes', 'in:permanent,contract,probation'],
            'position_id'       => ['sometimes', 'exists:positions,id'],
            'department_id'     => ['sometimes', 'exists:departments,id'],
            'join_date'         => ['sometimes', 'date'],
            'resign_date'       => ['nullable', 'date', 'after:join_date'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string'],
            'npwp'              => ['nullable', 'string', 'max:20'],
            'bpjs_tk_number'    => ['nullable', 'string'],
            'bpjs_kes_number'   => ['nullable', 'string'],
            'bank_name'         => ['nullable', 'string'],
            'bank_account'      => ['nullable', 'string'],
        ];
    }
}