<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>{{__('Workers')}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="antialiased">
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-4 mb-5 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card p-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{__('Filters')}}</h5>
                </div>
                <form id="filter-form" action="{{ route('index') }}" method="get">
                    <div class = 'row'>
                        <div class="text-center mt-3">
                            <p>{{__("Checkbox for Currently Employed")}}</p>
                        </div>
                        <div class="form-check ml3 mb-3">
                            <label class="form-check-label" for="filter">
                                {{__('Employed')}}
                            </label>
                            <input class="form-check-input" type="checkbox" name="filter" id="filter" value="1" {{ old('filter', request()->has('filter')) ? 'checked' : '' }}>
                        </div>
                        <div class="row pb-3">
                            <div class="text-center">
                                <p>{{__("Search Input by Name")}}</p>
                            </div>
                            <div class="col-md-8 text-center mb-2">
                                <input type="text" name="search" class="form-control" value="{{ old('search', request()->get('search')) }}" placeholder="{{__("Search Name")}}">
                            </div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-primary ml-2">{{__("Apply")}} </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center">
                                <p>{{__("Search Input by Salary")}}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <input type="number" name="valueLow" class="form-control mb-2" value="{{ old('valueLow', request()->get('valueLow')) }}" placeholder="{{__("Min")}}">
                            </div>
                            <div class="col-md-4 text-center ">
                                <input type="number" name="valueHigh" class="form-control mb-2" value="{{ old('valueHigh', request()->get('valueHigh')) }}" placeholder="{{__("Max")}}">
                            </div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-primary ml-2">{{__("Apply")}} </button>
                            </div>
                        </div>
                        <div class = "row">
                            <div class="text-center mt-3">
                                <p>{{__("Checkbox for Gender")}}</p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" id="filterMale" type="checkbox" name="filterMale"  {{ old('filterMale', request()->has('filterMale')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">
                                        {{__('Male')}}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="filterFemale" type="checkbox" name="filterFemale" {{ old('filterFemale', request()->has('filterFemale')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">
                                        {{__('Female')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class="text-center mt-3">
                                <p>{{__("Checkbox for Departament")}}</p>
                            </div>
                            <div class="col-md-12">
                                <div class="departments_wrapper text-center">
                                    <select class="departments_select form-control" name="departments[]" multiple="multiple">
                                        @foreach($departaments as $departament)
                                            <option value="{{ $departament->dept_no }}">{{ $departament->dept_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary ml-2">{{__("Apply")}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12  col-lg-8 mb-3 mb-lg-5">
            <div class="overflow-hidden card table-nowrap table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{__('Workers')}}</h5>
                    <a href="#!" class="btn btn-light btn-sm">{{__('View All')}}</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="small text-uppercase bg-body text-muted">
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Tittle')}}</th>
                            <th>{{__('Salary')}}</th>
                            <th>{{__('Department')}}</th>
                            <th>{{__('Job')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($employee->gender === "M")
                                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="avatar sm rounded-pill me-3 flex-shrink-0" alt="Male">
                                        @else
                                            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="avatar sm rounded-pill me-3 flex-shrink-0" alt="Female">
                                        @endif
                                        <div>
                                            <div class="h6 mb-0 lh-1"> {{$employee->first_name}} {{$employee->last_name}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->titles->last()->title }}</td>
                                <td>{{ $employee->salaries->last()->salary }} $</td>
                                <td>{{ $employee->department->dept_name }}</td>
                                <td>{{ $employee->getJob() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $employees->appends([
                        'filter' => request()->input('filter') ,
                        'search' =>request()->input('search'),
                        'valueLow' =>request()->input('valueLow'),
                        'valueHigh' =>request()->input('valueHigh'),
                        'filterMale'=> request()->input('filterMale'),
                        'filterFemale' =>request()->input('filterFemale'),
                        'departments' => request()->input('departments')
                    ])->onEachSide(1)->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</html>
