
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

  <style type="text/css">
    .fee {
      color: orange;
      font-size: 12px;
      margin-top: -8px;
      display: block;
      margin-right: 10px;
      padding-bottom: 10px;
    }
  </style>
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
          Online Payment
        </span>
        <form class="login100-form validate-form" role="form" method="POST" action="{{ url()->current() }}" style="margin: auto; width: 350px !important;">
          
          {!! csrf_field() !!}
          
          <span class="help-block text-center p-b-12 message-top" style="color: #c58e20; font-size: 12px; display: block;">
          	
          </span>
         

          <div class="wrap-input100 validate-input form-group" >

            <select class="input100" style="outline: none;" name="school_year_id" id="schoolYear">
              <option selected disabled>Select School Year</option>
              @php $flag = false; @endphp
              @foreach($schoolYears as $schoolYear)
                <option value="{{ $schoolYear->id }}" {{ $schoolYear->isActive ? 'selected' : '' }}>{{ $schoolYear->schoolYear }}</option>
              @endforeach
            </select>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>

          </div>

          <div class="wrap-input100 validate-input form-group" >

            <input class="input100" type="number" name="studentnumber" value="{{ old('studentnumber') }}" placeholder="Student Number" autocomplete="off">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-user" aria-hidden="true"></i>
            </span>

          </div>

          <div class="wrap-input100 validate-input form-group" >

            <input class="input100" type="number" step="any" name="amount" value="{{ old('amount') }}" placeholder="Amount" autocomplete="off">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-money" aria-hidden="true"></i>
            </span>
            
          </div>
            <span class="fee text-right">Fee: <span class="amount-fee">0</span></span>


          <div class="wrap-input100 validate-input form-group" >

            <input class="input100" type="text" name="email" value="{{ old('email') }}" placeholder="E-mail">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </span>

          </div>

          <div class="wrap-input100 validate-input form-group" >

            <textarea class="form-control" type="text" name="description" value="{{ old('description') }}" placeholder="Description (optional)" style="background: #e6e6e6;"></textarea>

          </div>

          <div class="col-md-12">
            <span style="color: orange; font-size: 11px;">NOTE: <span>{{ config('settings.paymentnotes') }}</span></span>
          </div>
          
          <div class="container-login100-form-btn">
            <div class="col-md-12">
              <button name="login" class="login100-form-btn" style="z-index: 99;">
            	Make Payment
              </button>
            </div>
          </div>
          
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
<!-- <script src="{{ asset('js/jquery.min.js') }}"></script> -->
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/tilt.jquery.min.js')}}"></script>


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
        

        function delay(callback, ms) {
          var timer = 0;
          return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
              callback.apply(context, args);
            }, ms || 0);
          };
        }

        $('#schoolYear').change(function ( ) { 
          $('input[name="studentnumber"]').val("");
          $('.message-top').text("");
          getFee();
        });

        // Example usage:

        $('input[name="studentnumber"]').keyup(delay(function (e) {
          var _this = $(this);
          $.ajax({
            url: '{{ url()->current() }}/student/' + $(this).val() + '/tuition',
            type: 'get',
            data: {
              _token: "{{ csrf_token() }}",
              school_year_id: $('#schoolYear').val()  
            },
            success: function (response) {
              if(!response.error) {
                $('.message-top').html("<strong>" + response.data.full_name + "</strong><br><strong>Remaining Balance: " + Intl.NumberFormat('en', {style: 'currency' , currency: 'PHP'}).format(response.data.remaining_balance) + "</strong>")
              } else {
                $('.message-top').html('<strong>' + response.message + '</strong>');
              }
            }
          });
        }, 800));

        function getFee () {
          var amount = $('input[name="amount"]');

          if(isNaN(amount.val())) {
            $('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format({{ $fixedAmount }}));
            return;
          }

          $('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(  ( amount.val() * ({{ $fee }}/100) ) + {{ $fixedAmount }}) );
        }

        getFee();

        $('input[name="amount"]').keyup(function () { getFee(); });
        

    })(jQuery);
</script>


</body>
</html>
