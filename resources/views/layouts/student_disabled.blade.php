<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>
    SchoolMATE Online
  </title>
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

  <link rel="icon" type="image/png" href="{{ asset(Config::get('settings.schoollogo')) }}"/>
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-4.1.2.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome-4.7.0.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/animate-3.5.2.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" href="{{ asset('css/jquery-confirm-3.3.2.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/kiosk.css') }}">

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

  .sm-logo {
    float: right;
  }
  .credits {
    padding-top: 15px;
  }

  #monitorLogo {
    position: absolute;
    min-height: 150px;
    top: 35%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  @media (max-width: 1000px) {
    #monitorLogo {
      min-height: 110px;
    }
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

  .schoolmate-name {
    color: #0e6ea6;
  }
</style>

  <style>
    .smo_number {
      font-size: 156px;
      font-weight: 600;
      color: #dd4b39;
      line-height: 100px;
    }
    .smo_number small {
      font-size: 56px;
      font-weight: 700;
    }

    .smo_number hr {
      margin-top: 60px;
      margin-bottom: 0;
      border-top: 5px solid #dd4b39;
      width: 50px;
    }

    .smo_title {
      margin-top: 40px;
      font-size: 36px;
      color: #B0BEC5;
      font-weight: 400;
    }

    .smo_description {
      font-size: 24px;
      color: #B0BEC5;
      font-weight: 400;
    }
  </style>
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} fixed">

  <div class="limiter">
    <div class="container-login20 header" style="background: #0e6ea6;">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-12 col-lg-12 text-center">
            <img class="header-schoolLogo" height="80" id="schoolLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo" style="margin: auto; display: block;">
            <h1 class="text-white text-center schoolmate-name">{{ config('settings.schoolname') }}</h1>
            <p class="text-white text-center text-uppercase schoolmate-desc">{{ config('settings.schooladdress') }}</p>
          </div>
        </div>
      </div>
    </div> 
    <div class="container-login70" style="background: rgb(236, 240, 245);">
      <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
              <!-- SCHOOL LOGO -->
              <img class=" img-responsive" id="schoolLogo" src="{{ asset('images/logo_schoolmate.png') }}" alt="SchoolMATE Online" class="">
              <br>
              <br>
              <h1 class="text-smo text-center schoolmate-name">SchoolMATE Online</h1>
              <p class="text-smo text-center schoolmate-desc">
                Your Account has been disabled
              </p>
              <p class="text-smo text-center schoolmate-desc">
                Kindly contact your accounting department or school administrator <br>
              </p>
              <!-- <a href="mailto:accounting@tigernethost.com">accounting@tigernethost.com</a> <br>
                  <a href="tel:09171744014">(0917) 174 4014</a> -->
            </div>
          </div>
        
      </div>
    </div>
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
        <!-- SchoolMATE Logo is Align With School Management System -->
        {{-- <div class="d-flex align-items-center">
          <p class="text-white mr-auto"> © 2019 SchoolMATE Online | All right reserved.</p>
          <img class="sm-logo align-self-center" height="30" id="schoolLogo" src="{{ asset('images/logo_schoolmate.png') }}" alt="SMO">
          <p class="text-white">School Management System</p>
        </div> --}}
      </div>
    </div> 
  </div>

  @yield('before_scripts')

  <!--===============================================================================================-->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}"></script>
  <script src="{{ asset('js/popper-1.14.3.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-4.1.2.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('js/tilt.jquery-1.2.1.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.js') }}"></script>
  <script src="{{ asset('js/jquery-confirm-3.3.2.min.js') }}"></script>

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