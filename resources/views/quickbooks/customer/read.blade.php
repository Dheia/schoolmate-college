@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
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

		<div class="col-md-6">
			<h3 class="m-b-0">Amy's Bird Sanctuary</h3>
			<p>4581 Finch St., Bayshore, CA 94326</p>
		</div>

		<div class="col-md-6">
			
		</div>

				<div class="box-header with-border">
			      {{-- <a href="#" class="btn btn-primary">Add Profit And Loss Statement</a> --}}
			    </div>
				
				<div class="box-body">
					{{-- {{ dd($customers) }} --}}
{{-- 
					<table class="table table-bordered table-striped table-hoverable">
						<thead>
							<th>Name</th>
							<th>Phone</th>
							<th>Balance</th>
						</thead>	
						<tbody>
							{{- @foreach($invoices as $invoice)  --}}
									{{-- @if($invoice->CustomerRef == $customer->Id)
										@if($invoice->CustomerRef == $customer->Id)
											<tr>
												<td>
													{{ $customer->DisplayName }}
												</td>
	 											@if(isset($customer->PrimaryPhone))
													<td>{{ $customer->PrimaryPhone->FreeFormNumber }}</td>
												@else
													<td></td>
												@endif
												<td>{{ $customer->Balance }} {{ $customer->CurrencyRef }}</td>
												<td>{{ $invoice->TotalAmt }}</td>
											</tr>
										@endif
									@endif --}}
							{{-- @endforeach --}}
						{{-- </tbody> --}}
					{{-- </table> --}}
						
				</div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')

@endpush
