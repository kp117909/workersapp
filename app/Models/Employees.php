<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    public function titles()
    {
        return $this->hasMany(Titles::class, 'emp_no', 'emp_no');
    }

    public function salaries()
    {
        return $this->hasMany(Salaries::class, 'emp_no', 'emp_no');
    }

    public function departmentManager()
    {
        return $this->hasOne(DeptManager::class, 'emp_no', 'emp_no');
    }

    public function departmentEmployee()
    {
        return $this->hasOne(DeptEmp::class, 'emp_no', 'emp_no');
    }

    public function department()
    {
        if ($this->departmentManager) {
            return $this->departmentManager->belongsTo(Departments::class, 'dept_no', 'dept_no');
        } elseif ($this->departmentEmployee) {
            return $this->departmentEmployee->belongsTo(Departments::class, 'dept_no', 'dept_no');
        }

        return null;
    }

    public function getJob()
    {
        if ($this->departmentManager) {
            return 'Manager';
        } elseif ($this->departmentEmployee) {
            return 'Employee';
        }

        return null;
    }

    public function latestDepartmentEmployee()
    {
        return $this->hasOne(DeptEmp::class, 'emp_no', 'emp_no')
            ->latest('from_date');
    }

    public function latestDepartmentManager()
    {
        return $this->hasOne(DeptManager::class, 'emp_no', 'emp_no')
            ->latest('from_date');
    }

}
