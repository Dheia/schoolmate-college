@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	All Sales
        	{{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
        	<li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        	<li class="active">All Sales</li>
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
			    </div>
				
				<div class="box-body">
					{{-- {{ dd($customers) }} --}}

						<table class="table table-bordered table-striped table-hoverable">
							<thead>
								<th>Name</th>
								<th>CurrentBalance</th>
							</thead>	
							<tbody>
								@foreach($accounts as $account) 
									<tr>
										<td>
											{{ $account->Name }}
										</td>
										<td>
											{{ $account->CurrentBalance }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						
				</div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')

@endpush
