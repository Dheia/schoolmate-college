@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{ $total_books }}</h3>

            <p>Total Books</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
  
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{ $total_unique_books }}</h3>

            <p>Total Unique Books</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{ $total_borrowed_books }}</h3>

            <p>Total Borrowed Books</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        <div class="row m-b-10">
          <div class="col-xs-6">
            @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

              @include('crud::inc.button_stack', ['stack' => 'top'])

            </div>
            @endif
          </div>
          <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-right"></div>
          </div>
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

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('before_scripts')
  {{-- ADD MODAL --}}
  <div class="modal fade" id="addModal" 
    tabindex="-1" role="dialog" 
    aria-labelledby="bookModalLabel">
    <div class="modal-dialog" role="document">
	    <div class="modal-content">
          	<div class="modal-header">
	            <h4 class="modal-title" id="bookModalLabel">{{old('book_title')}}</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          	</div>
            <form method="POST" action="{{ url($crud->route.'/add-book-copy') }}">
              @csrf
              {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Call No.</label>
                        <input readonly="true" type="text" class="form-control" id="modal_call_number" name="modal_call_number" placeholder="Call No." value="{{old('modal_call_number')}}">
                        @if($errors->has('modal_call_number'))
                          <div class="error" style="color: red;"><strong>{{ $errors->first('modal_call_number') }}</strong></div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="book_id" name="book_id" value="{{old('book_id')}}">
                        <input type="hidden" id="title" name="title" value="{{old('title')}}">
                        <label>Accession No.</label>
                        <input type="text" class="form-control" id="modal_accession_number" name="modal_accession_number" placeholder="Accession No." value="{{old('modal_accession_number')}}">
                        @if($errors->has('modal_accession_number'))
                          <div class="error" style="color: red;"><strong>{{ $errors->first('modal_accession_number') }}</strong></div>
                        @endif
                    </div>
                    <div class="form-group">
						            <label>ISBN</label>
                        <input type="text" class="form-control" id="modal_isbn" name="modal_isbn" placeholder="ISBN" value="{{old('modal_isbn')}}">
                         @if($errors->has('modal_isbn'))
                          <div class="error" style="color: red;"><strong>{{ $errors->first('modal_isbn') }}</strong></div>
                        @endif
                    </div>
                    <div class="form-group">
						            <label>Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="{{old('code')}}">
                        @if($errors->has('code'))
                          <div class="error" style="color: red;"><strong>{{ $errors->first('code') }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                	<button id="cancel-add-copy" type="button" 
                    class="btn btn-default" 
                    data-dismiss="modal">Cancel</button>
                    <span class="pull-right">
                    <button id="add-copy" name="add-copy" type="submit" class="btn btn-primary">
                        Add
                    </button>
                    </span>
                    
                </div>
            </form>
	    </div>
	 </div>
@endsection

@section('after_scripts')
	@include('crud::inc.datatables_logic')
  @if ($errors->any())
    <!-- <div class="alert alert-danger alert-block">
      <button type="button" class="close" data-dismiss="alert">X</button>
          @foreach ($errors->all() as $error)
            <strong>{{ $error }}</strong><br>
          @endforeach
    </div> -->
    <script>
      $(function() {
          $('#addModal').modal('show', function (e){
            $("#book_id").val($(e.relatedTarget).data('id'));
            // $("#bookModalLabel").html($title);
            $("#modal_accession_number").val({{$default_accession_number}});      
          });
      });
    </script>
  @endif

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>
  <script>
    $(function() {
        $('#addModal').on("show.bs.modal", function (e) {
            $("#book_id").val($(e.relatedTarget).data('id'));
            $("#bookModalLabel").html($(e.relatedTarget).data('title'));
            $("#book-title").html($(e.relatedTarget).data('title'));
            $("#title").val($(e.relatedTarget).data('title'));
            $("#modal_accession_number").val({{$default_accession_number}});
            $("#modal_call_number").val($(e.relatedTarget).data('call-number'));
            $("#code").val($(e.relatedTarget).data('code'));
            $("#modal_isbn").val($(e.relatedTarget).data('isbn'));

            // Disabled Modal Add If no Value
            if($('#modal_accession_number').val().length !=0 & $('#modal_call_number').val().length !=0 ){
              $('#add-copy').attr('disabled',false);
            }
            else{
              $('#add-copy').attr('disabled',true);
            }
        });
        $('#cancel-add-copy').on("click", function (e) {
            $( ".error" ).hide();
        });
        $('#addModal').modal('hide', function (e){
            $( ".error" ).hide();
        });
        
    });
    $( document ).ready(function() {
        if($('#modal_accession_number').val().length !=0 & $('#modal_call_number').val().length !=0 ){
          $('#add-copy').attr('disabled',false);
        }
        else{
          $('#add-copy').attr('disabled',true);
        }
      	
        $('#modal_accession_number').keyup(function(){
        	// console.log($('#accession_number').val().length);
            if($('#modal_accession_number').val().length !=0 & $('#modal_call_number').val().length !=0 ){
                $('#add-copy').attr('disabled', false);
            }else{
            	$('#add-copy').attr('disabled',true);
            }
        });
        $('#modal_call_number').keyup(function(){
            if($('#modal_accession_number').val().length !=0 && $('#modal_call_number').val().length !=0 ){
                $('#add-copy').attr('disabled', false);
            }else{
            	$('#add-copy').attr('disabled',true);
            }
        });
        $('#modal_isbn').keyup(function(){
            if($('#modal_accession_number').val().length !=0 && $('#modal_call_number').val().length !=0 ){
                $('#add-copy').attr('disabled', false);
            }else{
            	$('#add-copy').attr('disabled',true);
            }
        });
        $('#modal_code').keyup(function(){
            if($('#modal_accession_number').val().length !=0 && $('#modal_call_number').val().length !=0 ){
                $('#add-copy').attr('disabled', false);
            }else{
            	$('#add-copy').attr('disabled',true);
            }
        });
    });
    
</script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
