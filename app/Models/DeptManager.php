<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptManager extends Model
{
    use HasFactory;

    protected $table = 'dept_manager';

    public function department()
    {
        return $this->hasMany(Departments::class, 'dept_no', 'dept_no');
    }

    public function employee()
    {
        return $this->hasMany(Employees::class, 'emp_no', 'emp_no');
    }

}
