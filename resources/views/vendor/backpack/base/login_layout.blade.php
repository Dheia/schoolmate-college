
<!DOCTYPE html>
<html lang="en">
<head>
  <title>
    {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
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
  html,body{
    scroll-behavior:smooth;
  }

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
    /*SCROLL BAR*/
    ::-webkit-scrollbar {
      width: 7px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
    ::-webkit-scrollbar-thumb {
      background: #0458A3;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
    .reveal{
    position: relative;
    transform: translateY(150px);
    opacity: 0;
    transition: 1s all ease;
  }

  .reveal.active{
    transform: translateY(0);
    opacity: 1;
  }
  .notification-logo{
              transform: translateY(-10%);
              animation: floater 3.5s infinite;
              transition: ease 0.5s;
          }
  @keyframes floater {
              0%{transform: translateY(-10%);transition: ease 0.8s;}
              50%{transform: translateY(10%);transition: ease 0.8s;}
          }
  .socmed-logo{
              transform: translateY(-10%);
              animation: floater 3.5s infinite;
              transition: ease 0.5s;
          }
  @keyframes floater {
              0%{transform: translateY(-10%);transition: ease 0.8s;}
              50%{transform: translateY(10%);transition: ease 0.8s;}
          }   
 

    /* FOR MOBILE */
    @media only screen and (max-width:500px) {
      #loginform{
        display:none;
      }
      .section1{
        background-image: linear-gradient(to right bottom, #002591, #002591, #002591, #002591, #002591, #003aa3, #004db4, #0061c4, #008be1, #00b3ef, #00d8f2, #5ffbf1); 
        width: 100% !important; 
        height:100vh !important;
        height:25vh !important;
      }
      .section1-div1{
        width:100% !important;
        padding-top:20px !important;
        height:100vh !important; 
        text-align:center !important; 
      }
      #schoolLogo{
        height:60px !important; 
        border-radius:10vw !important; 
      }
      .schoolmate-name{
        font-size:20px !important;
        margin-top:10px !important;
      }
      .schoolmate-desc{
        font-size:8px !important;
        margin-top:0px !important;
      }
      .section2{
        height:50vh !important;
        width: 100% !important; 
      }
      .what-new{
        font-size:50px !important;
        font-weight:bold !important;
      }
      .text-ads{
        margin-top:0px !important;
        font-size:22px !important;
        font-weight:bold !important; 
      }
      .text-description{
        font-size:11px !important;
        font-weight:300 !important; 
        margin-top:10px !important;
        padding-right:20px !important;
      }
      .section2-div1{
        padding-left:40px !important;
        padding-top:30px !important; 
      }
      .section2-div2{
        padding-left:0px !important;
        padding-top:30px !important; 
      }
      .features-section2{
        font-size:20px !important;
        padding-left:0px !important;
        margin-top:0px !important; 
        font-weight:bold !important;
      }
      .notification-logo{
        margin-top:10px !important;
        margin-left:30px !important;
        height:160px !important;
      }
      .section3{
        height:80vh !important;
        width: 100% !important; 
        padding-top:180px !important;
      }
      .section3-div1{
        height:30vh !important;
        padding-left:40px !important;
        background:#F0F4F7 !important; 
        text-align:left !important; 
        padding-top:20px !important; 
        padding-left:40px !important;
      }
      .section3-div2{
        margin-top:20px !important;
      }
      #payment-logo1{
        margin-left:0px !important; 
        height:50px !important;
      }
      #payment-logo2{
        margin-top:1px !important;
        height:53px !important;
      }
      #payment-logo3{
        height:50px !important;
      }
      .socmed-logo{
        margin-top:10px !important;
        margin-left:30px !important;
        height:160px !important;
      }
      .features{
        font-size:20px !important;
      }
      .section4{
        width: 100% !important; 
        height:100vh !important;
      }
      .section4-div1{
        padding-left:0px !important;
      }
      .section5{
        background:#2D8CFF !important; 
        width: 100% !important; 
        height:70vh !important;
      }
      .section5-div1{
        width:100% !important; 
        text-align:left !important; 
        padding-top:20px !important; 
        padding-left:20px !important;
      }
      .section5-div2{
        padding-left:20px !important;
      }
      .features-section5{
        font-size:20px !important;
        font-weight:bold !important;
        margin-top:20px !important; 
        color:white !important;
      }
      .text-ads-payment{

        margin-top:0px !important;
        font-size:22px !important;
        font-weight:bold !important; 
      }
      .text-description-payment{
        
        font-size:11px !important;
        font-weight:300 !important; 
        margin-top:10px !important;
        padding-right:20px !important;
      }
      .text-ads-section5{
        
        margin-top:0px !important;
        font-size:22px !important;
        font-weight:bold !important; 
      }
      .limiter{
        display:block !important;
      }
      .zoom-pat{
        height:250px !important;
        margin-left:-20px !important; 
        margin-top:40px !important;  
      } 
      .zoom-logo{
        display:none;
      }
    }

      /* FOR DESKTOP */
    @media only screen and (min-width: 768px) {
          .limiter{
            display:none !important;
          }
          .section1{
            background-image: linear-gradient(to right bottom, #002591, #002591, #002591, #002591, #002591, #003aa3, #004db4, #0061c4, #008be1, #00b3ef, #00d8f2, #5ffbf1); 
            width: 100% !important; 
            height:100vh !important;
          }
          #loginform{
            display:block !important;
          }
          .limiter{
            display:none !important;
          }
          .section1-div1{
            width:70% !important; 
            height:100vh !important; 
            text-align:center !important; 
            padding-top:10vw !important;
          }
          #schoolLogo{
            border-radius:10vw !important; 
            height:12vw !important; 
          }
          .schoolmate-name{
            font-size:2.6vw !important; 
            font-weight:bold !important; 
            margin-top:30px !important;
          }
          .schoolmate-desc{
            font-size:1.2vw !important; 
            font-weight:300 !important;
          }
          .section2{
            width: 100% !important; 
            height:100vh !important; 
          }
          .section2-div1{
            width:70% !important; 
            text-align:left !important; 
            padding-top:130px !important; 
            padding-left:100px !important;
          }
          .what-new{
            font-size:60px !important; 
            font-weight:bold !important;
          }
          .section2-div2{
            padding-left:40px !important;
          }
          .features-section2{
            font-size:5vh !important; 
            font-weight:bold !important;
            margin-top:100px !important; 
            color:#666666 !important;
          }
          .features-section5{
            font-size:5vh !important; 
            font-weight:bold !important;
            margin-top:100px !important; 
            color:white !important;
          }
          .text-ads{
            font-weight:bold !important; 
            margin-top:80px !important;
            font-size:2vw !important; 
          }
          .text-description{
            font-weight:300 !important; 
            margin-top:10px !important;
            font-size:1vw !important;
            padding-right:60vh !important;
          }
          .text-description-payment{
            font-weight:300 !important; 
            margin-top:10px !important;
            font-size:1vw !important;
            padding-right:80vh !important;
          }
          .notification-logo{
            margin-top:-15vw !important; 
            margin-left:40vw !important;  
            height:22vw !important;
          }
          .socmed-logo{
            margin-top:-15vw !important; 
            margin-left:35vw !important;  
            height:22vw !important;
          }
          .section3{
            width: 100% !important; 
            height:100vh !important; 
            padding-top:300px !important;
          } 
          .section3-div1{
            background:#F0F4F7 !important; 
            height:22vw !important; 
            text-align:left !important; 
            padding-top:50px !important; 
            padding-left:100px !important;
          }
          .text-ads-payment{
            font-weight:bold !important; 
            font-size:2vw !important; 
          }
          .text-ads-section5{
            margin-top:10vh !important;
            font-weight:bold !important; 
            font-size:2vw !important; 
          }
          .section4{
            width: 100% !important; 
            height:100vh !important;
          }
          .section4-div1{
            padding-left:40px !important;
          }
          .section5{
            background:#2D8CFF !important; 
            width: 100% !important; 
            height:90vh !important;
          }
          .section5-div1{
            width:70% !important; 
            text-align:left !important; 
            padding-top:20px !important; 
            padding-left:100px !important;
          }
          .zoom-pat{
            margin-left:-20px !important; 
            margin-top:40px !important;  
            height:20vw !important;
          }
          .zoom-logo{
            margin-left:-20px !important; 
            margin-top:40px !important; 
            height:20vw !important;
          }
          .section5-div2{
            padding-left:100px !important;
          }
          .zoom-logo{
            display:block !important;
            margin-left: 520px !important;
            margin-top: -390px !important;
            height: 20vw !important;
          }
    }
    
