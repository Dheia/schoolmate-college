
<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ Config::get('settings.schoolname') }}</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ Config::get('settings.meta_description') }}">
  <meta name="keywords" content="{{ Config::get('settings.meta_keywords') }}">
  <meta name="author" content="">
  <meta property="og:url" content="{{ env('APP_URL') }}" />
  <meta property="og:description" content="{{ Config::get('settings.meta_description') }}">
  <meta property="og:image" content="{{ asset(Config::get('settings.meta_image')) }}">
  <meta name="twitter:title" content="{{ Config::get('settings.meta_title') }}">
  <meta name="twitter:description" content="{{ Config::get('settings.meta_description') }}">
  <meta name="twitter:image" content="{{ asset(Config::get('settings.meta_image')) }}">
  <meta name="twitter:card" content="{{ asset(Config::get('settings.meta_image')) }}">


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

  @media (max-width: 767px) {
    .schoolmate-name {
      font-size: 25px;
    }
    .schoolmate-desc {
      font-size: 12px;
    }
    .header {
      padding-top: 20px;
      padding-bottom: 20px;
    }
    .header-schoolLogo {
       display: block !important;
    }
    #footer_data {
      flex-direction: column-reverse;
    }
    
  }
  .sm-logo {
    float: right;
  }
  .credits {
    padding-top: 15px;
  }
  @media (max-width: 375px) {
    .footer {
      text-align: center;
    }
    .footer .sm-logo {
      text-align: center;
      float: none;
    }
    .sm-desc .p-r-125 {
      text-align: center;
      float: none;
    }
    .sm-desc .pull-right {
      text-align: center;
      padding: 0px;
    }
  }

</style>

@yield('after_styles')
</head>
<body>


<!-- <div class="container-fluid" style="background: #0e6ea6; width: 100%;
    min-height: 20vh; padding: 5vh;">
  <div class="container">
    <div class="col-md-6 col-lg-6 text-center text-white">
      <h1>SchoolMATE|ONLINE</h1>
      <h5 class="text-uppercase">System Management System</h5>
    </div>
  </div>
</div> -->

<div class="limiter">
  <div class="container-login20 header" style="background: #0e6ea6;">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-12 col-lg-12 text-center">
          <img class="header-schoolLogo" height="80" id="schoolLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/headshot-default.png')) }}" alt="School Logo" style="display: block; margin: auto; display: none;">
          <h1 class="text-white text-center schoolmate-name">{{ config('settings.schoolname') }}</h1>
          <p class="text-white text-center text-uppercase schoolmate-desc">{{ config('settings.schooladdress') }}</p>
        </div>
      </div>
    </div>
  </div> 

  <div class="container-login70" style="background: rgb(236, 240, 245);" id="app">
    @yield('content')
  </div>

  <!-- FOOTER -->
  <div class="container-login10 footer" style="background: #222;">
    <div class="container">
      <!-- SchoolMATE Logo is in Top of School Management System -->
      <div id="footer_data" class="d-flex justify-content-between align-items-center">
        <p class="text-white"> © 2019 SchoolMATE Online | All right reserved.</p>
        <div class="d-flex flex-column align-items-center">
          <img class="sm-logo align-self-center" height="40" id="schoolLogo" src="{{ asset('images/logo_schoolmate.png') }}" alt="SMO">
          <p class="text-white">School Management System</p>
        </div>
      </div>
    </div>
  </div> 
  <!-- END OF FOOTER -->
</div>

<!-- <div class="container-fluid" style="background: #1a1a1a; width: 100%;
    min-height: 20vh; padding-top: 9vh;">
  <div class="container">
    <div class="row" style="align-items: center; justify-content: center;">
      <div class="col-md-6 col-lg-6 text-center text-white">
        <h5 class="text-uppercase">System Management System</h5>
      </div>
      <div class="col-md-6 col-lg-6 text-center text-white">
        <h5 class="text-uppercase">System Management System</h5>
      </div>
    </div>
  </div>
</div>
 -->
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
