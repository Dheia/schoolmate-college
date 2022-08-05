
<!DOCTYPE html>
<html lang="en">
<head>
  <title>
    {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
  </title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">


<!--===============================================================================================-->
  <link rel="icon" type="image/png" href="{{ Config::get('settings.schoollogo')}}"/>
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap4.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/hamburgers.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
  <!-- <link rel="stylesheet" type="text/css" href="css/main.css"> -->
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css"href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="limiter">
    <div class="container-login100">
    
     
      
  
      
      <div class="wrap-login100" style="z-index: 98;">
        <div class="schoolinfo">
          <h1 class="schoolname">{{ Config::get('settings.schoolname') ?? "Tigernet International School" }}</h1>
          <p class="text-center footer-message"> {{ Config::get('settings.schoolcontactnumber') ?? "322 San Roque, Guagua, Pampanga, Philippines, 2003" }}</p>
          <p class="text-center footer-message">  {{ Config::get('settings.schooladdress') ?? "0917 510 0074"}}</p>
        </div>
     {{--    <div class="login100-pic js-tilt" data-tilt>
          <a href="{{ url()->current() }}">
            <center>  
              <img width="250" src="../{{ Config::get('settings.schoollogo') ?? asset('images/schoolmate_logo.jpg') }}" alt="IMG" align="center">
          </center>  
          </a>
        </div>
 --}}
        <span class="login100-form-title">
          Employee Online Attendance
        </span>
        <form class="login100-form validate-form" role="form" method="POST" action="{{ url()->current() }}" style="margin: auto;">
          
          {!! csrf_field() !!}
          
          @if(Session::has('message'))
            <span class="help-block text-center p-b-12" style="color: #c58e20; font-size: 12px; display: block;">
                <strong>{{ Session::get('message') }}</strong>
            </span>
          @endif

          <div class="wrap-input100 validate-input form-group" >

            <input class="input100" type="text" name="username" value="{{ old('username') }}" placeholder="{{ config('backpack.base.authentication_column_name') }}">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-envelope" aria-hidden="true"></i>
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
          
          
          <div id="datetime" class="text-center text-white p-t-12 p-b-12"></div>

          <div class="container-login100-form-btn">
            <div class="col-md-6">
              <button name="login" class="login100-form-btn" style="z-index: 99;">
                {{ trans('backpack::base.login') }}
              </button>
            </div>
            <div class="col-md-6">
              <button name="logout" class="login100-form-btn" style="z-index: 99;">
                {{ trans('backpack::base.logout') }}
              </button>
            </div>
          </div>
          
    {{--         @if ($errors->has($username))
                <span class="help-block text-center" style="color: #c58e20; font-size: 12px;">
                    <strong>{{ $errors->first($username) }}</strong>
                </span>
            @endif --}}

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
          <center><a href="https://schoolmate-online.net"><img class="text-center footer-message" src="{{ asset('images/WIS_LOGO.png') }}" alt="IMG" width="200"></a></center>
          <p class="text-center footer-message">Copyright &copy; 2019</a></p>
          <p class="text-center footer-message">Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a></p>
        </div>
    </div>
  </div>

  <div class="image_lower_right"></div>


<!--===============================================================================================-->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
<!--===============================================================================================-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
<!--===============================================================================================-->
  <script src="{{ asset('js/tilt.jquery.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>

  <script type="text/javascript">
    var datetime = null,
        date = null;

    var update = function () {
        date = moment(new Date())
        datetime.html(date.format('ddd, MMMM D, YYYY | h:mm:ss a'));
    };

    $(document).ready(function(){
        datetime = $('#datetime')
        update();
        setInterval(update, 1000);
    });

  </script>

  <script >
    $('.js-tilt').tilt({
      scale: 1
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
              if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                  if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
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

</body>
</html>
