@extends('backpack::layout')

@section('header')
	{{-- <section class="content-header">
	  <h1>
      <span class="text-capitalize">
        {{ $teacher->name ?? 'Unknown' }}
      </span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section> --}}
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
            <a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a>
          </li>
          <li class="active">{{ trans('backpack::crud.list') }}</li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">
          {{ $teacher->full_name ?? 'Unknown' }}
        </span>
      </h1>
      <div class="col-xs-6">
          <div id="datatable_search_stack" class="pull-left"></div>
      </div>
    </div>

  </div>
  <!-- END OF HEADER -->
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        <div class="row m-b-10">
          <div class="col-xs-6">
            @if ($crud->hasAccess('create'))
              @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
              <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">
              <a href="/{{ $crud->route }}/create?teacher_id={{ request()->get('teacher_id') }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> Add Subject</span></a>
                {{-- @include('crud::inc.button_stack', ['stack' => 'top']) --}}

              </div>
              @endif
            @endif
          </div>
          {{-- <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-right"></div>
          </div> --}}
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="overflow-hidden">

        <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-visible-in-modal="{{ (isset($column['visibleInModal']) && $column['visibleInModal'] == false) ? 'false' : 'true' }}"
                    data-visible="{{ !isset($column['visibleInTable']) ? 'true' : (($column['visibleInTable'] == false) ? 'false' : 'true') }}"
                    data-visible-in-export="{{ (isset($column['visibleInExport']) && $column['visibleInExport'] == false) ? 'false' : 'true' }}"
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

  <!-- TRANSFER CLASS MODAL -->
  <div id="transferClassModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Transfer Class</h4>
        </div>
        <form id="transferClassForm" method="POST" action="{{ url($crud->route.'/transfer-class?teacher_id=' . $teacher->employee_id) }}">
          @csrf
          {!! csrf_field() !!}
          <div class="modal-body">
            <div class="form-group">
              <input type="hidden" id="teacher_subject_id" name="teacher_subject_id">
              <h5 style="word-wrap: break-word;" id="modalText">
                You are about to transfer the class for Subject of Level - Section to
              </h5>
              <select class="form-control" name="employee_id" id="employee_id">
                @if(count($employees) > 0)
                  @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{ $employee->lastname . ', ' . $employee->firstname . ' ' . $employee->middlename }}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>
        </form>
          <div class="modal-footer">
            <button id="btnTransfer" type="button" class="btn btn-primary">Transfer</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>
  <!-- /.TRANSFER CLASS MODAL -->

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

  <script>
    var teacher_subject_id;
    $(function() {
        $('#transferClassModal').on("show.bs.modal", function (e) {
            teacher_subject_id  = $(e.relatedTarget).data('id');
            $("#teacher_subject_id").val($(e.relatedTarget).data('id'));
            $("#modalText").html($(e.relatedTarget).data('label'));
        });
    });

    $('#btnTransfer').click(function(){
      $("#teacher_subject_id").val(teacher_subject_id);
      $("#transferClassForm").submit();
    });
  </script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
