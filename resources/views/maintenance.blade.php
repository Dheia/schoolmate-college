@extends('backpack::layout')

@php
  $error_number = 503;
@endphp

@section('title')
  Maintenance Mode
@endsection

@section('content')
<center>	
	<div class="col-md-12">
		@if(env('APP_DEBUG'))
			@php
				$default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
			@endphp
				{!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
		@else
		<div style="position: relative; margin-top: 90px;">
			<img src="https://yoast.com/app/uploads/2013/03/HTTP_503_FIklein.png" alt="maintenance">	
			<h1 style="
			            background-color: rgba(255, 255, 255, 0.4);
			            padding: 40px 50px; 
			            position: absolute; 
			            top: 50%; 
			            left: 50%;
			            color: #bd2b44;
			            transform: translate(-50%, -50%);">
            	<span style="font-weight: bold">ERROR</span> <br> 
            	<span style="font-size: 100px;">{{ $error_number }}</span> <br> 
            	<hr style="margin-top: 20px; margin-bottom: 20px; border-top: 5px solid #bd2b44; width: 50px;">
            	<span style="color: #a33c4d;">{!! $data->message !!}</span>
        	</h1>
		</div>
		@endif
	</div>
</center>
@endsection