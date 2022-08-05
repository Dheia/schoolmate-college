@php
  if(backpack_user())
  {
    $extends = backpack_user() && (starts_with(\Request::path(), config('backpack.base.route_prefix'))) ? 'backpack::layout' : 'backpack::layout_guest';
  }
  else {
    $extends = auth()->user() && (starts_with(\Request::path(), 'student')) ? 'backpack::layout_student' : 'backpack::layout_guest';
  }
@endphp

@extends($extends)

@section('after_styles')
  <style>
    .error_number {
      font-size: 156px;
      font-weight: 600;
      color: #dd4b39;
      line-height: 100px;
    }
    .error_number small {
      font-size: 56px;
      font-weight: 700;
    }

    .error_number hr {
      margin-top: 60px;
      margin-bottom: 0;
      border-top: 5px solid #dd4b39;
      width: 50px;
    }

    .error_title {
      margin-top: 40px;
      font-size: 36px;
      color: #B0BEC5;
      font-weight: 400;
    }

    .error_description {
      font-size: 24px;
      color: #B0BEC5;
      font-weight: 400;
    }
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="error_number m-t-80">
        @if(isset($error['image']))
      	 <img class="img-responsive" src="{{ asset($error['image']) }}" alt="Error" style="margin: auto; height: 200px;">
        @else
          <img class="img-responsive" src="{{ asset('/images/error-sorry.png') }}" alt="Error" style="margin: auto; height: 200px;">
        @endif
        <hr>
      </div>
      <div class="error_title">
      	{!! isset($error['title']) ? $error['title'] : 'Page not found.' !!}
      </div>
      <div class="error_description">
        @php
          $default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
        @endphp
        <small>
        	{!! isset($error['description']) ? $error['description'] : $default_error_message !!}
       	</small>
      </div>
    </div>
  </div>
@endsection