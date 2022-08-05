@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	Customers
        	{{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
        	<li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        	<li class="active">Customer</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
	<div class="row">
		<div class="col-md-12">

			<div class="box">

				<div class="box-header with-border">
			      {{-- <a href="#" class="btn btn-primary">Add Profit And Loss Statement</a> --}}
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							New Transaction&nbsp; <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#">Invoice</a></li>
							<li><a href="#">Payment</a></li>
							<li><a href="#">Estimate</a></li>
							<li><a href="#">Sales Receipt</a></li>
							<li><a href="#">Credit Memo</a></li>
							<li><a href="#">Delayed Charge</a></li>
							<li><a href="#">Time Activity</a></li>
						</ul>
					</div>
			    </div>
				
				<div class="box-body">
					{{-- {{ dd($customers) }} --}}

						
				</div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')

@endpush