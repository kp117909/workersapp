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
<h1>Exported Employees</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Title</th>
        <th>Salary</th>
        <th>Department</th>
        <th>Job</th>
        <th>Earned Money</th>
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
            <td>{{ $employee->titles->last()->title }}</td>
            <td>{{ $employee->salaries->last()->salary }} {{__('$')}}</td>
            <td>{{ $employee->department->dept_name  }}</td>
            <td>{{ $employee->getJob() }}</td>
            <td>{{ $employee->totalSalary }} {{__('$')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
