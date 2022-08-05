
<!DOCTYPE html>
<html lang="en">
<head>
  <title>
    {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Student Portal' : config('backpack.base.project_name').' Student Portal' }}
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


  @yield('before_styles')
  <!--===============================================================================================-->
  <link rel="icon" type="image/png" href="{{ asset(Config::get('settings.schoollogo')) }}"/>
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-4.1.2.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome-4.7.0.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/animate-3.5.2.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/hamburgers-0.9.3.min.css') }}">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/select2-4.0.5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/jquery-confirm-3.3.2.min.css') }}">
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

  body{
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  footer{
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      text-align: center;
      z-index: 1;
  }

  .sm-logo {
    float: right;
  }
  .credits {
    padding-top: 15px;
  }

  .info-box{
    background-color: white;
    border-radius: 20px;
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
    vertical-align: top;
    padding:50px;
  }

  .loginLogo{
    height: 50px;
  }

  .mobile-app{
    top: -61px;
    left: 20px;
    position: absolute;
    z-index: 0;
    max-height: 300px;
  }

  .mobile-app-info-box{
    /*background-color: #f6c23e;*/
    /*border-radius: 20px;*/
    /*box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);*/
    vertical-align: top;
    padding:20px;
    padding-left: 0px;
  }

  .dl-app-link-cont img{
    width: 120px;
  }

  .dl-app-text{
    font-size: 1rem;
  }

  .footer-nav {
    text-align: left;
    padding-top: 15px;
  }

  .credits {
    text-align: right;
  }

  .info-notes p{
    font-size: 10px;
  }

  .container-login10 {
    padding: 0px;
  }

  .schoolmate-name {
    font-size: 25px;
  }
  
  #schoolLogo {
    height: 80px;
  }

  .text-smo {
    font-size: 10px;
  }

  @media (max-height: 768px) {
    .mobile-app{
      max-height: 200px;
    }
    .login-image {
      width: 500px;
    }
  }

  @media (max-width: 768px) {
    .mobile-app{
      max-height: 130px;
    }
    .dl-app-text{
      font-size: .75rem;
      padding-left: 10px;
    }
    .dl-app-link-cont{
      padding: 0;
    }
    .footer-nav {
      text-align: center;
    }
    .credits {
      text-align: center;
    }
    .login80-form {
      width: 100%;
    }
    .info-box{
      padding:50px 20px 50px 20px;
    }
    .login-image {
      width: 300px;
      
    }
  }

  @media (max-width: 767px) {
    .schoolmate-name {
      font-size: 20px;
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
    .mobile-pb-10 {
      padding-bottom: 10px;
    }

    .login-image {
      width: 200px;
    }

    .dl-app-link-cont img{
      width: 90px;
    }

    .mobile-app-info-box {
      padding: 0px;
    }

    #footer_data p{
      font-size: 8px;
    }   
    #footer_data a{
      font-size: 8px;
    }

    p.schoolmate-desc {
      font-size: 9px;
    }
    p.schoolmate-desc a {
      font-size: 9px;
    }

    .container-login70 {
      padding-top: 0px;
    }



  }

  @media (min-width: 768px) and (max-width: 1200px) {
    .info-box{
      padding-left: 10px !important;
      padding-right: 10px !important;
    }

  }

  @media (min-width: 768px) {
    .hidden-lg{
      display: none;
    }

  }
</style>

@yield('after_styles')
</head>
<body>

