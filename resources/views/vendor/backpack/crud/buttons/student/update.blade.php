<!-- 		@if ($crud->hasAccess('update'))
			<li>
				@if (!$crud->model->translationEnabled())

				<a style="margin-bottom: 5px !important; width: 100%;" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="text-sm"><i class="fa fa-edit" title="Update"></i> 
					{{-- {{ trans('backpack::crud.edit') }} --}}
					Update
				</a>

				@else

				<div class="btn-group">
				  <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
				  <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu dropdown-menu-right">
			  	    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
				  	@endforeach
				  </ul>
				</div>

				@endif
			</li>
		@endif
 -->


@if ($crud->hasAccess('update'))
		@if (!$crud->model->translationEnabled())

		<!-- Single edit button -->
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default action-btn">
			<i class="fa fa-edit" title="Update"></i> 
			<!-- {{-- {{ trans('backpack::crud.edit') }} --}} -->
			<!-- Update -->
		</a>

		@else

		<!-- Edit button group -->
		<div class="btn-group">
		  <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
		  <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    <span class="caret"></span>
		    <span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu dropdown-menu-right">
	  	    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
		  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
			  	<li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
		  	@endforeach
		  </ul>
		</div>
		@endif
@endif