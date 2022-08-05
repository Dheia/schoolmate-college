{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

@if ($crud->hasAccess('redirectButton'))
	<br><a 	title="Redirect"
		style="margin-bottom: 5px; width: 100%;" 
		href="{{ asset($crud->data['redirectButton']['route'] ?? url($crud->route)) }}" 
		data-style="zoom-in" 

		@if(isset($crud->data['redirectButton']['button-style']))
	    	style = "
			@foreach($crud->data['redirectButton']['button-style'] as $attribute => $value)
				{{ $attribute }}:{!! $value !!};
			@endforeach
			"
	    @endif

		@if(isset($crud->data['redirectButton']['attribute']))
			
			@foreach($crud->data['redirectButton']['attribute'] as $attribute => $value)
				@if($value ?? '')
				{{ $attribute }}="{{ $value }}"
				@endif
			@endforeach

			@if(isset($crud->data['redirectButton']['attribute']['class']))
				@if($crud->data['redirectButton']['attribute']['class']))
					class="{{$crud->data['redirectButton']['attribute']['class']}}"
				{{-- @else
					class="btn btn-primary" --}}
				@endif
			{{-- @else
				class="btn btn-primary" --}}
			@endif
		{{-- @else
			class="btn btn-primary" --}}
		@endif
	>
		<span class="ladda-label">
			<i 
				@if(isset($crud->data['redirectButton']['icon-attribute']))
					@foreach($crud->data['redirectButton']['icon-attribute'] as $attribute => $value)
						@if($value ?? '')
						{{ $attribute }}="{{ $value }}"
						@endif
					@endforeach
				@endif
				@if (isset($crud->data['redirectButton']['icon-attribute']['class']))
			    	class="btn btn-primary"
			    @elseif(!$crud->data['redirectButton']['attribute']['class'])
			    	class="btn btn-primary"
			    @endif

			    @if(isset($crud->data['redirectButton']['icon-style']))
			    	style = "
					@foreach($crud->data['redirectButton']['icon-style'] as $attribute => $value)
						{{ $attribute }}:{{ $value }};
					@endforeach
					"
			    @endif
			></i>
		</span>
		@if(!isset($crud->data['redirectButton']['label']))
			Redirect
		@else
			{{ $crud->data['redirectButton']['label'] ?? '' }}
		@endif
	</a>
@endif