</style>

@yield('after_styles')
</head>
<body>

<!-- LOGIN FORM WEB -->
<div id="loginform" style="z-index:100; background:#ffffff; width:26vw; height:35vw; position:fixed; right:0; margin-right:100px; margin-top:100px; padding:50px; border-radius:10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
  <form class="login100-form validate-form mx-auto" role="form" method="POST" action="{{ route('backpack.auth.login') }}">  
          <span class="login100-form-title">
            <b>School Administrator</b> 
            <br>
            <small>Employee Login</small>
          </span>
          {!! csrf_field() !!}

          <div 
            class="wrap-input100 validate-input form-group{{ $errors->has($username) ? ' has-error' : '' }}" 
            data-validate =  " {{ $errors->has('username') ? $errors->first($username) : 'Valid email is required: ex@abc.xyz' }}" style="margin-top:40px;">

            <input class="input100" type="text" name="{{ $username }}" value="{{ old($username) }}" placeholder="{{ config('backpack.base.authentication_column_name') }}">
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

          <div class="container-login100-form-btn" style="margin-top:70px;">
            <button class="login100-form-btn" style="z-index: 99;">
              {{ trans('backpack::base.login') }}
            </button>
          </div>
            @if ($errors->has($username))
                <span class="help-block text-center" style="color: #c58e20; font-size: 12px;">
                    <strong>{{ $errors->first($username) }}</strong>
                </span>
            @endif
  </form>
