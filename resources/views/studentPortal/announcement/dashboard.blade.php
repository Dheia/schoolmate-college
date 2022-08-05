

@extends('backpack::layout_student')

@section('header')
<style>
	 @media only screen and (min-width: 768px) {
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
</style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
	<div class="container">

		<!-- HEADER -->
		<div class="row" style="padding: 15px;">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
				<section class="content-header">
					<ol class="breadcrumb">
						<li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
						<li><a class="text-capitalize active">Announcement</a></li>
					</ol>
				</section>
				<h1 class="smo-content-title">
					<span class="text-capitalize"><i class="fas fa-bullhorn"></i> Announcement</span>
				</h1>
			</div>
		</div>
		<!-- END OF HEADER -->

		<div class="row">

			<div class="col-md-12">
				@forelse ($announcements as $announcement)
				<div class="row">
					<div class="col-md-10">
						<div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de; border-top-color: #007bff !important;">
							<!-- <div class="box-header with-border">
								<h3 class="box-title"><i class="fas fa-bullhorn"></i></h3>
							</div> -->
							<!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-1 col-xs-1">
										<h4><b><i class="fas fa-bullhorn"></i></b></h4>
									</div>
									<div class="col-md-11 col-xs-10">
										<h4>{!! $announcement->message !!}</h4>
									</div>
								</div>

								<!--Announcement Image -->
								@if($announcement->image)
								<div class="row">
									<div class="col-md-1 col-xs-1">
										{{-- <h4><b><i class="fas fa-bullhorn"></i></b></h4> --}}
									</div>
									<div class="col-md-11 col-xs-10">
										<img src="{{ asset($announcement->image) }}" style="max-width: 100%; max-height: 300px; padding-left: 50px; padding-right: 50px;">
									</div>
								</div>
								@endif

								<!-- Annoucement Files -->
								@if($announcement->files)
									@if( count($announcement->files) > 0 )
										<div class="row">
											<div class="col-md-1 col-xs-1">
											</div>
											<div class="col-md-11 col-xs-10">
												<h5><b>Files:</b></h5>
												@foreach ( $announcement->files as $file )
													<a href="{{ url($file) }}" target="_blank" download="{{ url($file) }}"> {{ url($file) }} </a>
													@if(!$loop->last)
														<br>
													@endif
												@endforeach
											</div>
										</div>
									@endif
								@endif
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
				@empty
					<h3 class="text-center">No announcements</h3>
				@endforelse
			</div>

		</div>
		
	</div>
</body>
  
@endsection


@section('after_scripts')
	<script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  	<script>
	    $(document).ready(function() {

	    });
     
  	</script>
@endsection

@section('after_styles')
  	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
