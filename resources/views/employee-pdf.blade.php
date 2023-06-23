<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exported Employees</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }

        h4{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{__("Exported Employee")}}</h1>

    <hr>

    <h2> {{__("Name:")}} {{ $employee->first_name }} {{ $employee->last_name }}</h2>

    <h3> {{__("Title:")}} {{ $employee->titles->last()->title }}</h3>

    <h3> {{__("Salary:")}} {{ $employee->salaries->last()->salary }} {{__('$')}}</h3>

    <h3> {{__("Department:")}}
        @if ($employee->departmentManagers->isNotEmpty())
            {{ $employee->departmentManagers()->latest('to_date')->first()->department->dept_name }}
        @elseif ($employee->departmentEmployees->isNotEmpty())
            {{ $employee->departmentEmployees()->latest('to_date')->first()->department->dept_name }}
        @endif </h3>

    <h3> {{__("Rank:")}} {{ $employee->getJob() }}</h3>
    <hr>

    <h4 > {{__("History:")}}</h4>

    <table class="table mb-0">
        <thead class="small text-uppercase bg-body text-muted">
        <tr>
            <th>Date To</th>
            <th>Date From</th>
            <th>Title</th>
            <th>Department</th>
            <th>Salary</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employee->salaries->reverse() as $salary)
            @php
                $matchingTitles = $employee->titles->filter(function ($title) use ($salary) {
                    return $title->from_date <= $salary->to_date && $title->to_date >= $salary->from_date;
                });

                $matchingDepartments = $employee->departmentEmployees->filter(function ($department) use ($salary) {
                    return $department->from_date <= $salary->to_date && $department->to_date >= $salary->from_date;
                });
            @endphp
            @foreach($matchingTitles->reverse() as $title)
                @foreach($matchingDepartments->reverse() as $department)
                    <tr>
                        <td>
                            @if($salary->to_date === '9999-01-01')
                                {{ __("Now") }}
                            @else
                                {{ $salary->to_date }}
                            @endif
                        </td>
                        <td>
                            {{ $salary->from_date }}
                        </td>
                        <td>
                            {{ $title->title }}
                        </td>
                        <td>
                            {{ $department->department->dept_name }}
                        </td>
                        <td>
                            {{ $salary->salary }} <b style="color:green">{{ __('$') }}</b>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>
    <p class = "text-right m-2">{{__("Total Earned Salary")}} {{$employee->salaries()->sum('salary')}} <b style="color:green">{{ __('$') }}</b></p>
</body>
</html>

