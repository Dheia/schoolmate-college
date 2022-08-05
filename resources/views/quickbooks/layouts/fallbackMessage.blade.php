@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	{{-- QuickBooks --}}
        	&nbsp;
        </h1>
        <ol class="breadcrumb">
        	<li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        	<li class="active">QuickBooks</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush


@section('content')
	<div class="row">
		<div class="col-md-12">

			<div class="box">

				<div class="box-header with-border"></div>
				
				{{-- <div class="box-body"> --}}
					<center>
						<img style="width: 60%;" class="img-responsive" src="{{ asset('images/qbo-logo.png') }}" alt="QBO">
						@if($status == "OK")
							<h1>{{ $message }}</h1>
						@else
							<h1><b>Error:</b> {!! $message !!}</h1>
						@endif
					</center>
						
				{{-- </div> --}}

				<div class="box-header with-border"></div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')

@endpush