<div class="limiter">
  <!-- HEADER -->
  <div class="container-login20 header" style="background: rgb(236, 240, 245);">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-12 col-lg-12 text-center">
          {{-- <img class="" height="50" id="schoolLogo" src="{{ asset('images/logo_schoolmate_alt.png') }}" alt="School Logo"> --}}
          <img class="schoollogo img-responsive" id="schoolLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo" class="loginLogo">
          <h1 class="text-center schoolmate-name">{{ config('settings.schoolname') }}</h1>
          <p class="text-smo text-center text-uppercase schoolmate-desc"><i class="fa fa-home "></i> {{ config('settings.schooladdress') }}</p>
          <p class="text-smo text-center text-uppercase schoolmate-desc"><i class="fa fa-phone"></i>  <a href="tel:{{ config('settings.schoolcontactnumber') }}">{{ config('settings.schoolcontactnumber') }}</a></p>
          <p class="text-smo text-center text-uppercase schoolmate-desc"><i class="fa fa-envelope "></i> <a href="mailto:{{ config('settings.schoolemail') }}">{{ config('settings.schoolemail') }}</a></p>
        </div>
      </div>
    </div>
  </div> 
  <!-- END OF HEADER -->

  <!-- MAIN PANEL -->
  <div class="container-login70" style="background: rgb(236, 240, 245); align-items: flex-start !important;" >
    <div class="container">
      <div class="row">
        <!-- LOGIN PANEL -->
        <div class="col-md-12">
          <div class="info-box">
            <div class="row">
              <div class="col-md-7 col-xs-12 text-center">
                <img class="login-image" src="{{asset('images/login.png')}}" alt="">
              </div>
              <div class="col-md-5 col-lg-5 text-center pull-left login-section">
                <div class="">
                  <h3>Welcome back!</h3><small>Let's start learning!</small>
                  <div style="height: 20px;"></div>
                  <!-- LOGIN FORM -->
                  <form class="login80-form validate-form mx-auto " role="form" method="POST" action="{{ route('student.login.submit') }}" 
                    aria-label="{{ __('Login') }}">
      
                    @csrf 
                    <!-- STUDENTNUMBER FIELD -->
                    <div class="wrap-input100 validate-input form-group{{ $errors->has('studentnumber') ? ' has-error' : '' }}" 
                      data-validate =  " {{ $errors->has('studentnumber') ? $errors->first('studentnumber') : 'Valid Student Number Required' }}">
                        <input id="uintTextBox" class="input100" type="text" name="studentnumber" value="{{ old('studentnumber') }}" placeholder="Student Number" autocomplete="off">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                          <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <!-- PASSWORD FIELD -->
                    <div class="wrap-input100 validate-input form-group{{ $errors->has('password') ? ' has-error' : '' }}" 
                      data-validate = " {{ $errors->has('password') ? $errors->first('password') : 'Password is required' }}">
                      <input class="input100" type="password" name="password" placeholder="{{ trans('backpack::base.password') }}">
                      <span class="focus-input100"></span>
                      <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                      </span>
                    </div>
                    
                    <!-- LOGIN BUTTON -->
                    <div class="container-login100-form-btn">
                          <button class="login100-form-btn">
                            {{ trans('backpack::base.login') }}
                          </button>
                    </div>
      
                    <!-- FORGOT PASSWORD -->
                    <div class="info-notes">
                      <br>
                      <p>Forgot your password? Please contact your school administrator.</p>
                    </div>
      
                    @if ($errors->has('studentnumber'))
                        <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                            <strong>{{ $errors->first('studentnumber') }}</strong>
                        </span>
                    @endif
                  </form>
                  <!-- END OF LOGIN FORM -->
                </div>
                
                <!-- END OF FORGOT PASSWORD -->
                <div class="hidden-lg hidden-md hidden-sm" style="height: 20px;">
                </div>
              </div>
            </div>
          </div>
        </div>

       
        <!-- END OF LOGIN PANEL -->
      </div>

     

    </div>
  </div>
  <!-- END OF MAIN PANEL -->

  <!-- FOOTER -->
  <footer>
    <div class="container-login10 footer" style="background: #064970;z-index:1; ">
      <div class="container">
        <div id="footer_data" class="d-flex justify-content-between align-items-center">
          <p class="text-white"> © 2019 SchoolMATE Online | All right reserved. | <a class="text-white" href="https://schoolmate-online.net/privacy-policy" target="_blank">Privacy</a> | <a class="text-white" href="https://schoolmate-online.net/#contact-area" target="_blank">Contact Us</a></p>
          <div class="d-flex flex-row align-items-center">
                  <!-- MOBILE APP INFO -->
              <div class="row">
                <div class=" col-sm-12 col-md-12 col-lg-12" style="padding: 0 ">
                  <div class="mobile-app-info">
                    <div class="mobile-app-info-box" style="display:flex;">
                      <!-- PLAY STORE / APP STORE LINK -->
                      <div class="col-md-12 col-lg-12 text-center dl-app-link-cont">
                        <p class="text-white">Now Available</p>
                        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.schoolmate_online.schoolmateonline">
                          <img src="{{ asset('images/googleplay.png') }}">
                        </a>
                        <a  target="_blank" href="https://apps.apple.com/ph/app/schoolmate-online/id1485140251">
                          <img src="{{ asset('images/appstore.png') }}">
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END OF MOBILE APP INFO -->
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- END OF FOOTER -->
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

  <!--===============================================================================================-->
  <script>

      (function ($) {
          "use strict";

          
          /*==================================================================
          [ Validate ]*/
          var input = $('.validate-input .input100');

          $('.validate-form').on('submit',function(){
              var check = true;

              for(var i=0; i<input.length; i++) {
                  if(validate(input[i]) == false){
                      showValidate(input[i]);
                      check=false;
                  }
              }

              return check;
          });


          $('.validate-form .input100').each(function(){
              $(this).focus(function(){
                 hideValidate(this);
              });
          });

          function validate (input) {
              if($(input).attr('type') == 'student_number' || $(input).attr('name') == 'student_number') {
                  if($(input).val().trim().match(/^\d*$/) == null) {
                      return false;
                  }
              }
              else {
                  if($(input).val().trim() == ''){
                      return false;
                  }
              }
          }

          function showValidate(input) {
              var thisAlert = $(input).parent();

              $(thisAlert).addClass('alert-validate');
          }

          function hideValidate(input) {
              var thisAlert = $(input).parent();

              $(thisAlert).removeClass('alert-validate');
          }
          
          

      })(jQuery);
  </script>


  <script>
      
    // Restricts input for each element in the set of matched elements to the given inputFilter.
    (function($) {
      $.fn.inputFilter = function(inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
          if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
          } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          }
        });
      };
    }(jQuery));

    // Install input filters.
    $("#uintTextBox").inputFilter(function(value) {
      return /^\d*$/.test(value); });

  </script>

@yield('after_scripts')

</body>
</html>
