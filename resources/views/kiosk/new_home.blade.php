@extends('kiosk.new_layout')

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">


@endsection

@section('content')
        
      <div class="container">
        <div class="row" style="align-items: center;">

          <div class="school_info col-md-12 col-lg-12 text-center  p-l-10 p-r-10 pull-right" style="display: none;">
            {{-- <img height="150" id="schoolLogo" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" style="display: block; margin: auto;"> --}}

            {{-- <div class="col-lg-12">
              <h2 class="text-center;" id="schoolName">{{ config('settings.schoolname') }}</h2>
              <p class="text-center;" id="schoolAddress">{{ config('settings.schooladdress') }}</p>
            </div> --}}
          </div>
          @if($oldStudentOption)
            @if($oldStudentOption->active || $newStudentOption->active)

              <div class="col-md-7 col-lg-7 text-center p-l-50 p-r-50 pull-left">
                @if($announcement)
                  @if($announcement->active)
                    <h5  style="padding-bottom: 50px;">
                      {{ $announcement->description }}
                    </h5>
                  @endif
                @endif
                @if($oldStudentOption->active)
                  <a href="/kiosk/enlisting/old" class="login100-form-btn btn-success" style="background-color: #28a745 !important;">
                    Old Student
                  </a>
                @endif
                <div class="col-lg-6"><br></div>

                  @if($newStudentOption->active)
                    <a href="/kiosk/enlisting/new" class="login100-form-btn btn-primary" style="background-color: #007bff !important;">
                      New Student
                    </a>
                  @endif
              </div>
            @endif
          @endif

          <div class="col-md-5 col-lg-5 position-relative m-auto text-center">
            <img class="img-responsive monitor" src="{{asset('images/monitor.png')}}" style="width: 100%;">
            <img class="schoollogo img-responsive" id="monitorLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo">
          </div>

        </div>
      </div>


      {{-- </div> --}}
      
@endsection

@section('after_scripts')

  @if(Session::has('message'))
    <script>
      $(document).ready(function () {

        new PNotify({
            title: "Success!",
            text: "{{ Session::get('message') }}",
            type: "success"
        });
              
      })
    </script>
  @endif

@endsection