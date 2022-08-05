@extends('backpack::layout')

@section('header')

@endsection

@section('after_styles')
  <style>
    @media only screen and (max-width: 768px) {
      #btnAddEnrollment {
        width: 100%;
      }
    }
    .department-column{
      margin-bottom:50px !important;
    }
  </style>
@endsection

@section('content')

<!-- HEADER -->
  <div class="row" style="padding: 15px;">        
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group">
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
          </li>
          <li>
            <a class="text-capitalize active">{{ $crud->entity_name_plural }}</a>
          </li>
        </ol>
      </section>

      <!-- HEADER TITLE -->
      <h1 class="smo-content-title">
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      </h1>

      @if(!request()->school_year_id && !request()->department || !request()->school_year_id)

      @else
        <!-- SEARCH BOX -->
        <search-enrolled :school_year_id="'{{ request()->school_year_id }}'"></search-enrolled>
      @endif

    </div>
        
  </div>
<!-- HEADER END -->


  <!-- Default box -->
  <div class="row">
     <!-- DEPARTMENTS START -->
    <div class="department-column col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="info-box shadow">
        <div class="box-body">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>
              Select a {{request()->school_year_id ? 'Department' : 'School Year'}} to view list.
            </h4>
            <hr>
          </div>

          <!-- THE ACTUAL CONTENT -->
          <div>
            @if(!request()->school_year_id && !request()->department || !request()->school_year_id)
              @if($schoolYears)
                @if(count($schoolYears)>0)
                  @foreach( $schoolYears as $key => $schoolYear)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                      <div class="small-box bg-blue">
                        <div class="inner">
                          <h3>{{ $schoolYear->schoolYear }}</h3>

                          <p>Total Enrollments: {{ count($schoolYear->enrollment_enrolled) }}</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ asset($crud->route.'?school_year_id='.$schoolYear->id) }}" class="small-box-footer" style="padding: 10px;">
                          View List <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                      <div class="box-body">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available data found.
                          </h4>
                      </div>
                    </div>
                  </div>
                @endif
                @else
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                      <div class="box-body">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available data found.
                          </h4>
                      </div>
                    </div>
                  </div>
              @endif
            @else
              @if($departments)
                @if(count($departments)>0)
                  @foreach($departments as $key => $sy_department)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                      <div class="small-box bg-blue" style="border-radius: 5px;">
                        <div class="inner">
                          <h3> 
                            {{ 
                              $sy_department->enrollment_enrolled
                                ->where('school_year_id', request()->school_year_id)
                                ->count()
                            }}
                          </h3>

                          <p>{{ $sy_department->name }}</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ asset($crud->route.'?school_year_id='.request()->school_year_id.'&department='.$sy_department->id) }}" class="small-box-footer" style="padding: 10px;">
                          More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                      <div class="box-body">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available data found.
                          </h4>
                      </div>
                    </div>
                  </div>
                @endif
                @else
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                      <div class="box-body">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available data found.
                          </h4>
                      </div>
                    </div>
                  </div>
              @endif
            @endif
          </div>
          
        </div>
      </div>
    </div>
    <!-- DEPARTMENTS END -->

    <!-- STATISTICS START -->
    <div class="text-center">
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollment Statistics</span>
      </h1>
      <h5>Enrolment Data as of {{\Carbon\Carbon::today()->format('m-d-Y') }}</h5>
    </div>
   
    <!-- START Default box -->
    <div class="first-canvas" style="display:none; margin-top:40px;">
        <!-- START Annual Enrollment (Over-all) -->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <div class="info-box shadow">
            <div class="box-body">
              <div id="canvas-holder" style="padding:20px;">
                Annual Enrollments (Over-all)
                <canvas id="annual_enrollment_lineChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!--  END Annual Enrollment (Over-all) -->
        <!-- START DEPARTMENT PER YEAR -->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <div class="info-box shadow">
            <div class="box-body">
              <div id="canvas-holder" style="padding:20px;">
                Annual Enrollment (Per Department)
                <canvas id="department-per-year-chart"></canvas>
              </div>
            </div>
          </div>
        </div>
      <!-- END DEPARTMENT PER YEAR -->
        <!-- START DEPARTMENT POPULATION OF CURRENT SCHOOL YEAR -->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <div class="info-box shadow">
            <div class="box-body">
              <div id="canvas-holder" style="padding:20px;">
              <canvas id="chart-area"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!-- END DEPARTMENT POPULATION OF CURRENT SCHOOL YEAR -->
        @if($enrollments)
          @if(count($enrollments)>0)
            @foreach($enrollments->groupBy('department_id') as $key => $department)
              <!-- START DEPARTMENT POPULATION OF CURRENT SCHOOL YEAR -->
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <div class="info-box shadow">
                <div class="box-body">
                  <div id="canvas-holder" style="padding:20px;">
                  <canvas id="department-chart-{{ (json_encode(($department)->first()->department_id)) }}"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!-- END DEPARTMENT POPULATION OF CURRENT SCHOOL YEAR -->
            @endforeach
          @endif
        @endif
    <!-- END Default box -->
    
  </div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/search.js') }}"></script>

	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>
  {{-- <script src="{{ asset('js/moment.min.js') }}"></script> --}}
  <script src="{{ asset('js/chart.min.js') }}"></script>
  
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.min.js"></script> --}}
  <script src="{{ asset('js/palette.js') }}"></script>

  <!-- Annual Enrollment (Over-all) -->
  <script type="text/javascript">
    //line
    var ctxL = document.getElementById("annual_enrollment_lineChart").getContext('2d');
    @php
      $labels[] = "0";
      $datas[] = "0";
    @endphp
    var enrollmentLineChart = new Chart(ctxL, {
      type: 'line',
      data: {
        labels: {!! (json_encode(collect($labels)->concat(collect($schoolYears)->pluck('schoolYear')))) !!},
        datasets: [{
          label: "Annual Enrollment (Over-all)",
          data: {!! json_encode(collect($datas)->concat(collect($schoolYears)->pluck('enrollments_count'))) !!},
          backgroundColor: [
          'rgba(0, 137, 132, .2)',
          ],
          borderColor: [
          'rgba(0, 10, 130, .7)',
          ],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true
      }
    });
  </script>

   <!-- Annual Enrollment (Per Department) -->
  @if($department_per_schoolYears)
  <script>
    //line
    @php
      $depLabels[] = "0";
    @endphp
    var ctxL = document.getElementById("department-per-year-chart").getContext('2d');
    var departmentLabel = {!!(json_encode(collect($department_per_schoolYears)->unique('department_id')->pluck('department.name')->flatten()))!!};
    var myLineChart = new Chart(ctxL, {
    type: 'line',
    data: {
    labels: {!! (json_encode(collect($labels)->concat(collect($schoolYears)->pluck('schoolYear')))) !!},
    datasets: [
    @if($department_per_schoolYears)
      @if(count($department_per_schoolYears)>0)
        @foreach(collect($department_per_schoolYears)->groupBy('department_id') as $key => $value)
          @if($value->first()->department)
            {
            label: "{{($value->first()->department->name)}}",
            data: [0,{!! trim($value->groupBy('school_year_id')->map(function ($item, $key) {
                                              return collect($item)->count();
                                          })->values(), '[]') !!}],
            backgroundColor: [
                'rgba(255,255,255, 0.1)'
                ],
             borderColor: [
                'rgba('+random_rgba()+',1)'
                ],
            borderWidth: 2
            }@if(!$loop->last),@endif
          @endif
        @endforeach
      @endif
    @endif
    ]
    },
    options: {
    responsive: true
    }
    });
    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s);
    }
  </script>
  @endif

  <!-- Current School Year Enrollment -->
  <script>
    var myData = {!! (json_encode(($enrollments)->groupBy('department_id')->values()->map(function ($item, $key) {
                                              return collect($item)->count();
                                          }))) !!};
    var myLabel = {!! (json_encode(($enrollments)->groupBy('department_id')->values()->map(function ($item, $key) {
                                              return collect($item)->first()->department_name;
                                          }))) !!};
    @if((json_encode(($enrollments)->groupBy('department_id')->values()->map(function ($item, $key) {
                                              return collect($item)->first()->department_name;
                                          }))) == "[]")
      myData = ['1'];
      myLabel = ['No Data'];
    @endif
    var config = {
        type: 'pie',
        data: {
          datasets: [{
            data: myData,
            backgroundColor: 
              palette('tol-dv', myData.length).map(function(hex) {
              return '#' + hex;
              })
            ,
            // label: 'Dataset 1'
          }],
          labels: myLabel
        },
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Population by Department'
          }
        },
        showTooltips: true,
        pieceLabel: {
          render: 'label',
          segment: true,
        }
      };
      var currentSYCanvas      = document.getElementById('chart-area');
      var ctx                 = currentSYCanvas.getContext("2d");
      var currentSYChart       = new Chart(currentSYCanvas, config);
    
  </script>


  <!-- POPULATION PER DEPARTMENT CHART -->

  @if($enrollments)
    @if(count($enrollments)>0)
    
      @foreach(($enrollments)->groupBy('department_id') as $key => $department)
        <script>
          var myData = {!! 
                          (json_encode(($department)->groupBy('level_id')->values()
                                    ->map(function ($item, $key) {
                                          return collect($item)->count();
                                      }))) 
                          !!};

          var myLabel = {!! (json_encode(($department)->unique('level_id')->pluck('level_name'))) !!};

          @if((json_encode(collect($departments)->pluck('enrollments_count'))) == "[]")
            myData = ['1'];
            myLabel = ['No Data'];
          @endif
          var config = {
              type: 'doughnut',
              data: {
                datasets: [{
                  data: myData,
                  backgroundColor: 
                    palette('tol-dv', myData.length).map(function(hex) {
                    return '#' + hex;
                    })
                  ,
                  // label: 'Dataset 1'
                }],
                labels: myLabel
              },
              options: {
                responsive: true,
                title: {
                  display: true,
                  text: '{!! ((($department)->first()->department_name)) !!} Population'
                }
              },
              showTooltips: true,
              pieceLabel: {
                render: 'label',
                segment: true,
              }
            };
            var currentSYCanvas      = document.getElementById('department-chart-{{(json_encode(($department)->first()->department_id))}}');
            var ctx                 = currentSYCanvas.getContext("2d");
            var currentSYChart       = new Chart(currentSYCanvas, config);
          
        </script>
      @endforeach
    @endif
  @endif
  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')

@endsection
