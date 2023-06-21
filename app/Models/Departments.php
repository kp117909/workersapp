<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;
    public function deptEmp()
    {
        return $this->hasMany(DeptEmp::class, 'dept_no', 'dept_no');
    }

    public function deptManager()
    {
        return $this->hasMany(DeptManager::class, 'dept_no', 'dept_no');
    }
}
