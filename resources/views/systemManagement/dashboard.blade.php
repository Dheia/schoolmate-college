@extends('backpack::layout')

@section('header')
  <section class="content-header" >
    <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
      <li><a class="text-capitalize active">{{ $crud->entity_name_plural }}</a></li>
    </ol>
  </section>

@endsection

@section('after_styles')
  <style>
    @media only screen and (max-width: 768px) {
      #btnAddStudent {
        width: 100%;
      }
    }
  </style>
@endsection

@section('content')

<!-- DEPARTMENTS START -->
  <div class="row" style="padding: 15px;">        

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
          
            <h1 class="smo-content-title">
              <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            </h1>
            
          
          <search-students></search-students>
        </div>
            
          @include('student.studentlist_navbar')
        

  </div>
  <!-- DEPARTMENTS END -->

<!-- Default box -->
  <div class="row">
    <!-- DEPARTMENTS START -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="info-box shadow">
        <div class="box-body">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>
              Select a Department to view list.
            </h4>
            <hr>
          </div>
          <!-- THE ACTUAL CONTENT -->
          <div>
             @if(!request()->department)
              
              @if($departments)
                @if(count($departments)>0)
                  @foreach( $departments as $key => $department)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                      <div class="small-box bg-blue" style="border-radius: 5px;">
                        <div class="inner">

                          {{-- <h3>{{ count($department->students) }}</h3> --}}
                          <h3> 
                            {{ count($students->where('department_id', $department->id)) }}
                          </h3>

                          <p>{{ $department->name }}</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ asset($crud->route.'?department='.$department->id) }}" class="small-box-footer" style="padding: 10px;">
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

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}"></script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
