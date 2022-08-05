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
  <style type="text/css">
    /*.bio-row {
      width: 50%;
      float: left;
      margin-bottom: 0px;
    }*/
    .padding-left-15 {
      padding-left: 15px;
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

    input[type="text"] {
      box-sizing: border-box;
      width: 100%;
      height: calc(3em + 2px);
      margin: 0 0 1em;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 1.2em;
      background: #fff;
      resize: none;
      outline: none;
    }
    input[type="text"][required]:focus {
      border-color: #00bafa;
    }
    input[type="text"][required]:focus + label[placeholder]:before {
      color: #00bafa;
    }
    input[type="text"][required] + label[placeholder]:before,
    input[type="text"][required]:valid + label[placeholder]:before {
      transition-duration: 0.2s;
      transform: translate(0, -1.5em) scale(0.9, 0.9);
    }
    input[type="text"][required]:invalid + label[placeholder][alt]:before {
      content: attr(alt);
    }
    input[type="text"][required] + label[placeholder] {
      display: block;
      pointer-events: none;
      line-height: 1.25em;
      margin-top: calc(-3em - 2px);
      margin-bottom: calc((3em - 1em) + 2px);
    }
    input[type="text"][required] + label[placeholder]:before {
      content: attr(placeholder);
      display: inline-block;
      margin: 0 calc(1em + 2px);
      padding: 0 2px;
      color: #898989;
      white-space: nowrap;
      transition: 0.3s ease-in-out;
      background-image: linear-gradient(to bottom, #fff, #fff);
      background-size: 100% 5px;
      background-repeat: no-repeat;
      background-position: center;
    }



    input[type="password"] {
      box-sizing: border-box;
      width: 100%;
      height: calc(3em + 2px);
      margin: 0 0 1em;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 1.2em;
      background: #fff;
      resize: none;
      outline: none;
    }
    input[type="password"][required]:focus {
      border-color: #00bafa;
    }
    input[type="password"][required]:focus + label[placeholder]:before {
      color: #00bafa;
    }
    input[type="password"][required] + label[placeholder]:before,
    input[type="password"][required]:valid + label[placeholder]:before {
      transition-duration: 0.2s;
      transform: translate(0, -1.5em) scale(0.9, 0.9);
    }
    input[type="password"][required]:invalid + label[placeholder][alt]:before {
      content: attr(alt);
    }
    input[type="password"][required] + label[placeholder] {
      display: block;
      pointer-events: none;
      line-height: 1.25em;
      margin-top: calc(-3em - 2px);
      margin-bottom: 18px;
    }
    input[type="password"][required] + label[placeholder]:before {
      content: attr(placeholder);
      display: inline-block;
      margin: 0 calc(1em + 2px);
      padding: 0 2px;
      color: #898989;
      white-space: nowrap;
      transition: 0.3s ease-in-out;
      background-image: linear-gradient(to bottom, #fff, #fff);
      background-size: 100% 5px;
      background-repeat: no-repeat;
      background-position: center;
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
        height: 270px;
        top: 0;
        left: 0;
        background: #3c8dbc;
        clip-path: circle(400px at 50% -48.5%);
    }
    .header-student{
        position: relative;
        height: 265px;
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
        min-height: 220px;
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


    .buttons-wrap{
        display: flex;
        margin-top: 5px;
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
        background: #2296d9;
        color: #fff;
        text-align: center;
        text-decoration: none;
        font-size: .7rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-radius: 18.1px;
        margin-right: 3px;
    }

    .view-profile{
        padding: 7.6px 0;
        width: 100%;
        border: 2px solid #4facfe;
        color: #4facfe;
        text-decoration: none;
        text-align: center;
        font-size: .7rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-left: 3px;
        border-radius: 18.1px;
    }


    .bnaddstudent-hover {
      width: 100%;
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
      height: 40px;
      text-align:center;
      border: none;
      background-size: 300% 100%;
      border-radius: 50px;
      moz-transition: all .4s ease-in-out;
      -o-transition: all .4s ease-in-out;
      -webkit-transition: all .4s ease-in-out;
      transition: all .4s ease-in-out;
    }

    .bnaddstudent-hover:hover {
      background-position: 100% 0;
      moz-transition: all .4s ease-in-out;
      -o-transition: all .4s ease-in-out;
      -webkit-transition: all .4s ease-in-out;
      transition: all .4s ease-in-out;
    }

    .bnaddstudent-hover:focus {
      outline: none;
    }

    .bnaddstudent-hover.bnaddstudent {
        background-image: linear-gradient(
          to right,
          #0e6387,
          #3c8dbc,
          #1d73a5,
          #3c8dbc
        );
        box-shadow: 0 4px 15px 0 rgba(65, 132, 234, 0.75);
      }


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
        .col-md-3{
        width:25% !important;
      }
        .content-wrapper{
      border-top-left-radius: 60px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    .main-footer{
      border-bottom-left-radius: 60px;
    
    }
  </style>
@endsection

@section('content')
<body>
  <!-- HEADER START -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ route('parent.dashboard') }}">Parent</a></li>
        <li class="active">Add Student</li>
      </ol>
    </section>
    <h1 class="smo-content-title">
      <span class="text-capitalize">Student</span>
      <small>Add student</small>
  </h1>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- ADD STUDENT -->
  <div class="row" style="padding-bottom: 0 !important;">
    <div class="col-md-6 m-r-15">
      <!--FORM -->
      <form method="post" action="{{ route('parent.add-student.submit') }}">
        @csrf
        <div class="col-md-12 m-b-0">
          <div class="row display-flex-wrap m-b-0 p-b-0">
            <div class="box col-md-12 padding-10 p-t-20 m-b-0 m-l-15" style="padding-bottom: 0 !important;">
              <div class="form-group col-md-6 required">
               
                <input type="text" name="studentnumber" value="{{ old('studentnumber') }}"   required=''>
               
                <label alt='Student Number' placeholder='Student Number'></label>
                @if ($errors->has('studentnumber'))
                  <span class="help-block" style="color:#e74a3b;  font-size: 12px;">
                      <strong>{{ $errors->first('studentnumber') }}</strong>
                  </span>
                @endif
              </div>
              <div class="form-group col-md-6 required">
                {{-- <label>Student Password</label> --}}
                <input type="password" name="password" required=''>
                <label alt='Password' placeholder='Password'></label>
                @if ($errors->has('password'))
                  <span class="help-block" style="color:#e74a3b;  font-size: 12px;">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
               <span style="color:#EF9113; font-weight:700; margin-top:-">NOTE:</span> Your Student portal password
              </div>
              <div class="form-group col-md-12 required">
                {{-- <label> </label> --}}
                <button type="submit" class="bnaddstudent-hover bnaddstudent">Add Student</button>
              </div>
           
              
            </div>
          </div>
        </div>
      </form>
      <!-- END OF FORM -->
    </div>
    <div class="col-md-12"  style="margin-top:20px">
      {{-- STUDENT DATA   --}}
      @if( count($students) > 0 )
      @php $index = 0; @endphp
      @foreach($students as $student)
        @if($student)
          @if($index % 4 == 0)
       
          
          @endif
          
          <div class="col-md-3">
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
                              <p class="text" style="margin-bottom:40px; margin-top:-10px;">Department: </b>{{ $student->department_name }}</p>
                              
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
          
                </div>
          </div>
            @php $index++; @endphp
            @if($index% 4 == 0)
         
       
            @endif
          @endif
        @endforeach
      @endif

</div>
  </div>
 
</body>
@endsection


@section('custom-script')
@endsection