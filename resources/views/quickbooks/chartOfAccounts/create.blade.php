@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Create Account</span>
        {{-- <small>Create</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url('admin/quickbooks/chart-of-accounts') }}" class="text-capitalize">Account</a></li>
	    <li class="active">Create</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<!-- Default box -->
			<a href="{{ url('admin/quickbooks/chart-of-accounts') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all <span>Accounts</span></a><br><br>
			
			<?php 
				$urlAction = url('admin/quickbooks/chart-of-accounts');
				if($action == "EDIT") {
					$urlAction = url('admin/quickbooks/chart-of-accounts/' . $id);
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
						
						<div class="col-md-6 form-group">
							<label for="accountType">Account Type</label>
							<select name="AccountType" id="accountType" class="form-control">
								@foreach($accountTypes as $key => $accountType)
									<option value="{{ $key }}" id="{{ trim(str_replace(' ', '', $key)) }}">{{ $key }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-6 form-group">
							<label for="name">Name</label>
							<input  id="name" name="Name" type="text" class="form-control">
						</div>		

						<div class="col-md-6 form-group">
							<label for="detailType">Detail Type</label>
							<select name="AccountSubType" id="detailType" class="form-control">
								
							</select>
						</div>	

						<div class="col-md-6 form-group">
							<label for="description">Description</label>
							<input type="text" name="Description" id="description" class="form-control">
						</div>

						<div class="col-md-6">
							<p id="detailTypeDesc" style="padding: 15px; background-color: #ecf0f5"></p>
						</div>

						<div class="col-md-6">
							<div class="checkbox">
								<label class="checkbox-inline">
						    		<input name="IsSubAccount" type="checkbox" id="subAccount" /> Is sub-account
						    	</label>
						    </div>
							<div class="col-md-12 form-group" style="padding: 0;">
								<select name="SubAccount" id="parentAccount" class="form-control" disabled>
									<option selected disabled>Enter Parent Account</option>
									@foreach($accounts as $key => $account)
										<optgroup label="{{ $key }}">
												@foreach($account as $acc)
													<option value="{{ $acc->Name }}">{{ $acc->Name }}</option>
												@endforeach
										</optgroup>
									@endforeach
								</select>
							</div>


						    <div class="d-inline-block">
						    	<div class="col-md-6 form-group" style="padding: 0;">
							    	<label for="balance">Balance</label>
							    	<input type="number" name="Balance" class="form-control" id="balance">
						    	</div>
						    	<div class="col-md-6 form-group" style="padding: 0;">
							    	<label for="as_of">as of</label>
							    	<input type="date" name="AsOf" class="form-control" id="as_of">
						    	</div>
						    </div>
						</div>	
		
					</div><!-- /.box-body -->

					<div class="box-footer">
						<div id="saveActions" class="form-group">

							<button type="submit" class="btn btn-success">
								<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;Save
							</button>

							<a href="/admin/quickbooks/chart-of-accounts" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>

						</div>
					</div><!-- /.box-footer-->
				</div><!-- /.box -->

			</form>
	</div>
</div>
@endsection


@push('after_scripts')
	<script>
		var subType = {!! json_encode($accountTypes, false) !!};

		function accountType () {

			// GET THE ACCOUNT TYPE SELECTED
			var accountType = $('#accountType option:selected').val();
			var detailType = $('#detailType');

			var detailTypeOptions = '';
			$.each(subType[accountType], function (key, val) {
				detailTypeOptions += '<option value="' + key + '">' + key.replace(/([A-Z])/g, ' $1').replace(/^./, function(str){ return str.toUpperCase(); }) + '</option>';
			});

			detailType.html(detailTypeOptions);
			$('#detailTypeDesc').text(subType[accountType][detailType.find('option:selected').val()]);
		}

		accountType();
		$('#accountType').change(function () {
			console.log("CHANGED");
			accountType();
		});

		$('#detailType').change(function () {
			$('#detailTypeDesc').text(subType[$('#accountType option:selected').val()][$('#detailType').find('option:selected').val()]);
		});

		$("#subAccount").change(function() {
			console.log(this.checked);
		    if(this.checked) {
		    	$('#parentAccount').removeAttr('disabled');
		    } else {
		    	$('#parentAccount').attr('disabled', true);
		    	$('#parentAccount option:selected').val([]);
		    }
		});

		if($('#subAccount').prop('checked')) {
			$('#parentAccount').removeAttr('disabled');
		} else {
			$('#parentAccount').attr('disabled');
		}
	</script>
@endpush