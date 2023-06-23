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
    </style>
</head>
<body>
<h1>{{__("Exported Employees")}}</h1>
<table>
    <thead>
    <tr>
        <th>{{__("Name")}}</th>
        <th>{{__("Title")}}</th>
        <th>{{__("Salary")}}</th>
        <th>{{__("Department")}}</th>
        <th>{{__("Job")}}</th>
        <th>{{__("Earned Money")}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
            <td>{{ $employee->titles->last()->title }}</td>
            <td>{{ $employee->salaries->last()->salary }} {{__('$')}}</td>
            <td>
                @if ($employee->departmentManagers->isNotEmpty())
                    {{ $employee->departmentManagers()->latest('to_date')->first()->department->dept_name }}
                @elseif ($employee->departmentEmployees->isNotEmpty())
                    {{ $employee->departmentEmployees()->latest('to_date')->first()->department->dept_name }}
                @endif
            </td>
            <td>{{ $employee->getJob() }}</td>
            <td>{{ $employee->totalSalary }} {{__('$')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
