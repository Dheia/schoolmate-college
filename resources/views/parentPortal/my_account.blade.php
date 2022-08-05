@extends('backpack::layout_parent')

@section('after_styles')
  <style type="text/css">
		/* colors */
		/* tab setting */
		/* breakpoints */
		/* selectors relative to radio inputs */
		html {
		width: 100%;
		height: 100%;
		}

		body {
		background: #efefef;
		color: #333;
		font-family: "Raleway";
		height: 100%;
		}
		body h1 {
		text-align: center;
		color: #2683B9;
		font-weight: 300;
		padding: 40px 0 20px 0;
		margin: 0;
		}

		.tabs {
		left: 50%;
		transform: translateX(-50%);
		position: relative;
		background: white;

		padding: 10px;
		/*padding-bottom: 30px;*/
		/*padding-top: 20px;*/
		width: auto;
		height: auto;
		box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
		border-radius: 5px;
		min-width: 240px;
		}
		.tabs input[name=tab-control] {
		display: none;
		}
		.tabs .content section h2,
		.tabs ul li label {
		font-weight: bold;
		font-size: 18px;
		color: #428BFF;
		}
		.tabs ul {
		list-style-type: none;
		padding-left: 0;
		display: flex;
		flex-direction: row;
		margin-bottom: 0px;
		margin-top: 20px;
		justify-content: space-between;
		align-items: flex-end;
		flex-wrap: wrap;
		}
		.tabs ul li {
		box-sizing: border-box;
		flex: 1;
		width: 25%;
		padding: 0 10px;
		text-align: center;
		}
		.tabs ul li label {
		transition: all 0.3s ease-in-out;
		color: #929daf;
		padding: 5px auto;
		overflow: hidden;
		text-overflow: ellipsis;
		display: block;
		cursor: pointer;
		transition: all 0.2s ease-in-out;
		white-space: nowrap;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		}
		.tabs ul li label br {
		display: none;
		}
		.tabs ul li label svg {
		fill: #929daf;
		height: 1.2em;
		vertical-align: bottom;
		margin-right: 0.2em;
		transition: all 0.2s ease-in-out;
		}
		.tabs ul li label:hover, .tabs ul li label:focus, .tabs ul li label:active {
		outline: 0;
		color: #bec5cf;
		}
		.tabs ul li label:hover svg, .tabs ul li label:focus svg, .tabs ul li label:active svg {
		fill: #bec5cf;
		}
		.tabs .slider {
		position: relative;
		width: 51%;
		transition: all 0.33s cubic-bezier(0.38, 0.8, 0.32, 1.07);
		}
		.tabs .slider .indicator {
		position: relative;
		width: 60px;
		max-width: 100%;
		margin: 0 auto;
		height: 4px;
		background: #2683B9;
		border-radius: 1px;
		}
		.tabs .content {
		margin-top: 15px;
		}
		.tabs .content section {
		display: none;
		-webkit-animation-name: content;
				animation-name: content;
		-webkit-animation-direction: normal;
				animation-direction: normal;
		-webkit-animation-duration: 0.3s;
				animation-duration: 0.3s;
		-webkit-animation-timing-function: ease-in-out;
				animation-timing-function: ease-in-out;
		-webkit-animation-iteration-count: 1;
				animation-iteration-count: 1;
		line-height: 1.4;
		}
		.tabs .content section h2 {
		color: #2683B9;
		display: none;
		}
		.tabs .content section h2::after {
		content: "";
		position: relative;
		display: block;
		width: 30px;
		height: 3px;
		background: #2683B9;
		margin-top: 5px;
		left: 1px;
		}
		.tabs input[name=tab-control]:nth-of-type(1):checked ~ ul > li:nth-child(1) > label {
		cursor: default;
		color: #2683B9;
		}
		.tabs input[name=tab-control]:nth-of-type(1):checked ~ ul > li:nth-child(1) > label svg {
		fill: #2683B9;
		}
		.tabs input[name=tab-control]:nth-of-type(1):checked ~ .slider {
		transform: translateX(0%);
		}
		.tabs input[name=tab-control]:nth-of-type(1):checked ~ .content > section:nth-child(1) {
		display: block;
		}
		.tabs input[name=tab-control]:nth-of-type(2):checked ~ ul > li:nth-child(2) > label {
		cursor: default;
		color: #2683B9;
		}
		.tabs input[name=tab-control]:nth-of-type(2):checked ~ ul > li:nth-child(2) > label svg {
		fill: #2683B9;
		}
		.tabs input[name=tab-control]:nth-of-type(2):checked ~ .slider {
		transform: translateX(100%);
		}
		.tabs input[name=tab-control]:nth-of-type(2):checked ~ .content > section:nth-child(2) {
		display: block;
		}
		@-webkit-keyframes content {
		from {
			opacity: 0;
			transform: translateY(5%);
		}
		to {
			opacity: 1;
			transform: translateY(0%);
		}
		}
		@keyframes content {
		from {
			opacity: 0;
			transform: translateY(5%);
		}
		to {
			opacity: 1;
			transform: translateY(0%);
		}
		}

		.shadow {
		box-shadow: 0 .15rem 0.75rem 0 rgba(124, 127, 158, 0.15);
		}

		.avatar {		
		position:relative;
		display:inline-block;
		}
		.avatar-img {
			
		width: 50%;
		object-fit:cover;
		border-radius:100%;
		box-shadow: 0 .15rem 0.75rem 0 rgba(124, 127, 158, 0.15);
		
		}
		.status {
		width:25px;
		height:25px;
		background:#0ce030;
		border:2px solid white;
		position:absolute;
		bottom:2%;
		right:2%;
		border-radius:100%;
		}

		@media only screen and (min-width: 768px) {
			.col-md-3{
				padding-right:60px;
			}
			.col-md-9{
				margin-left:-50px;
			}
			.avatar{
				/*left: 105px;*/
				width: 100%;
				text-align: center;
			}
			.content-wrapper{
			border-top-left-radius: 60px;
			}
			.sidebar-toggle{
				margin-left:40px;
			}
			.tabs{
				/*height: 450px;*/
			}
		}
		
		.main-footer{
		border-bottom-left-radius: 60px;
		
		}


		
	.card{
    position: relative;
    margin-bottom: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 5px 15px 1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
	}

	.card:before{
		content: '';
		position: absolute;
		width: 100%;
		height: 180px;
		top: 0;
		left: 0;
		background-image: linear-gradient(to top, #3C8DBC, #0FA1F2);
		clip-path:polygon(0 0, 100% 0, 100% 60%, 0% 100%);
	}
	 
	.tabs:before{
		content: '';
		position: absolute;
		width: 100%;
		height: 15px;
		top: 0;
		left: 0;
		background-image: linear-gradient(to top, #3C8DBC, #0FA1F2);
		clip-path:inset(0% 0% 0%);
	
	}
    input[type="text"] {
      box-sizing: border-box;
      width: 100%;
      height: calc(3em + 2px);
      margin: 0 0 1em;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 0.5em;
      background: #fff;
      resize: none;
      outline: none;
    }
    input[type="text"]{
      border-color: #afafaf;
    }
    input[type="text"] + label[placeholder]:before,
    input[type="text"]:valid + label[placeholder]:before {
      transition-duration: 0.2s;
      transform: translate(0, -1.5em) scale(0.9, 0.9);
    }
    
    input[type="text"] + label[placeholder] {
      display: block;
      pointer-events: none;
      line-height: 1.25em;
      margin-top: calc(-3em - 2px);
      margin-bottom: calc((3em - 1em) + 2px);
    }
    input[type="text"] + label[placeholder]:before {
      content: attr(placeholder);
      display: inline-block;
      margin: 0 calc(1em + 0px);
      padding: 0 0px;
      color: #0575d1;
      white-space: nowrap;
      transition: 0.3s ease-in-out;
      background-image: linear-gradient(to bottom, #fff, #fff);
      background-size: 100% 5px;
      background-repeat: no-repeat;
      background-position: center;
    }
	

	input[type="password"] {
      box-sizing: border-box;
      width: 100%;
      height: calc(3em + 2px);
      margin: 0 0 1em;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 1.2em;
      background: #fff;
      resize: none;
      outline: none;
    }
    input[type="password"][required]:focus {
      border-color: #00bafa;
    }
    input[type="password"][required]:focus + label[placeholder]:before {
      color: #00bafa;
    }
    input[type="password"][required]:focus + label[placeholder]:before,
    input[type="password"][required]:valid + label[placeholder]:before {
      transition-duration: 0.2s;
      transform: translate(0, -1.5em) scale(0.9, 0.9);
    }
    input[type="password"][required]:invalid + label[placeholder][alt]:before {
      content: attr(alt);
    }
    input[type="password"][required] + label[placeholder] {
      display: block;
      pointer-events: none;
      line-height: 1.25em;
      margin-top: calc(-3em - 2px);
      margin-bottom: calc((3em - 1em) + 2px);
    }
    input[type="password"][required] + label[placeholder]:before {
      content: attr(placeholder);
      display: inline-block;
      margin: 0 calc(1em + 2px);
      padding: 0 2px;
      color: #898989;
      white-space: nowrap;
      transition: 0.3s ease-in-out;
      background-image: linear-gradient(to bottom, #fff, #fff);
      background-size: 100% 5px;
      background-repeat: no-repeat;
      background-position: center;
    }

  </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
<!-- HEADER START -->
<div class="row" style="padding: 15px;">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
		<section class="content-header">
		  <ol class="breadcrumb">
			<li><a href="{{ url('parent/dashboard') }}">Dashboard</a></li>
			<li><a href="{{ url('parent/parent') }}" class="text-capitalize">Parent</a></li>
			<li class="active">Record</li>
		  </ol>
		</section>
		{{-- <h1 class="smo-content-title">
			<span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
			<small>{{ trans('backpack::crud.edit').' '.$crud->entity_name }}.</small>
		</h1> --}}
	</div>
</div>
<!-- HEADER END -->
<div class="row">
	<div class="col-md-3">
		<!-- Profile Image -->
		  <div class="card" style="border-radius: 10px; padding-bottom:10px;">
			<div class="box-body box-profile">
				  @php
					$avatar = $parent ? $parent->photo : 'images/headshot-default.png';
				  @endphp
				  <div class="avatar" style="width: 100%; text-align: center;">
					<img class="avatar-img" src="{{ url($parent->photo) }}" alt="User profile picture" style="margin-top:40px;  border-radius: 50%; width: 120px;">
					<span class="status"></span>
				  </div>
				  <h3 class="profile-username text-center" style="margin-top:20px;">{{ $parent->firstname }} {{ $parent->lastname }}</h3>

				  {{-- <p class="text-muted text-center bold">{{ $student->prefixed_student_number }}</p> --}}
				  <div class="box-body">
					<strong><i class="fa fa-mobile margin-r-5"></i> Contact No.</strong>
					<p class="text-muted">{{ $parent->mobile  ?? '-' }}</p>
					<hr>
					<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
					<p class="text-muted">{{ $parent->residentialaddress ?? '-' }}</p>
					<hr>
					<strong><i class="fa fa-birthday-cake margin-r-5"></i> Birthday</strong>
					<p class="text-muted">{{ date('F j, Y', strtotime($parent->birthdate)) }}</p>
				  </div>
			</div>
			<!-- /.box-body -->
		  </div>
	</div>
  <div class="col-md-9" >
		<div class="tabs">

			<input type="radio" id="tab1" name="tab-control" @if (!$errors->count()) checked @endif>
			<input type="radio" id="tab2" name="tab-control" @if ($errors->count()) checked @endif>
			<ul>
				<li title="information">
					<label for="tab1" role="button">
						<svg viewBox="0 0 24 24"></svg>
						<br>
						<span>My Information</span>
					</label>
				</li>
				<li title="change_password">
					<label for="tab2" role="button">
						<svg viewBox="0 0 24 24"></svg>
						<br>
						<span>Change Password</span>
					</label>
				</li>
			</ul>

			<div class="slider">
				<div class="indicator"></div>
			</div>
			<div class="content">
				<section>
					<!-- START STUDENT INFORMATION -->
					<div class="panel-body" style="margin-top: 30px;">
					
						<!-- Student's Fullname -->
						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="lastname" value="{{ $parent->lastname }}" readonly="1">
							<label alt='Lastname' placeholder='Lastname'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="firstname" value="{{ $parent->firstname }}" readonly="1" >
							<label alt='Firstname' placeholder='Firstname'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="middlename" value="{{ $parent->middlename }}" readonly="1" >
							<label alt='Middlename' placeholder='Middlename'></label>
						</div>
						<!-- End of Student's Fullname -->

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="contactnumber" value="{{ $parent->mobile }}" readonly="1" >
							<label alt='Contact Number' placeholder='Contact Number'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="email" value="{{ $parent->email }}" readonly="1" >
							<label alt='Email' placeholder='Email'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="gender" value="{{ $parent->gender }}" readonly="1" >
							<label alt='Gender' placeholder='Gender'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="citizenship" value="{{ $parent->citizenship }}" readonly="1" >
							<label alt='Citizenship' placeholder='Citizenship'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="birthdate" value="{{ date("F d, Y", strtotime($parent->birthdate)) }}" readonly="1" >
							<label alt='Date of Birth' placeholder='Date of Birth'></label>
						</div>
						
						<!-- Residential Address -->
						<div class="form-group col-md-4 col-lg-4" >
							<input type="text" name="province" value="{{ $parent->province }}" readonly="1" >
							<label alt='Residential Address In The Philippines ( Province )' placeholder='Residential Address In The Philippines ( Province )'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="city_municipality" value="{{ $parent->city_municipality }}" readonly="1" >
							<label alt='City/Municipality' placeholder='City/Municipality'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="barangay" value="{{ $parent->barangay }}" readonly="1" >
							<label alt='Barangay' placeholder='Barangay'></label>
						</div>

						<div class="form-group col-md-4 col-lg-4">
							<input type="text" name="street_number" value="{{ $parent->street_number }}" readonly="1" >
							<label alt='Street No.' placeholder='Street No.'></label>
						</div>
						<!-- End of Residential Address -->
					</div>
					<!-- END STUDENT INFORMATION -->
				</section>
				<section>
					<form class="form" action="{{url('parent/change-password/submit')}}" method="post">
						{!! csrf_field() !!}
						@if ($errors->count())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $e)
								<li>{{ $e }}</li>
								@endforeach
							</ul>
						</div>
						@endif
						<div class="form-group">
							<input type="password" name="old_password" id="old_password" value=""  required='' style="margin-top:15px;">
							<label alt='Old password' placeholder='Old password'></label>
						</div>
						<div class="form-group">
							<input  type="password" name="new_password" id="new_password" value="" required='' style="margin-top:15px;">
							<label alt='New password' placeholder='New password'></label>
						</div>
						<div class="form-group">
							<input  type="password" name="new_password_confirmation" id="new_password_confirmation" value="" required=''  style="margin-top:15px;">
							<label alt='Confirm password' placeholder='Confirm password'></label>
						</div>
						<button type="submit" class="btn btn-success" style="margin-top:40px;">
							<span class="ladda-label" ><i class="fa fa-save"></i> {{ trans('backpack::base.change_password') }}</span>
						</button>
						<a href="{{ url('parent/dashboard') }}" class="btn btn-default"  style="margin-top:40px;">
							<span class="ladda-label">{{ trans('backpack::base.cancel') }}</span>
						</a>
					
					</form>
				</section>
			</div>
		</div>
  	</div>
</div>
</body>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@push('after_scripts')
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
@endpush
