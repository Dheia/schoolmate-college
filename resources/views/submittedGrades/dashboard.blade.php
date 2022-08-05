@extends('backpack::layout')

@section('content')
<!-- Default box -->
  <div class="row">
    <!-- DEPARTMENTS START -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="box" style="border-radius: 5px;">
        <div class="box-body">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>
              Select a School Year to view list.
            </h4>
          </div>
          <!-- THE ACTUAL CONTENT -->
          <div style="padding-top: 15px;">
            @if($schoolYears)
              @if(count($schoolYears)>0)
              {{-- {{dd($schoolYears)}} --}}
                @foreach( $schoolYears as $key => $schoolYear)
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="small-box bg-blue" style="border-radius: 5px;">
                      <div class="inner text-center">
                        <h3 class="p-t-15 p-b-15">{{ $schoolYear->schoolYear }}</h3>
                       {{--  <p>Total Students: {{ count($schoolYear->students) }}</p> --}}
                       <p></p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-folder"></i>
                      </div>
                      <a href="{{ asset($crud->route.'/'.$schoolYear->id.'/school-year/') }}" class="small-box-footer">
                        More info
                      </a>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box">
                    <div class="box-body">
                        <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                          No school years found.
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
                          No school years found.
                        </h4>
                    </div>
                  </div>
                </div>
            @endif
          </div>
          
        </div>
      </div>
    </div>
    <!-- DEPARTMENTS END -->

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

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
