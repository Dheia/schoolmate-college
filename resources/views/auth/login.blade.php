
<!DOCTYPE html>
<html lang="en">
<head>
  <title>SchoolMATE Online</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
  <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png')}}"/>
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
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">

  <link rel="stylesheet" type="text/css" href="{{ asset('css/student-portal/login.css') }}">
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

</head>
<body>

  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <div class="login100-pic js-tilt" data-tilt>
          <a href="{{ url()->current() }}">
            <img src="{{ asset('images/WIS_LOGO.png') }}" alt="IMG">
            <center>  
              <img width="100" src="../{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center">
          </center>  
          </a>
        </div>

        <form class="login100-form validate-form" role="form" method="POST" action="{{ route('student.login.submit') }}" aria-label="{{ __('Login') }}">
          <span class="login100-form-title">
            Student Login
          </span>
          
          @csrf            
  
          <div 
            class="wrap-input100 validate-input form-group{{ $errors->has('student_number') ? ' has-error' : '' }}" 
            data-validate =  " {{ $errors->has('student_number') ? $errors->first('student_number') : 'Valid Student No. Is Required: 11420010' }}">

            <input id="uintTextBox" class="input100" type="text" name="studentnumber" value="{{ old('student_number') }}" placeholder="Student Number" autocomplete="off">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-user" aria-hidden="true"></i>
            </span>

          </div>

           

          <div 
            class="wrap-input100 validate-input form-group{{ $errors->has('password') ? ' has-error' : '' }}" 
            data-validate = " {{ $errors->has('password') ? $errors->first('password') : 'Password is required' }}">

            <input class="input100" type="password" name="password" placeholder="{{ trans('backpack::base.password') }}">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
          </div>

          <div class="container-login100-form-btn">
            <button class="login100-form-btn">
              {{ trans('backpack::base.login') }}
            </button>
          </div>
            @if ($errors->has('student_number'))
                <span class="help-block text-center" style="color: #c58e20; font-size: 12px;">
                    <strong>{{ $errors->first('student_number') }}</strong>
                </span>
            @endif

         {{--  <div class="text-center p-t-12">
            <span class="txt1">
              Forgot
            </span>
            <a class="txt2" href="#">
              Username / Password?
            </a>
          </div> --}}

         {{--  <div class="text-center p-t-136">
            <a class="txt2" href="#">
              Create your Account
              <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
            </a>
          </div> --}}
        </form>
      </div>
      
        <div class="col-md-12">
          <p class="text-center footer-message"><i class="fa fa-mobile-phone"></i> {{ Config::get('settings.schoolcontactnumber') }}</p>
          <p class="text-center footer-message"><i class="fa fa-address-book"></i>  {{ Config::get('settings.schooladdress') }}</p>
          <p class="text-center footer-message">Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a></p>
        </div>
    </div>
  
  </div>




<!--===============================================================================================-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--===============================================================================================-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js"></script>
  <script >
    $('.js-tilt').tilt({
      scale: 1.1
    })
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

</body>
</html>
