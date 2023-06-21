<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titles extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_no', 'emp_no');
    }
}
