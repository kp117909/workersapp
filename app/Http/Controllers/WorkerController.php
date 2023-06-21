<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class WorkerController extends Controller
{
    function index(Request $request){

        $query = Employees::query()->with(['titles', 'salaries', 'latestDepartmentManager.department', 'latestDepartmentEmployee.department']);

        $departaments = Departments::all();

        if ($request->has('filter')) {
            $query->where(function ($query) {
                $query->whereHas('departmentEmployee', function ($query) {
                    $query->where('to_date', '9999-01-01');
                })->orWhereHas('departmentManager', function ($query) {
                    $query->where('to_date', '9999-01-01');
                });
            });
        }

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%$searchTerm%'")
                    ->orWhere('first_name', 'like', "%$searchTerm%")
                    ->orWhere('last_name', 'like', "%$searchTerm%");
            });
        }

        if ($request->has('valueLow')) {
            $searchTerm = $request->get('valueLow');
            if ($searchTerm !== null) {
                $query->join('salaries AS s', function ($join) {
                    $join->on('employees.emp_no', '=', 's.emp_no')
                        ->where('s.from_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(from_date)')
                                ->from('salaries')
                                ->whereColumn('emp_no', 'employees.emp_no');
                        });
                })->where('s.salary', '>=', $searchTerm);
            }
        }

        if ($request->has('valueHigh')) {
            $searchTerm = $request->get('valueHigh');
            if ($searchTerm !== null) {
                $query->join('salaries AS ss', function ($join) {
                    $join->on('employees.emp_no', '=', 'ss.emp_no')
                        ->where('ss.from_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(from_date)')
                                ->from('salaries')
                                ->whereColumn('emp_no', 'employees.emp_no');
                        });
                })->where('s.salary', '<=', $searchTerm);
            }
        }


        if (!$request->has('filterMale') || !$request->has('filterFemale')) {
            if ($request->has('filterMale')) {
                $query->where('gender', 'M');
            }
            if ($request->has('filterFemale')) {
                $query->where('gender', 'F');
            }
        }



        if ($request->has('departments')) {
            $selectedDepartments = $request->input('departments');

            $query->select('employees.*', 'departments.dept_no')
                ->leftJoin('dept_emp', function ($join) {
                    $join->on('employees.emp_no', '=', 'dept_emp.emp_no')
                        ->where('dept_emp.from_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(from_date)')
                                ->from('dept_emp')
                                ->whereColumn('dept_emp.emp_no', 'employees.emp_no');
                        });
                })
                ->leftJoin('departments', 'dept_emp.dept_no', '=', 'departments.dept_no')
                ->leftJoin('dept_manager', function ($join) {
                    $join->on('employees.emp_no', '=', 'dept_manager.emp_no')
                        ->where('dept_manager.from_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(from_date)')
                                ->from('dept_manager')
                                ->whereColumn('dept_manager.emp_no', 'employees.emp_no');
                        });
                })
                ->whereIn('departments.dept_no', $selectedDepartments);
        }

        $query = $query->paginate(10);


        return view('welcome', ['employees' => $query, 'departaments' => $departaments]);

    }


}
