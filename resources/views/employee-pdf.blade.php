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

    <h4 > {{__("History Of Salary:")}}</h4>
    <table>
        <thead>
        <tr>
            <th>{{__("Date From")}}</th>
            <th>{{__("Date To")}}</th>
            <th>{{__("Salary")}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employee->salaries->reverse() as $salary)
            <tr>
                <td>{{$salary->from_date}}</td>
                <td>{{$salary->to_date}}</td>
                <td>{{$salary->salary}} <b style = "color:green">{{__('$')}}</b></td>
            </tr>
        </tbody>
        @endforeach
    </table>

    <h4 > {{__("History Of Titles:")}}</h4>
    <table>
        <thead>
        <tr>
            <th>{{__("Date From")}}</th>
            <th>{{__("Date To")}}</th>
            <th>{{__("Title")}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employee->titles as $title)
            <tr>
                <td>{{$title->from_date}}</td>
                <td>{{$title->to_date}}</td>
                <td>{{$title->title}}</b></td>
            </tr>
        </tbody>
        @endforeach
    </table>

    <h4 > {{__("History Of Departments:")}}</h4>
    <table>
        <thead>
        <tr>
            <th>{{__("Date From")}}</th>
            <th>{{__("Date To")}}</th>
            <th>{{__("Department Name")}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employee->departmentEmployees as $deparment)
            <tr>
                <td>{{$deparment->from_date}}</td>
                <td>{{$deparment->to_date}}</td>
                <td>{{$deparment->department->dept_name}}</td>
            </tr>
        </tbody>
        @endforeach
    </table>
</body>
</html>