</div>


<!-- FIRST TAB -->
<section id="slide">
  <div class="section1" name="id1">
    <div  class="reveal section1-div1">
        <img id="schoolLogo" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" >
        <h1 class="text-white schoolmate-name" >{{ config('settings.schoolname') }}</h1>
        <p class="text-white text-uppercase schoolmate-desc">{{ config('settings.schooladdress') }}</p>
    </div>  
  </div>
</section>

<!-- LOGIN FORM MOBILE -->
<div class="limiter">
  <div class="container-login70" style="background: rgb(236, 240, 245);">
    <div class="container">
      <div class="row" style="align-items: center;">

        <div class="school_info col-md-12 col-lg-12 text-center  p-l-10 p-r-10 pull-right" style="display: none;">
          {{-- <img height="150" id="schoolLogo" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="School Logo" style="display: block; margin: auto;"> --}}

          {{-- <div class="col-lg-12">
            <h2 class="text-center;" id="schoolName">{{ config('settings.schoolname') }}</h2>
            <p class="text-center;" id="schoolAddress">{{ config('settings.schooladdress') }}</p>
          </div> --}}
        </div>
        
        <div class="col-md-7 col-lg-7 text-center pull-left">
          <form class="login100-form validate-form mx-auto" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
            <span class="login100-form-title">
              <b>School Administrator </b>
              <br>
               <small>Employee Login</small>
            </span>
            {!! csrf_field() !!}

            <div 
              class="wrap-input100 validate-input form-group{{ $errors->has($username) ? ' has-error' : '' }}" 
              data-validate =  " {{ $errors->has('username') ? $errors->first($username) : 'Valid email is required: ex@abc.xyz' }}">

              <input class="input100" type="text" name="{{ $username }}" value="{{ old($username) }}" placeholder="{{ config('backpack.base.authentication_column_name') }}">
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

            <div class="container-login100-form-btn">
              <button class="login100-form-btn" style="z-index: 99;">
                {{ trans('backpack::base.login') }}
              </button>
            </div>
              @if ($errors->has($username))
                  <span class="help-block text-center" style="color: #c58e20; font-size: 12px;">
                      <strong>{{ $errors->first($username) }}</strong>
                  </span>
              @endif
          </form>
        </div>
        <div class="col-md-5 col-lg-5 position-relative m-auto text-center">
          <img class="img-responsive monitor" src="{{asset('images/monitor.png')}}" style="width: 100%;">
          <img class="schoollogo img-responsive" id="monitorLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo">
        </div>

      </div>
      
    </div>
  </div>
</div>

<section  id="slide">
  <div class="section2" name="id2">
    <div class="reveal section2-div1" >
        <h1 class="what-new">What's new</h1>
        <div class="section2-div2">
        <h1 class="features-section2" >New Features</h1>

        <h1 class="text-ads">Push Notification on <br>Web, IOS, and Android App</h1>
        <p class="text-uppercase text-description">Pay your school fees from the comfort of your home using GCash, Union Bank, or even in the nearest 7/11 store</p>
        </div>
        <img class="notification-logo" src="https://schoolmate-online.net/mail_images/notification.png" alt="Notification Logo">
    </div>
   
  </div>
</section>

