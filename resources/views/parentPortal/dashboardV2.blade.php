@extends('backpack::layout_parent')

@section('header')
    {{-- <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section> --}}
   
@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{ asset('js/calendar/style.css')}}">
    <link rel="stylesheet" href="{{ asset('js/calendar/theme.css')}}">
    <style type="text/css">
      /*.bio-row {
        width: 50%;
        float: left;
        margin-bottom: 0px;
      }*/
      .padding-left-15 {
        padding-left: 15px;
      }
      .calendar-wrapper {
          padding: 15px;
    
      }
      .pad-top {
        margin-top: 5px;
        padding-top: 5px;
      }
      .panel .panel-body {
        border-width: 1px 1px 2px;
        border-style: solid;
        border-top-color: #ccc!important;
        border-right-color: #ccc!important;
        border-bottom-color: #ccc!important;
        border-left-color: #ccc!important;
      }
      .childrens {
          font-size: 20px;
          font-weight: 800;
      }
      .text-bold {
          font-size: 14px;
          font-weight: 800;
      }
      
      .control-labels{
          margin: 0px;
          padding: 0px;
      }
      .nav-pills>li>a {
        border-radius: 10px;
      }
      .nav-pills>li.active>a {
        border-top-color: #007bff !important;
        color: #ffffff;
        background-color: #007bff !important;
      }
      .box-primary {
        border-top-color: #007bff !important;
      }
    
    
      .ribbon-wrapper .ribbon {
      box-shadow: 0 0 3px rgb(0 0 0 / 30%);
      font-size: .8rem;
      line-height: 100%;
      padding: .375rem 0;
      position: relative;
      text-align: center;
      text-shadow: 0 -1px 0 rgb(0 0 0 / 40%);
      text-transform: uppercase;
      top: 15px;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg);
      width: 90px;
    }
    .ribbon-wrapper {
      height: 160px;
      overflow: hidden;
      position: absolute;
      right: 0px;
      top: 0px;
      width: 120px;
      z-index: 10;
    }
    .ribbon-wrapper.ribbon-lg .ribbon {
      right: 0;
      top: 26px;
      width: 160px;
    }
    
    
    
    .card{
      position: relative;
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 5px 15px 1px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .card:before{
      content: '';
      position: absolute;
      width: 100%;
      height: 250px;
      top: 0;
      left: 0;
      background: #3c8dbc;
      clip-path: circle(370px at 50% -48.5%);
    }
    .header-student{
      position: relative;
      height: 210px;
    }
    
    
    .main{
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    
    .main .image{
    
      position: relative;
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: url('img/profile.jpg') no-repeat center / cover;
      border: 4px solid #346daf;
      margin-bottom: 2px;
      overflow: hidden;
      cursor: pointer;
    }
    
    .image .hover{
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background-color: rgba(79, 172, 254, .7);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      transition: .5s;
      opacity: 0;
    }
    
    .image:hover .hover{
      opacity: 1;
    }
    
    .hover.active{
      opacity: 1;
    }
    
    .name{
      font-size: 1.5rem;
      color: #fff;
      font-weight: 500;
      line-height: 1;
      margin: 5px 0;
      margin-top: 10px;
      text-align:center;
    }
    .student-id{
      font-size: 1.2rem;
      color: #fff;
      font-weight: 500;
      line-height: 1;
      margin: 5px 0;
      margin-top: 10px;
    }
    .sub-name{
      font-family: 'Cutive Mono', monospace;
      font-size: 1.2rem;
      opacity: .8;
      color: #fff;
    }
    
    .content{
      padding: 1.7rem 2.5rem 2.6rem 2.5rem;
      min-height: 180px;
    }
    
    
    .number{
      font-size: 2.1rem;
      font-weight: 200;
      color: #333;
      line-height: 1.2;
    }
    
    .number-title{
      font-size: .55rem;
      color: #666;
      font-weight: 400;
      line-height: 1;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    
    .title{
      position: relative;
      color: #555;
      font-weight: 500;
      font-size: 1.1rem;
      padding: 0 0 3px 0;
      margin-bottom: .9rem;
      display: inline-block;
    }
    
    .title:after{
      content: '';
      position: absolute;
      height: 3px;
      width: 50%;
      background-color: #555;
      bottom: 0;
      left: 0;
    }
    
    .text{
      color: #666;
      font-weight: 300;
      line-height: 1.7;
    }
    
    .icons-container{
      padding: 1rem 0;
    }
    
    .buttons-wrap{
      display: flex;
      margin-bottom: 20px;
      margin-left: 20px;
      margin-right: 20px;
    }
    
    .view-enroll-wrap, .view-profile-wrap{
      flex: 4;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: .5s;
    }
    
    .view-enroll-wrap:hover, .view-profile-wrap:hover{
      flex: 5;
    }
    
    .view-enroll{
      padding: 9.6px 0;
      width: 100%;
      background: #2296d9;;
      color: #fff;
      text-align: center;
      text-decoration: none;
      font-size: 10px;
      letter-spacing: 1px;
      border-radius: 18.1px;
      margin-right: 3px;
    }
    
    .view-profile{
      padding: 7.6px 0;
      width: 100%;
      border: 2px solid #4facfe;
      color: #0075b9;
      text-decoration: none;
      text-align: center;
      font-size: 10px;
      letter-spacing: 1px;
      margin-left: 3px;
      border-radius: 18.1px;
    }
    /* FOR MOBILE RESPONSIVE: */
    @media only screen and (min-width: 768px) {
        #welcomeImage {
          float: right;
        }
        .profile-user-img{
          display: block;
        }
        .profile{
          margin-top:35px
        }
        .card{
          width: auto;
        }
        
        .content-wrapper{
      border-top-left-radius: 60px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
    .column-card{
      width:32% !important;
    }
     
    }
    .main-footer{
      border-bottom-left-radius: 60px;
    
    }
    </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
  <div class="row"> 

    <div class="col-md-9">
      <div class="info-box " style="min-height: 150px;">

        <div class="col-md-4 text-center" id="welcomeImage">
            <img src="{{ asset('images/parentdashboard.png')}}" alt="">
        </div>
  
        <div class="col-md-8">
          <h4>Welcome Message</h4>
          <p>Hi <b></b>! Welcome back and let's learn new things! 
           
          </p>
          {{-- <b>Announcement</b>
          <p><li class="small"><span class="fa-li"></span>No announcement</li></p> --}}
         
        </div>

      </div>
      <div class="childrens-wrapper">
          <p>
            <span class="childrens">My Student</span> 
            <span class="pull-right">
            
            </span>
          </p>
            
      </div>
          <hr>

            {{-- STUDENT DATA   --}}
            @if( count($students) > 0 )
              @php $index = 0; @endphp
              @foreach($students as $student)
                @if($student)
                  @if($index % 4 == 0)
            
                  @endif
                    <div class="col-md-3 column-card">
                      <div class="card">
                              <div class="ribbon-wrapper ribbon-lg">
                                @if($student->is_enrolled == 'Enrolled')
                                  <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-primary text-lg">
                                      <i class="fas fa-lg fa-graduation-cap"></i> Enrolled
                                    </div>
                                  </div>
                                  @else
                                  <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-warning text-lg">
                                      <i class="fas fa-lg fa-graduation-cap"></i> Not Enrolled
                                    </div>
                                  </div>
                                @endif

                              </div>
                                  <div class="header-student">
                                    <h3 class="student-id" style="color:#fff; margin-left:10px;">MDSI-{{ $student->studentnumber }}</h3>
                                       <div class="main">
                                          <div class="image">
                                            @php
                                            $avatar = $student ? $student->photo : 'images/headshot-default.png';
                                            @endphp
                                              
                                            <img  src="{{ url($avatar) }}" style="width: 110px; height: 110px; border-radius: 50%;">
                                              <div class="hover">
                                              
                                                  Profile Picture
                                              </div>
                                          </div>
                                          <h3 class="name"><b>{{  $student->fullname }}</b></h3>
                                          <h3 class="sub-name">{{ $student->current_level }}</h3>
                                        </div>
                                  </div>
                      
                                  <div class="content">
                                      <div class="left">
                                          <div class="about-container">
                                              <h3 class="title">About</h3>
                                              <p class="text">Section : {{ $student->student_section_name }}</p>
                                              <p class="text" style=" margin-top:-10px;">Department: {{ $student->department_name }}</p>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="buttons-wrap">
                                    <div class="view-enroll-wrap">
                                          <a href="{{ url('parent/student-enrollments/' . $student->studentnumber) }}" class="view-enroll">Enrollment</a>
                                    </div>
                                      <div class="view-profile-wrap">
                                          <a href="{{ url('parent/student-information/' . $student->studentnumber) }}" class="view-profile">Profile</a>
                                      </div>
                                  </div>
                         </div>
                     </div>
                    @php $index++; @endphp
                    @if($index%4 == 0)
                  
                
                  @endif
                @endif
              @endforeach
            @endif
            
             
    </div>

     {{-- SCHOOL CALENDAR --}}
     <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">School Calendar</span>
                    @if($upcoming_calendar)
                    <span class="info-box-number">
                      <a href="javascript:void(0)" data-toggle="modal" data-target="#calendarModal" data-backdrop="static">
                        {{ strtoupper($upcoming_calendar->title) }}
                      </a> 
                      <br>
                      <small style="font-size:12px; color: #bbb;">
                        {!! date("F j, Y", strtotime($upcoming_calendar->start_at)) !!} 
                        - 
                        {!! date("F j, Y", strtotime($upcoming_calendar->end_at)) !!}
                      </small>
                    </span>
                  @else
                    <span class="info-box-number">
                      No Upcoming Event
                    </span>
                  @endif
                </div><!-- /.info-box-content -->
             </div>
     
        {{-- CALENDAR --}}
        <div class="info-box calendar-wrapper">
            <div class="calendar-container"></div>
        </div>

           <!-- START OF CALENDAR MODAL -->
           @if($upcoming_calendar)
           <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
             <div class="modal-dialog" role="document">
               <div class="modal-content" style="border-radius: 5px;">
                 <div class="modal-header">
                   <h4 class="modal-title text-primary" id="calendarModalLabel"><b>Upcoming Event</b></h4>
                   <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button> -->
                 </div>
                 <div class="modal-body">
                   <h4> <b> {{ strtoupper($upcoming_calendar->title) }} </b> </h4>
                   <h5 class="m-l-30"> {{ $upcoming_calendar->description }} </h5>
                   <!-- <br> -->
                   <small style="font-size:12px;">
                     <i class="fa fa-calendar-day text-primary"></i>
                     {!! date("F j, Y", strtotime($upcoming_calendar->start_at)) !!} 
                     - 
                     {!! date("F j, Y", strtotime($upcoming_calendar->end_at)) !!}
                   </small>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 </div>
               </div>
             </div>
           </div>
           @endif
           <!-- END OF CALENDAR MODAL -->
    </div>
  </div>
</body>
@endsection

@section('after_scripts')
  <script src="{{ asset('js/calendar/calendar.js')}}"></script>
  <script>
      $('.calendar-container').calendar({
        date:new Date()// today
    });


  </script>
 
@endsection
@section('custom-script')
@endsection



