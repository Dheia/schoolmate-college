
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
  }

  .mobile-app-info-box{
    /*background-color: #f6c23e;*/
    /*border-radius: 20px;*/
    /*box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);*/
    vertical-align: top;
    padding:20px;
    padding-left: 0px;
  }
</style>

@yield('after_styles')
</head>
<body>

<div class="limiter">
  <!-- HEADER -->
  <div class="container-login10 header" style="background: rgb(236, 240, 245);">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-12 col-lg-12 text-center">
          <img class="" height="50" id="schoolLogo" src="{{ asset('images/logo_schoolmate_alt.png') }}" alt="School Logo">
        </div>
      </div>
    </div>
  </div> 
  <!-- END OF HEADER -->

  <!-- MAIN PANEL -->
  <div class="container-login80" style="background: rgb(236, 240, 245); align-items: flex-start !important; padding-top: 0; margin-top: 0px;" >
    <div class="container">
      <div class="row">
        <!-- LEFT PANEL -->
        <div class="col-md-12 col-lg-12 text-center" style="position: relative;">
          <!-- SCHOOL LOGO -->
          <img class="schoollogo img-responsive" id="schoolLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo" class="loginLogo">
          <br>
          <!-- SCHOOL NAME -->
          <h1 class="text-smo text-center schoolmate-name">{{ config('settings.schoolname') }}</h1>
          <!-- SCHOOL ADDRESS -->
          <p class="text-smo text-center text-uppercase schoolmate-desc">{{ config('settings.schooladdress') }}</p>
          <br>
        </div>
        <!-- END OF LEFT PANEL -->

        <!-- REGISTRATION PANEL -->
        <div class="col-md-12 col-lg-12 text-center pull-left login-section">
          <div class="info-box" style="padding: 50px 20px 50px 20px; border-radius: 10px;">
            @if(!session()->has('success'))
              <!-- REGISTRATION FORM -->
              <form class="login100-form validate-form mx-auto " role="form" method="POST" action="{{ route('parent.register.submit') }}" aria-label="{{ __('Register') }}">

                @csrf

                <div class="row">
                  <div class="col-md-12">
                    <!-- FIRSTNAME -->
                    <div class="col-sm-12 col-md-6 pull-left">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('firstname') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('firstname') ? $errors->first('firstname') : 'Valid Firstname Required' }}">
                        <input id="firstname" class="input100-no-icon" type="text" name="firstname" value="{{ old('firstname') }}" placeholder="Firstname (Required)" autocomplete="off">
                        @if ($errors->has('firstname'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                              <strong>{{ $errors->first('firstname') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <!-- LASTNAME -->
                    <div class="col-sm-12 col-md-6 pull-right">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('lastname') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('lastname') ? $errors->first('lastname') : 'Valid Lastname Required' }}">
                        <input id="lastname" class="input100-no-icon" type="text" name="lastname" value="{{ old('lastname') }}" placeholder="Lastname (Required)" autocomplete="off">
                          @if ($errors->has('lastname'))
                            <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                          @endif
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <!-- MOBILE -->
                    <div class="col-sm-12 col-md-6 pull-left">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('mobile') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('mobile') ? $errors->first('mobile') : 'Valid Mobile No. Required' }}">
                        <input id="mobile" class="input100-no-icon" type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile No. (Required)" autocomplete="off">
                        @if ($errors->has('mobile'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                              <strong>{{ $errors->first('mobile') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <!-- TELEPHONE -->
                    <div class="col-sm-12 col-md-6 pull-right">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('telephone') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('telephone') ? $errors->first('telephone') : 'Valid Telephone No. Required' }}">
                        <input id="telephone" class="input100-no-icon" type="text" name="telephone" value="{{ old('telephone') }}" placeholder="Telephone No. (Optional)" autocomplete="off">
                        @if ($errors->has('telephone'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                              <strong>{{ $errors->first('telephone') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <!-- Gender -->
                    <div class="col-sm-12 col-md-6 pull-left">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('gender') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('gender') ? $errors->first('gender') : 'Valid Gender Required' }}">
                        <select name="gender" id="gender" class="input100-no-icon" required style="border: none;">
                          <option value="Male" disabled selected>Select Gender (Required)</option>
                          <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                          <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @if ($errors->has('gender'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                            <strong>{{ $errors->first('gender') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <!-- Birthdate -->
                    <div class="col-sm-12 col-md-6 pull-right">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('birthdate') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('birthdate') ? $errors->first('birthdate') : 'Valid Birthdate Required' }}">
                        <input id="birthdate" class="input100-no-icon" type="date" name="birthdate" value="{{ old('birthdate') }}" placeholder="Birthdate (Required)" autocomplete="off">
                        @if ($errors->has('birthdate'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                            <strong>{{ $errors->first('birthdate') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
               
                <div class="row">
                  <div class="col-md-12">
                    <!-- EMAIL -->
                    <div class="col-sm-12 col-md-12">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('email') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('email') ? $errors->first('email') : 'Valid Email Required' }}">
                        <input id="email" class="input100-no-icon" type="email" name="email" value="{{ old('email') }}" placeholder="Email (Required)" autocomplete="off">
                        @if ($errors->has('email'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                            <strong>{{ $errors->first('email') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- PASSWORD -->
                    <div class="col-md-12">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('password') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('password') ? $errors->first('password') : 'Password is required' }}">
                        <input class="input100-no-icon" type="password" name="password" placeholder="Password (Required)">
                        @if ($errors->has('password'))
                          <span class="help-block text-center" style="color:#e74a3b;  font-size: 12px;">
                            <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- PASSWORD CONFIRMATION -->
                    <div class="col-md-12">
                      <div class="wrap-input100 validate-input form-group{{ $errors->has('password') ? ' has-error' : '' }}" 
                        data-validate="{{ $errors->has('password') ? $errors->first('password') : 'Password is required' }}">
                        <input class="input100-no-icon" type="password" name="password_confirmation" placeholder="Password Confirmation (Required)">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- REGISTER BUTTON -->
                <div class="col-md-12">
                  <div class="container-login100-form-btn">
                        <button class="login100-form-btn" style="border-radius: 5px;">
                          Register
                        </button>
                  </div>
                </div>
              </form>
              <!-- END OF REGISTRATION FORM -->
            @else
              <h1>
                <i class="fa fa-check-circle fa-5x"></i>
              </h1>
              <h2>
                  <b>Your registration has been sent!</b>
              </h2>
              <p class="text-smo text-center schoolmate-desc">
                School administrator will verified your account before you can login.
              </p>
            @endif
          </div>
          <!-- FORGOT PASSWORD -->
          <div class="info-notes">
            {{-- <br> --}}
            <p class="p-t-10">
              <b>Note: </b>Registered account will be verified by your school administrator.
            </p>
          </div>
          <!-- END OF FORGOT PASSWORD -->
        </div>
        <!-- END OF REGISTRATION PANEL -->
      </div>

      <!-- MOBILE APP INFO -->
      {{-- <div class="row">
        <div class="col-md-7 col-lg-7" style="padding: 0 ">
          <div class="mobile-app-info">
            <div class="mobile-app-info-box" style="display:flex;">
              <!-- MOBILE APP PICTURE -->
              <div class="col-md-4 col-lg-4 text-center">
                  <img style="width: 100%;" src="{{ asset('images/mobile.png') }}" class="mobile-app">
              </div>

              <div class="col-md-4 col-lg-4 text-center" style="align-self:center;">
                <h5 style="font-size: 1rem;"><b>DOWNLOAD YOUR APP HERE!</b></h5>
              </div>

              PLAY STORE / APP STORE LINK
              <div class="col-md-4 col-lg-4 text-center" style="padding: 0;">
                <a target="_blank" href="https://play.google.com/store/apps/details?id=com.schoolmate_online.schoolmateonline">
                  <img style="padding-bottom: 10px; width: 100%;" src="{{ asset('images/googleplay.png') }}">
                </a>
                <a  target="_blank" href="https://apps.apple.com/ph/app/schoolmate-online/id1485140251">
                  <img style="width: 100%;" src="{{ asset('images/appstore.png') }}">
                </a>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <!-- END OF MOBILE APP INFO -->

    </div>
  </div>
  <!-- END OF MAIN PANEL -->

  <!-- FOOTER -->
  <div class="container-login10 footer" style="background: #0e6ea6;z-index:1; ">
    <div class="container">
      <div class="col-lg-12 col-md-12" style="display:flex;">
        <div class="col-lg-6 col-md-6" >
          <span class="text-left text-white"><p class="text-white">© 2019 SchoolMATE Online | All rights reserved.</p></span>
        </div>
        <div class="col-lg-6 col-md-6 text-right text-white">
          <span style="padding-left: 20px;"><a class="text-white" href="https://schoolmate-online.net/privacy-policy" target="_blank">Privacy</a></span>
          <span style="padding-left: 20px;"><a class="text-white" href="https://schoolmate-online.net/#contact-area" target="_blank">Contact Us</a></span>
        </div>
      </div>
    </div>
  </div>
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
