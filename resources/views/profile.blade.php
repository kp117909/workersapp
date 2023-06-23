
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/3133d360bd.js" crossorigin="anonyous"></script>
    <title>{{__('Workers')}}</title>
    @vite(['resources/css/profile.css', 'resources/js/client.js'])
</head>

<body>
    <div class="container emp-profile">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-img">
                        @if($employee->gender === "M")
                            <img  src="https://bootdey.com/img/Content/avatar/avatar1.png" class="avatar sm rounded-pill me-3 flex-shrink-0" alt="Male">
                        @else
                            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="avatar sm rounded-pill me-3 flex-shrink-0" alt="Female">
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </h5>
                        <h6>
                            @if ($employee->departmentManagers->isNotEmpty())
                                {{ $employee->departmentManagers()->latest('to_date')->first()->department->dept_name }}
                            @elseif ($employee->departmentEmployees->isNotEmpty())
                                {{ $employee->departmentEmployees()->latest('to_date')->first()->department->dept_name }}
                            @endif
                        </h6>
                        <p class="proile-rating">{{__("RANK")}} : <span>{{ $employee->getJob()}}</span></p>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Timeline</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" id = "go-back" class="btn btn-primary">{{__("Back")}}</button>

                    <button type="button" id = "export-data" data-id = "{{$employee->emp_no}}" data-url = "{{route('export-employee')}}" class="btn btn-warning">{{__("Export Data")}}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-work">
                        <p>{{__("WORKER PROFILE")}}</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content profile-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("User Id")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p> {{ $employee->emp_no }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Name")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Gender")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $employee->gender }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Birth Date")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $employee->birth_date }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Salary")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $employee->salaries->last()->salary }} <b style = "color:green;">{{__("$")}}</b></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Title")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{$employee->titles()->latest('to_date')->first()->title }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Hire Date")}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $employee->hire_date }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Department")}}</label>
                                </div>
                                <div class="col-md-6">
                                    @if ($employee->departmentManagers->isNotEmpty())
                                        <p> {{ $employee->departmentManagers()->latest('to_date')->first()->department->dept_name }}</p>
                                    @elseif ($employee->departmentEmployees->isNotEmpty())
                                        <p>{{ $employee->departmentEmployees()->latest('to_date')->first()->department->dept_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Titles")}}</label>
                                    @foreach($employee->titles->reverse() as $title)
                                        <p class = "m-3">{{$title->from_date}} - {{$title->to_date}} <i class="fa-solid fa-right-long"></i> {{$title->title}}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Deparments")}}</label>
                                    @foreach($employee->departmentEmployees as $deparment)
                                        <p class = "m-3">{{$deparment->from_date}} - {{$deparment->to_date}} <i class="fa-solid fa-right-long"></i> {{$deparment->department->dept_name}}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__("Salary")}}</label>
                                    @foreach($employee->salaries as $salary)
                                        <p class ="m-3">{{$salary->from_date}} - {{$salary->to_date}} <i class="fa-solid fa-right-long"></i> {{$salary->salary}} <b style = "color:green">{{__('$')}}</b></p>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>

</html>
