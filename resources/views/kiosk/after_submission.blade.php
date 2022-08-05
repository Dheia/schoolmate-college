@extends('kiosk.new_layout')

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">


@endsection

@section('content')
        
      <div class="container">
        <div class="row" style="align-items: center;">

        <div class="col-md-10 col-lg-10 text-center" style="margin: auto; position: relative;">
            @if($additionalPage)
            {!! $additionalPage->description !!}
            @endif
            <br>
            <br>
            <a href="{{ url('kiosk/enlisting') }}" class="login100-form-btn btn-success" style="background-color: #28a745 !important;">
              OK
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