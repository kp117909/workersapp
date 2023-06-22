<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function departmentEmployees()
    {
        return $this->hasMany(DeptEmp::class, 'emp_no', 'emp_no');
    }

    public function departmentManagers()
    {
        return $this->hasMany(DeptManager::class, 'emp_no', 'emp_no');
    }

    public function department()
    {
        if ($this->departmentManagers->isNotEmpty()) {
            return $this->departmentManagers->last()->belongsTo(Departments::class, 'dept_no', 'dept_no');
        } elseif ($this->departmentEmployees->isNotEmpty()) {
            return $this->departmentEmployees->last()->belongsTo(Departments::class, 'dept_no', 'dept_no');
        }

        return null;
    }

    public function getJob()
    {
        if ($this->departmentManagers->isNotEmpty()) {
            return 'Manager';
        } elseif ($this->departmentEmployees->isNotEmpty()) {
            return 'Employee';
        }

        return null;
    }

}
