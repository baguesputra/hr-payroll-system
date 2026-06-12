<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'position_id',
        'department_id',
        'nik',
        'employee_number',
        'full_name',
        'gender',
        'birth_date',
        'marital_status',
        'ptkp_status',
        'employment_status',
        'join_date',
        'resign_date',
        'phone',
        'address',
        'npwp',
        'bpjs_tk_number',
        'bpjs_kes_number',
        'bank_name',
        'bank_account',
        'photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date'  => 'date',
            'join_date'   => 'date',
            'resign_date' => 'date',
            'is_active'   => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['full_name', 'position_id', 'department_id', 'ptkp_status', 'employment_status', 'is_active'])
        ->logOnlyDirty();
}

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function salaryHistories(): HasMany
    {
        return $this->hasMany(EmployeeSalaryHistory::class);
    }

    public function positionHistories(): HasMany
    {
        return $this->hasMany(EmployeePositionHistory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function latestSalary(): HasOne
    {
        return $this->hasOne(EmployeeSalaryHistory::class)->latestOfMany('effective_date');
    }
}