@extends('kiosk.layout')

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">
@endsection

@section('content')

      {{-- <div class="wrap-login100"> --}}
    {{--     <img height="150" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" style="display: block; margin: auto;">

        <div class="col-lg-12">
          <h2 style="text-align: center; color: #FFF;">{{ config('settings.schoolname') }}</h2>
          <p style="text-align: center; color: #FFF;">{{ config('settings.schooladdress') }}</p>
        </div> --}}
        
        <div class="col-lg-12">
          <br>
          <br>
        </div>
          
        <span class="login100-form-title" style="padding-bottom: 0;">
          @if(env('KIOSK_NEW_STUDENT'))
            New
          @endif 
          @if(env('KIOSK_OLD_STUDENT') && env('KIOSK_NEW_STUDENT'))
            Or
          @endif
          @if(!env('KIOSK_OLD_STUDENT') && !env('KIOSK_NEW_STUDENT'))
            UNAUTHORIZED ACCESS
          @endif 
          @if(env('KIOSK_OLD_STUDENT'))
            Old?
          @endif
        </span>

        <div class="container-login100-form-btn">

          @if(env('KIOSK_OLD_STUDENT'))
            <a href="/kiosk/enlisting/old" class="login100-form-btn btn-success" style="background-color: #28a745 !important;">
              Old Student
            </a>
          @endif
          <div class="col-lg-12"><br></div>

          @if(env('KIOSK_NEW_STUDENT'))
            <a href="/kiosk/enlisting/new" class="login100-form-btn btn-primary" style="background-color: #007bff !important;">
              New Student
            </a>
          @endif

        </div>


      {{-- </div> --}}
      
@endsection

@section('after_scripts')

  @if(Session::has('message'))
    <script>
      $(document).ready(function () {

        new PNotify({
            title: 'Success!',
            text: '{{ Session::get('message') }}',
            type: 'success'
        });
              
      })
    </script>
  @endif

@endsection