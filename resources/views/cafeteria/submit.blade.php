<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Cafeteria | Westfields International</title>
      <!-- Styles -->
      <link href="{{ asset('css/bootstrap4-3-1.min.css') }}" rel="stylesheet">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Font Awesome Icon Kit -->
      <script src="https://kit.fontawesome.com/8e38ce13e4.js" crossorigin="anonymous"></script>
      <!-- Animate Css -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">

      <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- GIJGO FOR DATE PICKER -->
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
      <style>
        @media only screen and (max-width: 640px){
          .btn .btn-outline-secondary .border-left-0{
            height: 38px !important;
          }
        }
        .fab {
          z-index: 9999;
          position: fixed;
          bottom: 1rem;
          right: 1rem;
        }

        .fab_cat {
          z-index: 9999;
          position: fixed;
          top: 1rem;
          left: 1rem;
        }

        .fab:hover {
          animation-name: shake !important;
          animation-duration: 1s !important;
        }

        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            border-radius: 35px;
            font-size: 24px;
            line-height: 1.33;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;

        }

      </style>
     
  </head>
  <body class="skin-blue">
      <div id="app" class="container-fluid">
          <!-- Page Content -->
          <div class="container" id="sidebar">
              <nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(-135deg, #c850c0, #080e7a);">
                  <div class="container d-flex justify-content-center">
                      <a href="{{ url()->current() }}" >
                          <img height="50" src="{{ asset('images/WIS_LOGO.png') }}" class="d-flex justify-content-center" alt="IMG">
                          <center>  
                              <img width="50" height="50" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="IMG" align="center">
                          </center>  
                      </a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" 
                              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                      </button>
                  </div>
              </nav>
          </div>
          <div class="container-fluid" style="padding-top: 110px;">
            <div class="container">
              <form id="formSubmit" name="formSubmit" method="POST" action="{{url('cafeteria/student/login')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <h3 class="w-100 font-weight-bold text-center">Pickup Date</h3>
                
                <div class="d-flex justify-content-center">
                  <input id="cart_pickupDate" width="276" name="cart_pickupDate" value="{{old('cart_pickupDate')}}" />
                  <script>
                      $('#cart_pickupDate').datepicker({
                          uiLibrary: 'bootstrap4'
                      });
                  </script>
                </div>
                @if($errors->has('cart_pickupDate'))
                  <div class="d-flex justify-content-center">
                    <div class="error" style="color: red;"><strong>{{ $errors->first('cart_pickupDate') }}</strong><br></div>
                  </div>
                @endif
                <h3 class="w-100 font-weight-bold text-center mt-5">Sign in</h3>
                <input type="hidden" name="cart_data" id="cart_data">
                @if(session()->has('failed'))
                  <div class="d-flex justify-content-center">
                    <div class="error" style="color: red;"><strong>Credentials not matched!</strong><br></div>
                  </div>
                @endif
                <div class="input-group m-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control" name="studentnumber" id="studentnumber" placeholder="ID No. or Email" aria-label="Studentnumber" aria-describedby="basic-addon1" value="{{old('studentnumber')}}">
                </div>
                 @if($errors->has('studentnumber'))
                    <div class="d-flex justify-content-center">
                      <div class="error" style="color: red;"><strong>{{ $errors->first('studentnumber') }}</strong><br></div>
                    </div>
                  @endif
                <div class="input-group m-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-lock"></i></span>
                  </div>
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                </div>
                @if($errors->has('password'))
                  <div class="d-flex justify-content-center">
                    <div class="error" style="color: red;"><strong>{{ $errors->first('password') }}</strong><br></div>
                  </div>
                @endif
          
                <button type="submit" class="btn btn-primary w-100 m-3">Login</button>
              </form>
            </div>
              <!-- /.row -->
          </div>
          <!-- /.container -->

          <!-- Main Footer -->
          <footer class="main-footer mt-5">
             <div class="col-md-12">
                <p class="text-center footer-message"><i class="fa fa-mobile-phone"></i> +63 917 510 0002<br>
                  <i class="fa fa-address-book"></i>  Cutcut, Angeles City, Philippines<br>
                  Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a></p>
              </div>
          </footer>

          <!-- Success Submit Modal Start-->
          <div class="modal" tabindex="-1" role="dialog" id="successModal">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                  <i style="color:black;" class="fas fa-check-circle fa-10x"></i>
                  <h2>Order Submitted</h2>
                </div>
              </div>
            </div>
          </div>
          <!-- Success Submit Modal End -->
      </div>
     

      <script src="{{ asset('js/jquery.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
      <!-- jQuery 2.1.3 -->
      <script src="{{ asset ('vendor/adminlte/dist/js/adminlte.min.js') }}" type="text/javascript"></script>
      <script type="text/javascript">
      //   $(document).ready(function() {
      //     if(cart.length == 0)
      //     {
      //       window.location.href = "{{asset('cafeteria')}}";
      //     }
      // });
        function myFunction() {
          var x = document.getElementById("myDIV");
          if (x.style.display === "none") {
            x.style.display = "block";
          } else {
            x.style.display = "none";
          }
        }
        var cart = [];
        var cart_data = [];
        $(function () {
            if (localStorage.cart)
            {
              cart = JSON.parse(localStorage.cart);
            }
            $('#cart_data').val(JSON.stringify(cart));
        });
      </script> 

      <script type="text/javascript">
        @if (count($errors) > 0)
          @if($errors->has('cart_pickupDate'))
            $('#pickupDateModal').modal('show');
          @elseif ($errors->has('studentnumber') || $errors->has('password'))
            $('#loginModal').modal('show');
          @endif
        @endif
      </script>
      <script type="text/javascript">
        @if(session()->has('success'))
           $('#successModal').modal('show');
           localStorage.clear();
        @endif
        @if(session()->has('failed'))
           $('#loginModal').modal('show');
        @endif
      </script>
  </body>
</html>
