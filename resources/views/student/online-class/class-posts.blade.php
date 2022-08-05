@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')

  <div class="row p-l-20 p-r-20">
    @include('student/online-class/partials/navbar')
  </div>
  
  <div class="row p-l-20 p-r-20">
    <!-- START RIGHT SIDEBAR -->
    <div class="col-md-4 col-lg-4 col-two">
      <!-- START CLASS INFORMATION -->
      @include('student/online-class/partials/class_information')
      <!-- END CLASS INFORMATION -->

      <!-- Start Quipper Account -->
      {{-- @include('student/online-class/partials/quipperAccount') --}}
      <!-- END Quipper Account -->
    </div>
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">
      
      <!-- START SECTION CLASS POSTS -->
      <class-posts-student :school_id="'{{ env('SCHOOL_ID') }}'" :code="{{ json_encode($class->code) }}" :user="{{ $user }}" 
        :user_id="{{ $user->id }}" :submitted_quiz="{!! json_encode($studentSubmittedQuiz) !!}"
        :spaces_url="'{{ env('DO_SUBDOMAIN') }}'">
      </class-posts-student>
      <!-- END SECTION CLASS POSTS -->
    </div>
  
  </div>
@endsection

@push('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">

  <style>

    .row-profile{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .row-text{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .column-text-right{
      display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-end;
    }
    .column-text-left{
    display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-start;
    }
  
    .column-profile-name{
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-left: 10px;
    }

     .card{
      margin-bottom: 10px;
      background-color: #E6E6F3;
      border-radius: 13px;
      display: flex;
      flex-direction: row;
      width: 100%;

    }
    .column {
      display: flex;
      flex-direction: column;
      flex-basis: 100%;
      flex: 1;
      width: 100%;
      align-items: flex-start;
      
    }
    .column-icon {
      display: flex;
      flex-direction: column;
      flex-basis: 40%;
      flex: 0 0 60px;
      padding: 7px;
      padding-left: 13px;
      width: 90%;
    }
 
    .card-icon{
      background-color: #fff;
      border-radius: 10px;
      width: 40px;
      height: 40px;
    }
    .column-profile-pic{
        display: flex;
        flex-direction: column;
      }
    .font-serif-bold{
      font-family:Arial,Helvetica,sans-serif;
      font-size: 13px;
      color:rgb(27, 27, 27);
      font-weight: bold;
    }
    .auto-center{
      margin-top:auto;
      margin-bottom: auto;
    }
    .profile-pic{
      max-width: 65px;
      width: 100%;
      border-radius: 100%;
      overflow: hidden;
      padding: 0px;
      margin: 0px;
      border: 1.5px #d2d6de solid;
      border-radius: 100%;
    }
    .profile-pic:hover{
      border: 1.5px #3C8DBC solid;
    }
    .btn_comments{
      border: 1px #2c3758 solid;
    }

    
    @media only screen and (min-width: 768px) {
      /* For desktop: */
    .content-wrapper{
        border-top-left-radius: 50px;
        }
      .sidebar-toggle{
        margin-left:30px;
      }
    }
    .main-footer{
      border-bottom-left-radius: 50px;
    
    }
  
  </style>
@endpush

@push('before_scripts')
   {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/newsfeed.js') }}"></script>
@endpush

@push('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-explore").classList.add("active");
  </script>
  <!-- VUE JS -->
  {{-- <script src="{{ mix('js/onlineclass/newsfeed.js') }}"></script> --}}

  <!-- HTML5 QR SCRIPT -->
  <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
@endpush
