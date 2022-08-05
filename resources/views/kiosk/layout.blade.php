
<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ Config::get('settings.schoolname') }}</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  @yield('before_styles')
  <!--===============================================================================================-->
  <link rel="icon" type="image/png" href="{{ asset(Config::get('settings.schoollogo')) }}"/>
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/0.9.3/hamburgers.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/kiosk.css') }}">
  {{-- <link rel="stylesheet" type="text/css" href="css/main.css"> --}}
<!--===============================================================================================-->

<style type="text/css">

  /*//////////////////////////////////////////////////////////////////
  [ FONT ]*/

  @font-face {
    font-family: Poppins-Regular;
    src: url("{{ asset('fonts/Poppins-Regular.ttf') }}"); 
  }

  @font-face {
    font-family: Poppins-Bold;
    src: url("{{ asset('fonts/Poppins-Bold.ttf') }}"); 
  }

  @font-face {
    font-family: Poppins-Medium;
    src: url("{{ asset('fonts/Poppins-Medium.ttf') }}"); 
  }

  @font-face {
    font-family: Montserrat-Bold;
    src: url("{{ asset('fonts/Montserrat-Bold.ttf') }}"); 
  }

</style>

@yield('after_styles')
</head>
<body>


<div class="limiter">
    <div class="container-login100">

      <img height="150" id="schoolLogo" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" style="display: block; margin: auto;">

      <div class="col-lg-12">
        <h2 style="text-align: center; color: #FFF;" id="schoolName">{{ config('settings.schoolname') }}</h2>
        <p style="text-align: center; color: #FFF;" id="schoolAddress">{{ config('settings.schooladdress') }}</p>
      </div>

      <div class="wrap-login100" id="app">

        @yield('content')

      </div>
    </div>
  </div>

@yield('before_scripts')

<!--===============================================================================================-->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!--===============================================================================================-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

  <script>
    jQuery(document).ready(function($) {
      PNotify.prototype.options.styling = "bootstrap3";
      PNotify.prototype.options.styling = "fontawesome";
    });
  </script>
  
  <script >

    $('.js-tilt').tilt({
      scale: 1.1
    })
  </script>

@yield('after_scripts')

</body>
</html>
