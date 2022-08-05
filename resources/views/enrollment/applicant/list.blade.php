@extends('backpack::layout')

@section('header')
	  

@endsection

@section('content')

<!-- HEADER -->
  <div class="row " style="padding: 15px;">        
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group">
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
          <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
          <li class="active">{{ trans('backpack::crud.list') }}</li>
            </ol>
          </section>

      <!-- HEADER TITLE -->
      <h1 class="smo-content-title">
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!} List</span>
      </h1>
          <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-left"></div>
          </div>
          <!-- REDIRECT BUTTON -->
      <!-- @include('crud::inc.button_stack', ['stack' => 'redirect']) -->
      <!-- SEARCH BOX -->
      <!-- <search-enrolled></search-enrolled> -->
      <!-- <search-students></search-students> -->
        </div>
          
      <!-- NAVIGATION -->
      @include('enrollment.enrollment_navbar')
        
  </div>
<!-- HEADER END -->
<!-- CONTENT INFORMATION -->
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="info-box shadow">
          <div class="box-body" style="padding-top:25px;">
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">School Year</span>
              <span class="info-box-number">{{ $school_year->schoolYear }}<small></small></span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Department</span>
              <span class="info-box-number">{{ $department->name }}<small></small></span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Term Type:</span>
              <span class="info-box-number">{{ $department->term ? $department->term->type : 'Term Type Not Set' }}<small></small></span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Term:</span>
              <span class="info-box-number" id="selected_term">-<small></small></span>
            </div>

            <div class="col-md-3 col-lg-3">
              <div class="col-md-3 col-lg-3 col-xs-6 activate-portal">
                @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
                <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">
                  @include('crud::inc.button_stack', ['stack' => 'top'])

                </div>
                @endif
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>  
  <!-- END OF CONTENT INFORMATION -->

<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

<!--         <div class="row m-b-10">
          <div class="col-xs-6">
            @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

              @include('crud::inc.button_stack', ['stack' => 'top'])

            </div>
            @endif
          </div>

        </div> -->

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="overflow-hidden">
          {{-- @if(count($departments)>0)
            <div class="btn-group pull-left m-t-20" role="group" aria-label="...">
              @foreach($departments as $dept)
                <a href="{{ url($crud->route . '?school_year_id=' . request()->school_year_id . '&department=' . $dept->id) }}" class="btn btn-primary {{($dept->id == request()->department) ? 'active' : '' }}">{{ $dept->name }}</a>
              @endforeach
            </div>
          @endif --}}
          
        <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-visible="{{ var_export($column['visibleInTable'] ?? true) }}"
                    data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}"
                    data-visible-in-export="{{ var_export($column['visibleInExport'] ?? true) }}"
                    >
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th>{!! $column['label'] !!}</th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

          @if ( $crud->buttons->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="hidden-print">
            @include('crud::inc.button_stack', ['stack' => 'bottom'])

            <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
          </div>
          @endif

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')

@endsection
