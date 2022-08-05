
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
    
     
     <div class="col-md-6 p-5 mt-5 box-shadow text-center mx-auto payment-info-container" style="background: #FFF; position: relative;">
      {{-- <i class="fa fa-times fa-5x" style="color: #FFF; background: #60c878; border-radius: 50%; padding: 10px; position: absolute; top: -55px; left: 50%; transform: translateX(-50%);"></i> --}}
       <h3 class="mt-5"><b>Payment Cancelled</b></h3>

       <div class="col-md-12 mt-5 p-5" style="background: #f8f6f6;">
        {{--   <div class=" mx-auto profile-card" style="display: table;">
            <div class="p-1" style="display: table-cell;">
              <img src="{{ asset('images/headshot-default.png') }}" alt="" width="80" class="img-fluid box-shadow" style="border-radius: 50%; box-shadow: 3px 3px 3px #ccc;">
            </div>
            <div class="p-1" style="display: table-cell; vertical-align: middle;">
              <h4 class="text-left">Marlon Tandoc</h4>
              <p class="text-left" style="font-size: 1.2rem;">0114300502</p>
            </div>
          </div>
 --}}
        {{--   <div class="p-5">
            <p class="mb-0" style="font-size: 5em; font-weight: bold;"><span style="font-size: 3rem">PHP</span> 1,000.00</p>
            <p style="font-size: 1.5rem;">Description</p>
          </div> --}}

          <div>
            <a href="{{ url('online-payment') }}" class="btn btn-primary">Make a payment again</a>
          </div>

          <div class="pt-5">
            <img width="150" src="https://ppl.i.lithium.com/t5/image/serverpage/image-id/56084iFE8EEC50D9040CCB/image-size/large?v=1.0&px=999" alt="">
          </div>
       </div>
     </div>
      
  
      <div class="col-md-12 py-5">
          <center><a href="https://schoolmate-online.net"><img class="text-center footer-message" src="{{ asset('images/WIS_LOGO.png') }}" alt="IMG" width="150"></a></center>
          <p class="text-center footer-message">Copyright &copy; 2019</a></p>
          <p class="text-center footer-message">Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a></p>
      </div>

      
    </div>
  </div>


<!--===============================================================================================-->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
<!--===============================================================================================-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
<!--===============================================================================================-->


<!--===============================================================================================-->

</body>
</html>
