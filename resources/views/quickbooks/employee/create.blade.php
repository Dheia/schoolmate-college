@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Create Employee</span>
        {{-- <small>Create</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="quickbooks/employee" class="text-capitalize">Employee</a></li>
	    <li class="active">{{ trans('backpack::crud.edit') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<!-- Default box -->
			<a href="{{ url('admin/quickbooks/employee') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all <span>Employees</span></a><br><br>
			
			<?php 
				$urlAction = url('admin/quickbooks/employee');
				if($action == "EDIT") {
					$urlAction = url('admin/quickbooks/employee/' . $id);
				}
			?>

			<form method="POST" action="{{ $urlAction }}">
				{!! csrf_field() !!}

				@if($action == "EDIT")
					{{ method_field('PUT') }}
				@endif

				<div class="box">
					<div class="box-header with-border">
						
					</div>
					<div class="box-body row display-flex-wrap" style="display: flex;flex-wrap: wrap;">
						

						<div class="col-md-6" id="Column1">
							<div class="form-group col-md-2">
								<label for="Title">Title</label>
								<input value="{{ old('Title') }}" type="text" name="Title" class="form-control" id="Title">
							</div>

							<div class="form-group col-md-3">
								<label for="FirstName">FirstName</label>
								<input value="{{ old('FirstName') }}" type="text" name="FirstName" class="form-control" id="FirstName" required>
							</div>

							<div class="form-group col-md-2">
								<label for="MiddleName">MiddleName</label>
								<input value="{{ old('MiddleName') }}" type="text" name="MiddleName" class="form-control" id="MiddleName">
							</div>
							
							<div class="form-group col-md-3">
								<label for="LastName">LastName</label>
								<input value="{{ old('LastName') }}" type="text" name="LastName" class="form-control" id="LastName" required>
							</div>

							<div class="form-group col-md-2">
								<label for="Suffix">Suffix</label>
								<input value="{{ old('Suffix') }}" type="text" name="Suffix" class="form-control" id="Suffix">
							</div>

							<div class="form-group col-md-12">
								<label for="DisplayName">Display Name As*</label>
								<input value="{{ old('DisplayName') }}" type="text" name="DisplayName" class="form-control" id="DisplayName" required>
							</div>

							<div class="form-group col-md-12">
								<label for="PrintOnCheckName">Print On Check As</label>
								<input value="{{ old('PrintOnCheckName') }}" type="text" name="PrintOnCheckName" class="form-control" id="PrintOnCheckName" required>
							</div>

							<div class="form-group col-md-12">
								<label for="Address">Address</label>
								<input value="{{ old('Address') }}" name="Address" class="form-control" id="Address" placeholder="Street" required>
							</div>

							<div class="form-group col-md-6">
								<label for="CityTown">City/Town</label>
								<input value="{{ old('CityTown') }}" type="text" name="CityTown" class="form-control" id="CityTown" placeholder="City/Town" required>
							</div>

							<div class="form-group col-md-6">
								<label for="StateProvince">State/Province</label>
								<input value="{{ old('StateProvince') }}" name="StateProvince" class="form-control" id="StateProvince" placeholder="State/Province" required>
							</div>

							<div class="form-group col-md-6">
								<label for="ZIPCode">ZIP Code</label>
								<input value="{{ old('ZIPCode') }}" name="ZIPCode" class="form-control" id="ZIPCode" placeholder="ZIP Code" required>
							</div>

							<div class="form-group col-md-6">
								<label for="Country">Country</label>
								<input value="{{ old('Country') }}" name="Country" class="form-control" id="Country" placeholder="Country" required>
							</div>

							{{-- <div class="form-group col-md-12">
								<label for="Notes">Notes</label>
								<textarea name="Notes" class="form-control" id="Notes"></textarea>
							</div> --}}
						</div>

						<div class="col-md-6" id="Column2">

								<div class="form-group col-md-12">
									<label for="Email">Email</label>
									<input value="{{ old('Email') }}" type="text" name="Email" class="form-control" id="Email" placeholder="Seperate Multiple Emails With Commas">
								</div>

								<div class="form-group col-md-6">
									<label for="Phone">Phone</label>
									<input value="{{ old('Phone') }}" name="Phone" class="form-control" id="Phone" placeholder="Phone">
								</div>

								<div class="form-group col-md-6">
									<label for="Mobile">Mobile</label>
									<input value="{{ old('Mobile') }}" name="Mobile" class="form-control" id="Mobile" placeholder="Mobile">
								</div>

								<div class="form-group col-md-6">
									<label for="BillingRate">Billing Rate (/hr)</label>
									<input value="{{ old('BillingRate') }}" type="number" name="BillingRate" class="form-control" id="BillingRate">
								</div>

								<div class="form-group col-md-3">
									<div class="checkbox">
										<div class="checkbox" style="margin-top: 30px;">
										 	<label for="Billable" class="checkbox-inline">
										 		<input {{ old('Billable') == "true" ? 'checked' : '' }} type="checkbox" name="Billable" id="Billable">Billable By Default
										 	</label>
										</div>
									</div>
								</div>

								<div class="form-group col-md-6">
									<label for="EmployeeIdNo">Employee ID No.</label>
									<input value="{{ old('EmployeeIdNo') }}" type="text" name="EmployeeIdNo" class="form-control col-md-6" id="EmployeeIdNo">
								</div>

								<div class="form-group col-md-6">
									<label for="EmployeeId">Employee ID.</label>
									<input value="{{ old('EmployeeId') }}" type="text" name="EmployeeId" class="form-control col-md-6" id="EmployeeId">
								</div>

								<div class="form-group col-md-12">
									<label for="Gender">Gender</label>
									<select name="Gender" id="Gender" class="form-control" id="Gender">
										<option value="Male" {{ old("Gender") == "Male" ? 'selected' : '' }}>Male</option>
										<option value="Female" {{ old("Gender") == "Female" ? 'selected' : '' }}>Female</option>
									</select>
								</div>

								<div class="form-group col-md-6">
									<label for="HireDate">Hire Date</label>
									<input value="{{ old('HireDate') }}" name="HireDate" type="date" id="HireDate" class="form-control" id="HireDate">
								</div>

								<div class="form-group col-md-6">
									<label for="Released">Released</label>
									<input value="{{ old('Released') }}" name="Released" type="date" id="Released" class="form-control" id="Released">
								</div>

								<div class="form-group col-md-6">
									<label for="DateOfBirth">Date Of Birth</label>
									<input value="{{ old('DateOfBirth') }}" name="DateOfBirth" type="date" id="DateOfBirth" class="form-control" id="DateOfBirth">
								</div>
						</div>

					</div><!-- /.box-body -->

					<div class="box-footer">
						<div id="saveActions" class="form-group">

								<button type="submit" class="btn btn-success">
									<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;Save
								</button>
							{{-- </div> --}}

							<a href="/admin/quickbooks/employee" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
						</div>
					</div><!-- /.box-footer-->
				</div><!-- /.box -->

			</form>
	</div>
</div>
@endsection
