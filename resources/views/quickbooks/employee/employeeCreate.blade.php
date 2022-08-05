@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Create</span>
        <small>Create</small>
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
			<a href="/admin/quickbooks/employee" class="hidden-print"><i class="fa fa-angle-double-left"></i> Back to all <span>Employees</span></a><br><br>


			<form method="post" action="">
				{!! csrf_field() !!}
				{!! method_field('PUT') !!}

				<div class="box">
					<div class="box-header with-border">
						
					</div>
					<div class="box-body row display-flex-wrap" style="display: flex;flex-wrap: wrap;">
						
					</div><!-- /.box-body -->

					<div class="box-footer">
						<div id="saveActions" class="form-group">
							<input type="hidden" name="save_action" value="save_and_back">

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
