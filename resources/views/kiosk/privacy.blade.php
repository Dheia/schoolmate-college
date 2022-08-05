@extends('kiosk.new_layout')

@section('after_styles')
	
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  	<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<style>

		.login100-form-title {
			color: #000;
		}
		.form-group label {
			/*color: #ebe965;*/
			color: #000;
		}
		.wrap-login100 {
			position: relative;
			margin-top: 50px;
			overflow: unset;
			padding: 60px;
		}
		.form-control {
			padding: .390rem .75rem !important;
    		font-size: 14px !important;
		}
		.emergency-contact {
			display: none;
		}
		.required {
			color: red;
		}
		.file-cont {
	      width: 100%; 
	      height: 40px; 
	      border: 1px solid #ccc; 
	      border-radius: 5px; 
	      background-color: #ddd; 
	      display: flex; 
	      margin: 10px 0px;
	    }

	    .file-text {
	      padding: 8px;
	    }

	    .file-icon {
	      padding: 5px; 
	      border-radius:  5px; 
	      background-color: #ddd; 
	      text-align: left;
	    }

	    .file-close {
	      float: right !important;
	      position: absolute;
	      right: 5px;
	    }
	</style>
@endsection

@section('content')

	<div class="container">
		<div class="row p-t-50">
			<div class="col-md-12 col-lg-12 p-l-50 p-r-50">
				@if($termsConditions)
					@if($termsConditions->active)
						<div class="ckeditor">
							{!! $termsConditions->description !!}
						</div>
					@endif
				@endif
			</div>
		</div>
	</div>	
		
@endsection


@section('after_scripts')
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
@endsection