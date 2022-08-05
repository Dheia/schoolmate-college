@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Create Customer</span>
        {{-- <small>Create</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url('admin/quickbooks/customer') }}" class="text-capitalize">Customer</a></li>
	    <li class="active">Create</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<!-- Default box -->
			<a href="{{ url('admin/quickbooks/customer') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all <span>Customers</span></a><br><br>
			
			<?php 
				$urlAction = url('admin/quickbooks/customer');
				if($action == "EDIT") {
					$urlAction = url('admin/quickbooks/customer/' . $id);
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
						

						

					</div><!-- /.box-body -->

					<div class="box-footer">
						<div id="saveActions" class="form-group">

							<button type="submit" class="btn btn-success">
								<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;Save
							</button>

							<a href="/admin/quickbooks/customer" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>

						</div>
					</div><!-- /.box-footer-->
				</div><!-- /.box -->

			</form>
	</div>
</div>
@endsection
