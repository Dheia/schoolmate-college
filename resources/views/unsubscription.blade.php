@extends('errors.layout')

@php
  $error_number = 404;
@endphp

@section('title')
  Get full access!
@endsection

@section('description')
	@if(env('APP_DEBUG'))
		@php
			$default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
		@endphp
			{!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
	@else
		<h1>You are not subscribe to this service. To get full features, contact sales@tigernethost.com or call (045) 409 8336 or visit our website at <a href="https://tigernethost.com">https://tigernethost.com</a> </h1>
	@endif
@endsection