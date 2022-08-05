@extends('kiosk.new_layout')

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">
  <style type="text/css">
    @media only screen and (max-width: 768px) {
      .department {
        height: 100px !important;
      }
      .back-button {
        height: 50px !important;
      }
    }
  </style>


@endsection

@section('content')
        
      <div class="container">
        <div class="row m-t-20 m-b-20" style="align-items: center;">

          <div class="school_info col-md-12 col-lg-12 text-center  p-l-10 p-r-10 pull-right" style="display: none;">
            {{-- <img height="150" id="schoolLogo" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" style="display: block; margin: auto;"> --}}

            {{-- <div class="col-lg-12">
              <h2 class="text-center;" id="schoolName">{{ config('settings.schoolname') }}</h2>
              <p class="text-center;" id="schoolAddress">{{ config('settings.schooladdress') }}</p>
            </div> --}}
          </div>
          
          <div class="col-md-7 col-lg-7 text-center pull-left">
            @if($enrollmentStatusItems)
              @if(count($enrollmentStatusItems)>0)
                @foreach($enrollmentStatusItems as $item)
                  @if($item->active)
                    <a href="/kiosk/enlisting/{{$type}}/{{ $item->id }}" class="login100-form-btn btn-primary department">
                      {{ $item->enrollment_status->department_name }} {!! $item->enrollment_status->summer ? "<br> (Summer | " . $item->enrollment_status->school_year_name . ")" : '' !!}
                    </a>
                    <div class="col-lg-6"><br></div>
                  @endif
                @endforeach
              @endif
            @endif
          </div>

          <div class="col-md-5 col-lg-5 position-relative m-auto text-center">
            <img class="img-responsive monitor" src="{{asset('images/monitor.png')}}" style="width: 100%;">
            <img class="schoollogo img-responsive" id="monitorLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo">
          </div>

          <div class="col-md-12 col-lg-12 pull-right">
            <a href="/kiosk/enlisting" class="login100-form-btn btn-primary m-t-20 pull-right back-button" style="background-color: #007bff !important;">
              <i class="fa fa-arrow-left pull-left"></i>  Go Back
            </a>
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
            title: 'Success!',
            text: '{{ Session::get('message') }}',
            type: 'success'
        });
              
      })
    </script>
  @endif

@endsection