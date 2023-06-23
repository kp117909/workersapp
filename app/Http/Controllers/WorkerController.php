<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Employees;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    function index(Request $request){

        $query = Employees::query()->with(['titles', 'salaries']);
        $department = Departments::all();

        $selectedDepartments = $request->input('departments', []);

        session(['selectedDepartments' => $selectedDepartments]);

        $selectedExports = session('selectedExports', []);

        $selectedExportsCount = count($selectedExports);

        if (!$request->has('filter-employed') || !$request->has('filter-unemployed')) {
            if ($request->has('filter-employed')) {
                $query->where(function ($query) {
                    $query->whereHas('departmentEmployees', function ($query) {
                        $query->where('to_date', '9999-01-01');
                    })->orWhereHas('departmentManagers', function ($query) {
                        $query->where('to_date', '9999-01-01');
                    });
                });
            }

            if ($request->has('filter-unemployed')) {
                $query->where(function ($query) {
                    $query->whereHas('departmentEmployees', function ($query) {
                        $query->where('to_date', '!=', '9999-01-01');
                    })->orWhereHas('departmentManagers', function ($query) {
                        $query->where('to_date', '!=', '9999-01-01');
                    });
                });
            }
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
                        ->where('dept_emp.to_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(to_date)')
                                ->from('dept_emp')
                                ->whereColumn('dept_emp.emp_no', 'employees.emp_no');
                        });
                })
                ->leftJoin('departments', 'dept_emp.dept_no', '=', 'departments.dept_no')
                ->leftJoin('dept_manager', function ($join) {
                    $join->on('employees.emp_no', '=', 'dept_manager.emp_no')
                        ->where('dept_manager.to_date', function ($subQuery) {
                            $subQuery->selectRaw('MAX(to_date)')
                                ->from('dept_manager')
                                ->whereColumn('dept_manager.emp_no', 'employees.emp_no');
                        });
                })
                ->whereIn('departments.dept_no', $selectedDepartments);
        }

        $query = $query->paginate(10);

        return view('welcome', ['employees' => $query, 'departments' => $department, 'selectedExportsCount' => $selectedExportsCount]);

    }

    function generatePDF(Request $request)
    {
        $selectedExports = session('selectedExports', []);

        $employees = Employees::whereIn('emp_no', $selectedExports)->get();

        foreach ($employees as $employee) {
            $totalSalary = $employee->salaries()->sum('salary');
            $employee->totalSalary = $totalSalary;
        }

        $dompdf = new Dompdf();

        $html = view('employees-pdf', compact('employees'))->render();

        $dompdf->loadHtml($html);

        $dompdf->render();

        $fileName = 'exported_employees.pdf';


        $pdfContent = $dompdf->output();

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

    }

    function generatePDFEmployee(Request $request)
    {
        $id = $request->id;

        $employee = Employees::where('emp_no', $id)->first();

        $dompdf = new Dompdf();

        $html = view('employee-pdf', compact('employee'))->render();

        $dompdf->loadHtml($html);

        $dompdf->render();

        $fileName = 'exported_employee.pdf';


        $pdfContent = $dompdf->output();

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

    }

    public function saveSelectedExports(Request $request): \Illuminate\Http\JsonResponse
    {
        if($request->action == "add"){
            $selectedExports = $request->input('selectedExports', []);

            $currentSelectedExports = session('selectedExports', []);

            $newSelectedExports = array_merge($currentSelectedExports, array_diff($selectedExports, $currentSelectedExports));
            session(['selectedExports' => $newSelectedExports]);
        }else{
            $value = $request->input('value');

            $selectedExports = session('selectedExports', []);

            $key = array_search($value, $selectedExports);
            if ($key !== false) {
                unset($selectedExports[$key]);
            }
            session(['selectedExports' => $selectedExports]);
        }


        return response()->json(['success' => true]);
    }

    public function clearSelectedExports(): \Illuminate\Http\JsonResponse
    {
        session()->forget('selectedExports');

        return response()->json(['success' => true]);
    }

    public function showProfile($emp_no)
    {
        $employee = Employees::where('emp_no', $emp_no)->first();

        return view('profile', compact('employee'));
    }

}
