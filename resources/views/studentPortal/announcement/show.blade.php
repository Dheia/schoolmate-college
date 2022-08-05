

@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
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
        	<div class="col-md-12">
        		<div class="box">
					<div class="box-body text-center">

						<!-- Message -->
						<h4>{!! $announcement->message !!}</h4>
			
						<!-- Image -->
						@if($announcement->image)
							<br>
							<img src="{{ asset($announcement->image) }}" alt="">
						@endif
						<br>
						<br>
						<!-- Files -->
						@if($announcement->files)
							@if( count($announcement->files) > 0 )
								<h5><b>Files: </b></h5>
								@foreach ( $announcement->files as $file )
									<a href="{{ url($file) }}" target="_blank" download="{{ url($file) }}"> {{ url($file) }} </a>
									@if(!$loop->last)
										<br>
									@endif
								@endforeach
							@endif
						@endif
			
					</div>
					<div class="box-footer text-center">
						Posted By: <b>{{ $announcement->user ? $announcement->user->full_name : '-' }}</b>
					</div>
	            </div>
        	</div>
        </div>

    </div>
    
</div>

  
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