<section id="slide">
  <div class="section3" name="id3">
    <div class="reveal section3-div1">
        <h1 class="text-ads-payment">ONLINE PAYMENT</h1>
        <p class="text-uppercase text-description-payment">Pay your school fees from the comfort of your home using GCash, Union Bank, or even in the nearest 7/11 store</p>

        
          <div class="section3-div2" style="margin-top:40px;">
            <img id="payment-logo1" src="https://schoolmate-online.net/mail_images/gcash-logo.png" alt="payment-logo1" style="margin-left:30px; height:6vw;" >
            <img id="payment-logo2" src="https://schoolmate-online.net/mail_images/ub-logo.png" alt="payment-logo2" style="margin-left:30px; height:6vw;" >
            <img id="payment-logo3" src="https://schoolmate-online.net/mail_images/711-logo.png" alt="payment-logo3" style="margin-left:30px; height:6vw;" >
        </div>
        </div>
  
  </div>
</section>
<section id="slide">
  <div class="section4" name="id4">
    <div class="reveal section2-div1" >
          <div class="section4-div1">
          <h1 class="features-section2" >New Features</h1>

          <h1 class="text-ads" >Social Media <br>Management System</h1>
          <p class="text-uppercase text-description">Get engaged with your teachers and classmates like and comment on their posts in your class
          </p>
          </div>
          <img class="socmed-logo"  src="https://schoolmate-online.net/mail_images/socmed.png" alt="Social Media Logo" >
      </div>
    </div>
  </div>

</section>

<section id="slide">
  <div class="section5" name="id5" >
      <div class="reveal section5-div1">
        <h1 class="features-section5">New Features</h1>
      </div>
      <div class="reveal">
        <img class="zoom-pat" src="https://schoolmate-online.net/mail_images/macbook-zoom-classes.png" alt="" >
        <img class="zoom-logo" src="https://scontent.fcrk1-4.fna.fbcdn.net/v/t1.6435-9/109566726_3261569013864884_3983642245736978396_n.png?_nc_cat=105&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=xuK1xyFILnoAX_FL4ia&_nc_ht=scontent.fcrk1-4.fna&oh=00_AT8kUGtABaCwdBuWg-Z3RQOAfH_a1-PUeocfkhId8NW-cw&oe=62FD5313" alt="Zoom Logo" >
      
        <div class="section5-div2" >
        <h1 class="text-ads-section5 text-white" >Zoom Classes</h1>
        <p class="text-uppercase text-description text-white">Attend your zoom classes in the palm <br>
          of your hand.</p>
      </div>
      </div>
  </div>

</section>
<!-- FOOTER -->
<div class="container-login10 footer" style="background: #222; bottom:0;">
    <div class="container">
      <!-- SchoolMATE Logo is in Top of School Management System -->
      <div id="footer_data" class="d-flex justify-content-between align-items-center">
        <p class="text-white"> © 2019 SchoolMATE Online | All right reserved.</p>
        <div class="d-flex flex-column align-items-center">
          <img class="sm-logo align-self-center" height="40" id="" src="{{ asset('images/logo_schoolmate.png') }}" alt="SMO">
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
<script src="https://code.jquery.com/jquery-3.1.0.js"></script>

  <script>
    jQuery(document).ready(function($) {
      PNotify.prototype.options.styling = "bootstrap3";
      PNotify.prototype.options.styling = "fontawesome";
    });
    window.onload = function(){
      
    reveal();
    setTimeout(function(){
     $('html,body').animate({scrollTop: $("div[name='id1']").offset().top},'slow');
    },500)

    function myFunction(x) {
    if (x.matches) { // If media query matches
        
    }else {
        setTimeout(function(){
          console.log("here2");
          $('html,body').animate({scrollTop: $("div[name='id2']").offset().top},'slow');
        },3000)

        let i = 3;   
        var tid = setTimeout(mycode, 8000);
        function mycode() {
          console.log(i);
          var aTag = $("div[name='id"+ i +"']");
          $('html,body').animate({scrollTop: aTag.offset().top},'slow');
          if( i == 5 ){
            i = 1;
            tid = setTimeout(mycode, 10000);
          }else{
            i++;
            tid = setTimeout(mycode, 10000);
          }               
        }
      } 
    }

var x = window.matchMedia("(max-width: 700px)")
myFunction(x) // Call listener function at run time
x.addListener(myFunction) // Attach listener function on state changes
    
    }
    

   
    function reveal() {
    var reveals = document.querySelectorAll(".reveal");

    for (var i = 0; i < reveals.length; i++) {
      var windowHeight = window.innerHeight;
      var elementTop = reveals[i].getBoundingClientRect().top;
      var elementVisible = 150;

      if (elementTop < windowHeight - elementVisible) {
        reveals[i].classList.add("active");
      } else {
        reveals[i].classList.remove("active");
      }
    }
  }

  window.addEventListener("scroll", reveal);

  </script>

@yield('after_scripts')

</body>
</html>