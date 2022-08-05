@extends('backpack::layout')

@section('header')

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
            <a class="text-capitalize active">{{ $crud->entity_name_plural }} Dashboard</a>
          </li>
        </ol>
      </section>

      <!-- HEADER TITLE -->
      <h1 class="smo-content-title">
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      </h1>

      <!-- SEARCH BOX -->
      <!-- <search-enrolled></search-enrolled> -->
      <!-- <search-students></search-students> -->
        </div>
          
      <!-- NAVIGATION -->
      @include('enrollment.enrollment_navbar')
        
  </div>
<!-- HEADER END -->

 <div class="row">
     <!-- DEPARTMENTS START -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                      <div class="small-box bg-blue">
                        <div class="inner">
                          <h3>{{ $schoolYear->schoolYear }}</h3>

                          <p>Total Applicants: {{ count($schoolYear->enrollment_applicants) }}</p>
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
                              $sy_department->enrollment_applicants
                                ->where('school_year_id', request()->school_year_id)
                                ->count()
                            }}
                          </h3>

                          <p>{{ $sy_department->name }}</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ asset($crud->route.'?school_year_id='.request()->school_year_id.'&department='.$sy_department->id) }}" class="small-box-footer" style="padding:10px;">
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
            @endif
          </div>
          
        </div>
      </div>
    </div>
    <!-- DEPARTMENTS END -->
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
	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>
 
@endsection
