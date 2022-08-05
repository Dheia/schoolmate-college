@extends('backpack::layout')

@section('header')
	{{-- <section class="content-header">
	  <h1>
        <span class="text-capitalize">Create Term</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url('admin/quickbooks/term') }}" class="text-capitalize">Term</a></li>
	    <li class="active">Create</li>
	  </ol>
	</section> --}}
@endsection

@section('content')
	<!-- HEADER START -->
  	<div class="row" style="padding: 15px;">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group">
			<section class="content-header">
			  	<ol class="breadcrumb">
				    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
				    <li><a href="{{ url('admin/quickbooks/term') }}" class="text-capitalize">Term</a></li>
				    <li class="active">Create</li>
			  	</ol>
			</section>
			<h1 class="smo-content-title">
		        <span class="text-capitalize">Create Term</span>
		    </h1>
    	</div>
    </div>
    <!-- HEADER END -->

<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
			<a href="{{ url('admin/quickbooks/term') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all <span>Terms</span></a><br><br>
			
			<?php 
				$urlAction = url('admin/quickbooks/term');
				if($action == "EDIT") {
					$urlAction = url('admin/quickbooks/term/' . $id);
				}
			?>

			<form method="POST" action="{{ $urlAction }}">
				{!! csrf_field() !!}

				@if($action == "EDIT")
					{{ method_field('PUT') }}
				@endif

				<div class="box">
					<div class="box-header with-border">
						<i class="fa fa-info-circle"></i> Terms show how many days a customer has to pay you. You can change this number before you send the invoice.
					</div>
					<div class="box-body row display-flex-wrap" style="display: flex;flex-wrap: wrap;">
						
						{{-- {{ dd($input[]) }} --}}
			            <div class="form-group col-md-6">
			              <label for="name">Name</label>
			              <input type="text" id="name" name="Name" value="{{ $action == 'EDIT' ? $input['Name'] : '' }}" class="form-control">
			            </div>
						
			            <div class="form-group col-md-6">
			              <label for="dueDays">Due Days</label>
			              <input type="number" id="dueDays" name="DueDays" value="{{ $action == 'EDIT' ? $input['DueDays'] : ''  }}" class="form-control">
			            </div>
						

					</div><!-- /.box-body -->

					<div class="box-footer">
						<div id="saveActions" class="form-group">

							<button type="submit" class="btn btn-success">
								<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;Save
							</button>

							<a href="/admin/quickbooks/term" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>

						</div>
					</div><!-- /.box-footer-->
				</div><!-- /.box -->

			</form>
	</div>
</div>
@endsection
