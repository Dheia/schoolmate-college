@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	Employees
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
			      <a href="{{ URL::to('admin/quickbooks/employee/create') }}" class="btn btn-primary">Add Employee</a>
			    </div>
				
				<div class="box-body">
					{{-- {{ dd($customers) }} --}}

						<table class="table table-bordered table-striped table-hoverable">
							<thead>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Email Address</th>
								<th>Action</th>
							</thead>	
							<tbody>
								@foreach($employees as $employee) 
									<tr>
										<td>
											{{ $employee->DisplayName }}
										</td>
										<td>
											@if($employee->PrimaryPhone !== null)
												{{ $employee->PrimaryPhone->FreeFormNumber }}
											@endif
										</td>
										<td>
											@if($employee->PrimaryEmailAddr !== null)
												{{ $employee->PrimaryEmailAddr->Address }}
											@endif
										</td>
										<td>
											<div class="dropdown">
					                            <a href="javascript:void(0)" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
				                            		Action <span class="caret"></span>
					                            </a>
					                            <ul class="dropdown-menu">
													<li><a href="{{ URL('/admin/quickbooks/employee/' . $employee->Id) . '/edit' }}">Edit</a></li>
													<li><a href="{{ URL('/admin/quickbooks/employee/' . $employee->Id) . '/inactive' }}">Make Inactive</a></li>
													<li><a href="#">Run Report</a></li>
					                            </ul>
					                        </div>
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
