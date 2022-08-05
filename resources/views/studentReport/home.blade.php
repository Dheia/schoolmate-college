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
		    <li class="active">
		    	<a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a>
		    </li>
		    <li class="active">{{ trans('backpack::crud.list') }}</li>
		  </ol>
		</section>
        <h1 class="smo-content-title">
          <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        </h1>
        <div class="col-xs-6">
            <div id="datatable_search_stack" class="pull-left" placeholder="Search Student"></div>
        </div>
      </div>
            
      @include('student.studentlist_navbar')

    </div>
  <!-- END OF HEADER -->

 	<div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">
        <div class="overflow-hidden">
			@include('crud::inc.grouped_errors')
			<form id="form" method="post"
				{{-- target="_blank" --}}
		  		action="{{ url($crud->route) }}/download/report"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
				{!! csrf_field() !!}
				<div class="row">
					

				    <div class="col-md-12 display-flex-wrap smo-report-group">
				    	<!-- <div class="box-body"> -->
			          <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
			            
			          <!-- </div> -->
			        	<!-- </div> -->
						<!-- load the view from the application if it exists, otherwise load the one in the package -->
						{{-- @if(view()->exists('vendor.backpack.crud.form_content')) --}}
							{{-- @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ]) --}}
						{{-- @else --}}
							{{-- @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ]) --}}
						{{-- @endif --}}

						<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>



						@if ($crud->model->translationEnabled())
						<input type="hidden" name="locale" value={{ $crud->request->input('locale')?$crud->request->input('locale'):App::getLocale() }}>
						@endif

						{{-- See if we're using tabs --}}
						@if ($crud->tabsEnabled() && count($crud->getTabs()))
						    @include('crud::inc.show_tabbed_fields')
						    <input type="hidden" name="current_tab" value="{{ str_slug($crud->getTabs()[0], "") }}" />
						@else
						    <div class="box col-md-12 padding-10 p-t-20">
					
						    @include('crud::inc.show_fields', ['fields' => $crud->getFields('create')])
							<div class="col-md-12 btn-generate">
						    	<button title="Download" class="btn btn-primary "><i class="fa fa-download"></i> Download</button>
							    	<button title="Preview" type="button" onclick="view()" class="btn btn-primary pull-right m-r-15"><i class="fa fa-list"></i> Preview </button>
							    </div>

						    </div>


						@endif

						
					</div>
				</div>
			</form>

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_styles')

@endsection

@section('after_scripts')

	<script>
		console.log('test');
		var levels = {!! json_encode($levels) !!};
		var tracks = {!! json_encode($tracks) !!};
		var departments = {!! json_encode($departments) !!};

		var school_year = $('select[name="school_year_id"]');
		var department  = $('select[name="department_id"]');
		var level  		= $('select[name="level_id"]');
		var track  		= $('select[name="track_id"]');
		var term        = $('select[name="term_type"]');
		var sort_id 	= document.getElementById('sort_id');

		function watchDepartment () {
			var department_id = department.find('option:selected').val();

			options = '<option value="">-</option>';
			$.each(levels, function (key, val) {
				if(department_id == val.department_id) {
					options += '<option value="' + val.id + '">' + val.year + '</option>';
				}
			});
			$.each(departments, function (key, val) {
				if(department_id == val.id) {
					var termOptions;
					$.each(val.term.ordinal_terms, function (termIndex, ordinal_term) {
						termOptions += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
					});
					term.html(termOptions);
				}
			});

			level.html(options);
			watchLevel();
		}

		function watchLevel () {
			var level_id = level.find('option:selected').val();

			options = '<option value="">-</option>';
			$.each(tracks, function (key, val) {
				if(level_id == val.level_id) {
					options += '<option value="' + val.id + '">' + val.code + '</option>';
				}
			});

			track.html(options);
		}

		function watchSort () {
			if (sort_id.checked) {
            	// First Level Show
                $('select[name="first_level"').parent().show();
                $('select[name="first_level_order"').parent().show();
                // Second Level Show
                $('select[name="second_level"').parent().show();
                $('select[name="second_level_order"').parent().show();
                // Third Level Show
                $('select[name="third_level"').parent().show();
                $('select[name="third_level_order"').parent().show();
                // Fourth Level Show
                $('select[name="fourth_level"').parent().show();
                $('select[name="fourth_level_order"').parent().show();
                // Fifth Level Show
                $('select[name="fifth_level"').parent().show();
                $('select[name="fifth_level_order"').parent().show();
            } else {
            	// First Level Hide
                $('select[name="first_level"').parent().hide();
                $('select[name="first_level_order"').parent().hide();
                 // Second Level Hide
                $('select[name="second_level"').parent().hide();
                $('select[name="second_level_order"').parent().hide();
                // Third Level Hide
                $('select[name="third_level"').parent().hide();
                $('select[name="third_level_order"').parent().hide();
                // Fourth Level Hide
                $('select[name="fourth_level"').parent().hide();
                $('select[name="fourth_level_order"').parent().hide();
                // Fifth Level Hide
                $('select[name="fifth_level"').parent().hide();
                $('select[name="fifth_level_order"').parent().hide();

            }
		}

		department.change(function () { watchDepartment(); });
		level.change(function () { watchLevel(); });

		$(document).ready(function () {
			watchDepartment();
			watchSort();
	        $('#sort_id').click(function (event) {
	            watchSort();
	        });

	    });

	</script>
@endsection
