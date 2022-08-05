@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Topic: {{$selected_topic->title ?? 'Unknown'}}</span>
       <!--  <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small> -->
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}?course_code={{$course->code}}&module_id={{$module->id}}" class="text-capitalize"{{ $crud->entity_name_plural }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.edit') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	<a href="{{ url('admin/online-class-topic?course_code=' . $course->code . '&module_id=' . $module->id . '&topic_id=' . $selected_topic->id) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
@endif

<div class="row m-t-20">
	<div class="{{ $crud->getEditContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		<form method="post"
	  		action="{{ url('admin/online-class-topic-page/' . $entry->getKey() . '?course_code=' . $course->code . '&module_id=' . $module->id . '&topic_id=' . $selected_topic->id) }}"
			@if ($crud->hasUploadFields('update', $entry->getKey()))
			enctype="multipart/form-data"
			@endif
	  		>
			{!! csrf_field() !!}
			{!! method_field('PUT') !!}
			<div class="col-md-12">
			  	@if ($crud->model->translationEnabled())
			    <div class="row m-b-10">
			    	<!-- Single button -->
					<div class="btn-group pull-right">
					  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
					  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
						  	<li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
					  	@endforeach
					  </ul>
					</div>
			    </div>
			    @endif
			    <div class="row display-flex-wrap">
			      <!-- load the view from the application if it exists, otherwise load the one in the package -->
			      @if(view()->exists('vendor.backpack.crud.form_content'))
			      	@include('vendor.backpack.crud.form_content', ['fields' => $fields, 'action' => 'edit'])
			      @else
			      	@include('crud::form_content', ['fields' => $fields, 'action' => 'edit'])
			      @endif
			    </div><!-- /.box-body -->

	            <div class="">
	                <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-success" >
	                	<span class="fa fa-save" role="presentation" aria-hidden="true"></span> Update
	              	</button>
	              	<a href="{{ url('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id.'&topic_page='.$entry->getKey())}}" style="margin-right: 10px;" type="button" class="btn btn-secondary" data-dismiss="modal">
	                	<span class="fa fa-ban"></span> Cancel
	              	</a>

			    </div><!-- /.box-footer-->
		  	</div><!-- /.box -->
		</form>
	</div>
</div>
@endsection
