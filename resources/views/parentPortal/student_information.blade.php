@extends('backpack::layout_parent')

@section('after_styles')
  <style type="text/css">
    .padding-left-15 {
      padding-left: 15px;
    }
    .pad-top {
      margin-top: 5px;
      padding-top: 5px;
    }

    .control-labels{
        margin: 0px;
        padding: 0px;
    }
    .nav-pills>li {
      margin-top: 5px;
    }
    .nav-pills>li>a {
      border-radius: 10px;
    }
    .nav-pills>li.active>a {
      border-top-color: #007bff !important;
      color: #ffffff;
      background-color: #007bff !important;
    }
    .nav-pills>li>a:hover{
      background-color: #1d528b;
    }

    .box-primary {
      border-top-color: #007bff !important;
    }

    .tab-content {
      box-shadow:  none !important;
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
    
    
    .card-info{
      position: relative;
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 5px 15px 1px rgba(0, 0, 0, 0.1);
      overflow: hidden;
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
      background-image: linear-gradient(to top, #4dbdfd, #319dfc);
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
      border: 4px solid #12d6f8;
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
      background: linear-gradient(to right, #4facfe 0%, #00f2fe 140%);
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

	@media only screen and (min-width: 768px) {
    .content-wrapper{
    border-top-left-radius: 60px;
    }
    .sidebar-toggle{
      margin-left:40px;
    }
    .card{
      width: 335px;
    }
    }
    .main-footer{
      border-bottom-left-radius: 60px;
    
    }

        
    input[type="text"] {
      box-sizing: border-box;
      width: 100%;
      height: calc(3em + 2px);
      margin: 0 0 1em;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 0.5em;
      background: #fff;
      resize: none;
      outline: none;
    }
    input[type="text"]{
      border-color: #0cb7f0;
    }
    input[type="text"] + label[placeholder]:before,
    input[type="text"]:valid + label[placeholder]:before {
      transition-duration: 0.2s;
      transform: translate(0, -1.5em) scale(0.9, 0.9);
    }
    
    input[type="text"] + label[placeholder] {
      display: block;
      pointer-events: none;
      line-height: 1.25em;
      margin-top: calc(-3em - 2px);
      margin-bottom: calc((3em - 1em) + 2px);
    }
    input[type="text"] + label[placeholder]:before {
      content: attr(placeholder);
      display: inline-block;
      margin: 0 calc(1em + 0px);
      padding: 0 0px;
      color: #0575d1;
      white-space: nowrap;
      transition: 0.3s ease-in-out;
      background-image: linear-gradient(to bottom, #fff, #fff);
      background-size: 100% 5px;
      background-repeat: no-repeat;
      background-position: center;
    }
    .panel-default{
      border-color: #fff;
    }
    .panel-default>.panel-heading {
      background-color: #fff;
      border-color: #fff;
    }
    .box-header {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 140%);
    }
    
  </style>
@endsection

@section('header')
@endsection

@section('content')
<body style="background: #3c8dbc;">
    <!-- HEADER START -->
      <div class="row" style="padding: 15px;">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
        <section class="content-header">
          <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url('admin/student') }}" class="text-capitalize">Students</a></li>
            <li class="active">Record</li>
          </ol>
        </section>
        {{-- <h1 class="smo-content-title">
              <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
              <small>{{ trans('backpack::crud.edit').' '.$crud->entity_name }}.</small>
          </h1> --}}
        </div>
      </div>
      <!-- HEADER END -->
    <div class="row p-l-10 p-r-10">

      <!-- RIGHT SIDEBAR -->
      <div class="col-md-3 col-two">
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
                              
                                  <i class="fas fa-camera fa-2x"></i>
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
                          
                  <div class="buttons-wrap">
                    <div class="view-enroll-wrap">
                          <a href="{{ url('parent/student-enrollments/' . $student->studentnumber) }}" class="view-enroll">Enrollment</a>
                    </div>
                  </div>
                      </div>
                  </div>
        </div>
      </div>

      <div class="col-md-9 col-lg-9 col-one">

              <div class="nav-tabs-custom" style="border-radius: 10px;">
                <div class="box-header">
                    <ul class="nav nav-pills">
                      <li class="active">
                        <a href="#student-information" data-toggle="tab" aria-expanded="true">
                          <strong style="color:#fff;">Student Information</strong>
                        </a>
                      </li>
                      <li class="">
                        <a href="#family-background" data-toggle="tab" aria-expanded="false">
                          <strong style="color:#fff;">Family Background</strong>
                        </a>
                      </li>
                      <li class="">
                        <a href="#medical-history" data-toggle="tab" aria-expanded="false">
                          <strong style="color:#fff;">Medical History</strong>
                        </a>
                      </li>
                      <li class="">
                        <a href="#other-information" data-toggle="tab" aria-expanded="false">
                          <strong style="color:#fff;">Other Information</strong>
                        </a>
                      </li>
                    </ul>
                </div>
                
                <div class="tab-content" style="border-radius: 0;">

                    <!-- START STUDENT INFORMATION -->
                    <div class="tab-pane active" id="student-information">
                      <div class="panel panel-default" style="box-shadow: none;">

                         <!-- Heading -->
                         <div class="panel-heading">
                          <h3><strong>Student Information</strong></h3>
                        </div>

                          <div class="panel-body">
                       <!-- Student's Fullname -->
                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="lastname" value="{{ $student->lastname }}" readonly="1" >
                        <label alt='Lastname' placeholder='Lastname'></label>
                      </div>

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="firstname" value="{{ $student->firstname }}" readonly="1" >
                        <label alt='Firstname' placeholder='Firstname'></label>
                      </div>

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="middlename" value="{{ $student->middlename }}" readonly="1" >
                        <label alt='Middlename' placeholder='Middlename'></label>
                      </div>
                      <!-- End of Student's Fullname -->

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="gender" value="{{ $student->gender }}" readonly="1" >
                        <label alt='Gender' placeholder='Gender'></label>
                      </div>    


                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="contactnumber" value="{{ $student->contactnumber }}" readonly="1" >
                        <label alt='Contact Number' placeholder='Contact Number'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="email" value="{{ $student->email }}" readonly="1" >
                        <label alt='Email' placeholder='Email'></label>
                      </div>

                      <div class="form-group col-md-2 col-lg-2">
                        <input type="text" name="citizenship" value="{{ $student->citizenship }}" readonly="1" >
                        <label alt='Citizenship' placeholder='Citizenship'></label>
                      </div>
                      
                      <div class="form-group col-md-2 col-lg-2">
                        <input type="text" name="religion" value="{{ $student->religion }}" readonly="1" >
                        <label alt='Religion' placeholder='Religion'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="school_year_entered" value="{{ $student->school_year_name }}" readonly="1"   >
                        <label alt='School Year' placeholder='School Year'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="application" value="{{ date("F d, Y", strtotime($student->application)) }}" readonly="1" >
                        <label alt='Application Date' placeholder='Application Date'></label>
                      </div>

                            <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="studentnumber" value="{{ $student->studentnumber }}" readonly="1" >
                        <label alt='Student Number' placeholder='Student Number'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="lrn" value="{{ $student->lrn }}" readonly="1" >
                        <label alt='LRN' placeholder='LRN'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="current_department" value="{{ $student->current_department }}" readonly="1" >
                        <label alt='Current Department' placeholder='Current Department'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="current_level" value="{{ $student->current_level }}" readonly="1" >
                        <label alt='Current Level' placeholder='Current Level'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="birthplace" value="{{ $student->birthplace }}" readonly="1" >
                        <label alt='Place of Birth' placeholder='Place of Birth'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="birthdate" value="{{ date("F d, Y", strtotime($student->birthdate)) }}" readonly="1" >
                        <label alt='Date of Birth' placeholder='Date of Birth'></label>
                      </div>
                      
                      <!-- Residential Address -->
                      <div class="form-group col-md-12 col-lg-12" style="">
                      <h5 style="font-size:12px; color:#0575d1; margin-bottom:0px;"><strong>Residential Address In The Philippines</strong></h5>
                    </div>

                    <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="province" value="{{ $student->province }}" readonly="1" >
                        <label alt='Province' placeholder='Province'></label>
                      </div>

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="city_municipality" value="{{ $student->city_municipality }}" readonly="1" >
                        <label alt='City/Municipality' placeholder='City/Municipality'></label>
                      </div>

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="barangay" value="{{ $student->barangay }}" readonly="1" >
                        <label alt='Barangay' placeholder='Barangay'></label>
                      </div>

                      <div class="form-group col-md-3 col-lg-3">
                        <input type="text" name="street_number" value="{{ $student->street_number }}" readonly="1" >
                        <label alt='Street No.' placeholder='Street No.'></label>
                      </div>
                      <!-- End of Residential Address -->
                        </div>

                    </div>
                    </div>
                    <!-- END STUDENT INFORMATION -->


                    <!-- START FAMILY BACKGROUND -->
                    <div class="tab-pane" id="family-background">

                      <!-- Start Father Information -->
                      <div class="panel panel-default" style="box-shadow: none;">

                          <!-- Heading -->
                        <div class="panel-heading">
                          <h3><strong>Father Information</strong></h3>
                        </div>

                          <div class="panel-body">
                            <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="father_living_deceased" value="{{ $student->father_living_deceased }}" readonly="1" >
                        <label alt='Living or Deceased' placeholder='Living or Deceased'></label>
                      </div>
                            
                            <!-- Father's Fullname -->
                          <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fatherlastname" value="{{ $student->fatherlastname }}" readonly="1" >
                        <label alt='Lastname' placeholder='Lastname'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fatherfirstname" value="{{ $student->fatherfirstname }}" readonly="1" >
                        <label alt='Firstname' placeholder='Firstname'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fathermiddlename" value="{{ $student->fathermiddlename }}" readonly="1" >
                        <label alt='Middlename' placeholder='Middlename'></label>
                      </div> 
                      <!-- End Of Father's Fullname -->

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fathercitizenship" value="{{ $student->fathercitizenship }}" readonly="1" >
                        <label alt='Citizenship' placeholder='Citizenship'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fathervisastatus" value="{{ $student->fathervisastatus }}" readonly="1" >
                        <label alt='Philippine Visa Status' placeholder='Philippine Visa Status'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fatherMobileNumber" value="{{ $student->fatherMobileNumber }}" readonly="1" >
                        <label alt='Mobile Number' placeholder='Mobile Number'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="father_occupation" value="{{ $student->father_occupation }}" readonly="1" >
                        <label alt='Father Occupation' placeholder='Father Occupation'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fatheremployer" value="{{ $student->fatheremployer }}" readonly="1" >
                        <label alt='Employer/Organization' placeholder='Employer/Organization'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="fatherofficenumber" value="{{ $student->fatherofficenumber }}" readonly="1" >
                        <label alt='Office Number' placeholder='Office Number'></label>
                      </div> 

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="fatherdegree" value="{{ $student->fatherdegree }}" readonly="1" >
                        <label alt='Graduate Degree' placeholder='Graduate Degree'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="School" value="{{ $student->fatherschool }}" readonly="1" >
                        <label alt='School' placeholder='School'></label>
                      </div>
                          </div>

                      </div>
                      <!-- End Father Information -->

                      <!-- Start Mother Information -->
                      <div class="panel panel-default" style="box-shadow: none;">
                          <!-- Heading -->
                        <div class="panel-heading">
                          <h3><strong>Mother Information</strong></h3>
                        </div>

                          <div class="panel-body">
                            <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="mother_living_deceased" value="{{ $student->mother_living_deceased }}" readonly="1" >
                        <label alt='Living or Deceased' placeholder='Living or Deceased'></label>
                      </div>
                            
                            <!-- Mother's Fullname -->
                          <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="motherlastname" value="{{ $student->motherlastname }}" readonly="1" >
                        <label alt='Lastname' placeholder='Lastname'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="motherfirstname" value="{{ $student->motherfirstname }}" readonly="1" >
                        <label alt='Firstname' placeholder='Firstname'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="mothermiddlename" value="{{ $student->mothermiddlename }}" readonly="1" >
                        <label alt='Middlename' placeholder='Middlename'></label>
                      </div> 
                      <!-- End Of Father's Fullname -->

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="mothercitizenship" value="{{ $student->mothercitizenship }}" readonly="1" >
                        <label alt='Citizenship' placeholder='Citizenship'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="mothervisastatus" value="{{ $student->mothervisastatus }}" readonly="1" >
                        <label alt='Philippine Visa Status' placeholder='Philippine Visa Status'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="motherMobileNumber" value="{{ $student->motherMobileNumber }}" readonly="1" >
                        <label alt='Mobile Number' placeholder='Mobile Number'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="mother_occupation" value="{{ $student->mother_occupation }}" readonly="1" >
                        <label alt='Father Occupation' placeholder='Father Occupation'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="motheremployer" value="{{ $student->motheremployer }}" readonly="1" >
                        <label alt='Employer/Organization' placeholder='Employer/Organization'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="motherofficenumber" value="{{ $student->motherofficenumber }}" readonly="1" >
                        <label alt='Office Number' placeholder='Office Number'></label>
                      </div> 

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="motherdegree" value="{{ $student->motherdegree }}" readonly="1" >
                        <label alt='Graduate Degree' placeholder='Graduate Degree'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="motherschool" value="{{ $student->motherschool }}" readonly="1" >
                        <label alt='School' placeholder='School'></label>
                      </div>
                          </div>

                      </div>
                      <!-- End Mother Information -->

                      <!-- Start Legal Guardian Information -->
                      <div class="panel panel-default" style="box-shadow: none;">
                        
                          <!-- Heading -->
                        <div class="panel-heading">
                          <h3><strong>Legal Guardian Information</strong></h3>
                        </div>

                          <div class="panel-body">
                            <!-- Legal Guardian's Fullname -->
                          <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_lastname" value="{{ $student->legal_guardian_lastname }}" readonly="1" >
                        <label alt='Lastname' placeholder='Lastname'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_firstname" value="{{ $student->legal_guardian_firstname }}" readonly="1" >
                        <label alt='Firstname' placeholder='Firstname'></label>
                      </div> 

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_middlename" value="{{ $student->legal_guardian_middlename }}" readonly="1" >
                        <label alt='Middlename' placeholder='Middlename'></label>
                      </div> 
                      <!-- End Of Legal Guardian's Fullname -->

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_citizenship" value="{{ $student->legal_guardian_citizenship }}" readonly="1" >
                        <label alt='Citizenship' placeholder='Citizenship'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_occupation" value="{{ $student->legal_guardian_occupation }}" readonly="1" >
                        <label alt='Occupation' placeholder='Occupation'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="legal_guardian_contact_number" value="{{ $student->legal_guardian_contact_number }}" readonly="1" >
                        <label alt='Mobile Number' placeholder='Mobile Number'></label>
                      </div>
                          </div>

                      </div>
                      <!-- End Legal Guardian Information -->

                      <!-- Start Emergency Contact Information -->
                      <div class="panel panel-default" style="box-shadow: none;">

                          <!-- Heading -->
                        <div class="panel-heading">
                          <h3><strong>Legal Guardian Information</strong></h3>
                        </div>
                        
                        <div class="panel-body">
                          <!-- Emergency Contact Fullname -->
                          <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="emergency_contact_name_on_record" value="{{ $student->emergency_contact_name_on_record }}" readonly="1" >
                        <label alt='Fullname' placeholder='Fullname'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="emergency_contact_number_on_record" value="{{ $student->emergency_contact_number_on_record }}" readonly="1" >
                        <label alt='Mobile Number' placeholder='Mobile Number'></label>
                      </div>

                      <div class="form-group col-md-4 col-lg-4">
                        <input type="text" name="emergency_contact_home_number_on_record" value="{{ $student->emergency_contact_home_number_on_record }}" readonly="1" >
                        <label alt='Home Phone' placeholder='Home Phone'></label>
                      </div>
                      
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="emergency_contact_address_on_record" value="{{ $student->emergency_contact_address_on_record }}" readonly="1" >
                        <label alt='Address' placeholder='Address'></label>
                      </div>
                        </div>

                      </div>
                      <!-- End Emergency Contact Information -->
                  </div>
                  <!-- END FAMILY BACKGROUND -->

                  <!-- START MEDICAL HISTORY -->
                    <div class="tab-pane" id="medical-history">
                      <div class="panel panel-default" style="box-shadow: none;">

                          <!-- Heading -->
                          <div class="panel-heading">
                            <h3><strong>Medical Information</strong></h3>
                          </div>

                          <div class="panel-body">
                            <!-- First Question -->
                      <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
                      <label style="color:#0575d1; margin-bottom:5px;"><strong>1. Does your child have any of the following?</strong></label>
                    </div>

                        <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="asthma" value="{{ $student->asthma ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Asthma' placeholder='Asthma'></label>
                      </div>

                      <!-- Asthma Inhaler -->
                      @if($student->asthma)
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="asthmainhaler" value="{{ $student->asthmainhaler ? 'Yes' : 'No' }}" readonly="1" >
                          <label alt='Does your child carry an asthma inhaler?' placeholder='Does your child carry an asthma inhaler?'></label>
                        </div>
                      @endif

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="allergy" value="{{ $student->allergy ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Allergies' placeholder='Allergies'></label>
                      </div>

                      <!-- Specific Allergies -->
                      @if($student->allergy)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="allergies" value="{!! $student->allergies !!}" readonly="1" >
                        <label alt='Specific Allergy(s)' placeholder='Specific Allergy(s)'></label>
                      </div>
                      @endif

                      <!-- Allergy Reaction -->
                      @if($student->allergy)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="allergyreaction" value="{!! $student->allergyreaction !!}" readonly="1" >
                        <label alt='Reaction' placeholder='Reaction'></label>
                      </div>
                      @endif

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="drugallergy" value="{{ $student->drugallergy ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Drug Allergy' placeholder='Drug Allergy'></label>
                      </div>

                      <!-- Specific Drug Allergies -->
                      @if($student->drugallergy)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="drugallergies" value="{!! $student->drugallergies !!}" readonly="1" >
                        <label alt='Specific Drug Allergy(s)' placeholder='Specific Drug Allergy(s)'></label>
                      </div>
                      @endif

                      <!-- Drug Allergy Reaction -->
                      @if($student->drugallergy)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="allergyreaction" value="{!! $student->allergyreaction !!}" readonly="1" >
                        <label alt='Reaction' placeholder='Reaction'></label>
                      </div>
                      @endif

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="visionproblem" value="{{ $student->visionproblem ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Eye or vision problems' placeholder='Eye or vision problems'></label>
                      </div>

                      <!-- Vision Problem Description -->
                      @if($student->visionproblem && $student->visionproblemdescription)
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="visionproblemdescription" value="{{ $student->visionproblemdescription }}" readonly="1" >
                          <label alt='Description' placeholder='Description'></label>
                        </div>
                      @endif

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hearingproblem" value="{{ $student->hearingproblem ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Ear or hearing problems' placeholder='Ear or hearing problems'></label>
                      </div>

                      <!-- Hearing Problem Description -->
                      @if($student->hearingproblem && $student->hearingproblemdescription)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hearingproblemdescription" value="{{ $student->hearingproblemdescription }}" readonly="1" >
                        <label alt='Description' placeholder='Description'></label>
                      </div>
                      @endif

                      <!-- Second Question -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hashealthcondition" value="{{ $student->hashealthcondition ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='2. Any other health condition that the school should be aware of (e.g epilepsy, diabetes, etc.)' placeholder='2. Any other health condition that the school should be aware of (e.g epilepsy, diabetes, etc.)'></label>
                      </div>

                      <!-- Health Condition Summary -->
                      @if($student->hashealthcondition)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="healthcondition" value="{{ $student->healthcondition }}" readonly="1" >
                        <label alt='Summary' placeholder='Summary'></label>
                      </div>
                      @endif

                      <!-- Third Question -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="ishospitalized" value="{{ $student->ishospitalized ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='3. Has your child recently been hospitalized?' placeholder='3. Has your child recently been hospitalized?'></label>
                      </div>

                      <!-- Hospitalized Summary -->
                      @if($student->ishospitalized)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hospitalized" value="{{ $student->hospitalized }}" readonly="1" >
                        <label alt='Summary' placeholder='Summary'></label>
                      </div>
                      @endif

                      <!-- Fourth Question -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hadinjuries" value="{{ $student->hadinjuries ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='4. Has your child recently had any serious injuries?' placeholder='4. Has your child recently had any serious injuries?'></label>
                      </div>

                      <!-- Injuries Summary -->
                      @if($student->hadinjuries)
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="injuries" value="{{ $student->injuries }}" readonly="1" >
                          <label alt='Summary' placeholder='Summary'></label>
                        </div>
                      @endif

                      <!-- Fifth Question -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="medication" value="{{ $student->medication ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='5. Is your child on a regular medication?' placeholder='5. Is your child on a regular medication?'></label>
                      </div>

                      <!-- Injuries Summary -->
                      @if($student->medication)
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="medications" value="{{ $student->medications }}" readonly="1" >
                        <label alt='Name of medication(s) and frequency' placeholder='Name of medication(s) and frequency'></label>
                      </div>
                      @endif

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="schoolhourmedication" value="{{ $student->schoolhourmedication ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Does your child need to take any medication(s) during school hours?' placeholder='Does your child need to take any medication(s) during school hours?'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
                      <label style=" color:#0575d1; margin-bottom:5px;"><strong>I give consent for my child to receive the following:</strong></label>
                    </div>

                    <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="firstaidd" value="{{ $student->firstaidd ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='1. Minor first aid' placeholder='1. Minor first aid'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="emergencycare" value="{{ $student->emergencycare ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='2. Emergency care' placeholder='2. Emergency care'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="hospitalemergencycare" value="{{ $student->hospitalemergencycare ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='3. Emergency care at the nearest hospital' placeholder='3. Emergency care at the nearest hospital'></label>
                      </div>

                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="oralmedication" value="{{ $student->oralmedication ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='4. Oral non-prescription medication' placeholder='4. Oral non-prescription medication'></label>
                      </div>
                        </div>

                    </div>
                    </div>
                    <!-- /.END OF MEDICAL HISTORY -->

                    <!-- START OTHER INFORMATION -->
                    <div class="tab-pane" id="other-information">
                      <div class="panel panel-default" style="box-shadow: none;">

                        <!-- Heading -->
                        <div class="panel-heading">
                          <h3><strong>Other Information</strong></h3>
                        </div>

                          <div class="panel-body">

                            <!-- Previous School -->
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="previousschool" value="{!! $student->previousschool !!}" readonly="1" >
                          <label alt='Previous School' placeholder='Previous School'></label>
                        </div>

                      <!-- Previous School Address -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="previousschooladdress" value="{!! $student->previousschooladdress !!}" readonly="1" >
                        <label alt='Complete address of the above School (including zip code)' placeholder='Complete address of the above School (including zip code)'></label>
                      </div>

                      <!-- School(s) Attended Table -->
                      <div class="form-group col-md-12 col-lg-12">
                        <label style=" color:#0575d1; margin-bottom:5px;">School(s) attended</label>
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style=" color:#0575d1; margin-bottom:5px;">Grade/Level (Until)</th>
                                <th scope="col" style=" color:#0575d1; margin-bottom:5px;">Grade/Level (From)</th>
                                <th scope="col" style=" color:#0575d1; margin-bottom:5px;">Name of School</th>
                                <th scope="col" style=" color:#0575d1; margin-bottom:5px;">Year Attended</th>
                            </tr>
                        </thead>
                          <tbody>
                            @if($student->schooltable)
                              @if( count(json_decode($student->schooltable)) > 0 )
                                @foreach(json_decode($student->schooltable) as $schooltable)
                                  <tr>
                                      <td>{{ $schooltable->grade_level_from }}</td>
                                      <td>{{ $schooltable->grade_level_until }}</td>
                                      <td>{{ $schooltable->school_name }}</td>
                                      <td>{{ $schooltable->year_attended }}</td>
                                  </tr>
                                @endforeach
                            @endif
                            @endif
                          </tbody>
                      </table>
                      </div>
                      <!-- End Of School(s) Attended Table -->

                      <!-- Reading and Writing Proficiency -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="readingwriting" value="{{ $student->readingwriting }}" readonly="1" >
                        <label alt='Reading and Writing Proficiency' placeholder='Reading and Writing Proficiency'></label>
                      </div>

                      <!-- Verbal Proficiency -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="verbalproficiency" value="{{ $student->verbalproficiency }}" readonly="1" >
                        <label alt='Verbal Proficiency' placeholder='Verbal Proficiency'></label>
                      </div>

                      <!-- Major Language Used at Home -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="majorLanguage" value="{{ $student->majorLanguage }}" readonly="1" >
                        <label alt='Major language used at home' placeholder='Major language used at home'></label>
                      </div>

                      <!-- Major Language Used at Home -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="other_language_specify" value="{{ $student->other_language_specify }}" readonly="1" >
                        <label alt='Specify Other Language:' placeholder='Specify Other Language:'></label>
                      </div>

                      <!-- Specify Other Language -->
                      <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
                      <h5 style=" color:#0575d1; margin-bottom:5px;"><strong>Other languages/dialects spoken</strong></h5>
                    </div>

                    <div class="form-group col-md-12 col-lg-12">
                      <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style=" color:#0575d1; margin-bottom:5px;">List below</th>
                            </tr>
                        </thead>
                          <tbody>
                            @if( $student->otherlanguages )
                            @if( count($student->otherlanguages) > 0 )
                              @foreach($student->otherlanguages as $language)
                                @if( count($language) > 0 )
                                  <tr>
                                      <td>{{ $language['languages'] }}</td>
                                  </tr>
                                @endif
                              @endforeach
                            @endif
                            @endif
                          </tbody>
                      </table>
                    </div>

                    <!-- Remedial Help Explanation -->
                    <div class="form-group col-md-12 col-lg-12">
                      <input type="text" name="remedialhelpexplanation" value="{!! $student->remedialhelpexplanation !!}" readonly="1" >
                      <label alt='Latest Testing Result' placeholder='Latest Testing Result'></label>
                    </div>

                      <!-- Special Talent -->
                      <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
                      <h5 style=" color:#0575d1; margin-bottom:5px;"><strong>Does your child have any special talent or interest in:</strong></h5>
                    </div>

                      @if($student->specialtalent)
                        @foreach(json_decode($student->specialtalent) as $specialtalentKey => $specialtalent)

                          @if(isset($specialtalent->isChecked))
                            @if(isset($specialtalent->sports))
                              <div class="form-group col-md-12 col-lg-12">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent->isChecked ? 'checked' : ''}} disabled>
                                  <label class="form-check-label text-capitalize" for="defaultCheck1">
                                    {{ $specialtalentKey }}
                                  </label>
                              </div>
                              <textarea name="sports" readonly="1" >{!! $specialtalent->sports !!}</textarea>
                              </div>

                            @elseif(isset($specialtalent->instrument))
                              <div class="form-group col-md-12 col-lg-12">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent->isChecked ? 'checked' : ''}} disabled>
                                  <label class="form-check-label text-capitalize" for="defaultCheck1">
                                    {{ $specialtalentKey }}
                                  </label>
                              </div>
                              <textarea name="sports" readonly="1" >{!! $specialtalent->instrument !!}</textarea>
                              </div>
                            @endif

                          @else
                            <div class="form-group col-md-12 col-lg-12">
                              <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent ? 'checked' : ''}} disabled>
                                <label class="form-check-label text-capitalize" for="defaultCheck1">
                                  {{ $specialtalentKey }}
                                </label>
                            </div>
                            </div>
                          @endif
                        @endforeach
                      @endif
                      <!-- End Of Special Talent -->

                      <!-- Other Information -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="otherinfo" value="{{ $student->otherinfo ? 'Yes' : 'None' }}" readonly="1" >
                        <label alt='Are there any other information you think the teacher should know about your child?' placeholder='Are there any other information you think the teacher should know about your child?'></label>
                      </div>

                      @if($student->otherinfo && $student->otherinfofield)
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="sports" value="{!! $specialtalent->instrument !!}" readonly="1" >
                          <label alt='Other Information' placeholder='Other Information'></label>
                        </div>
                      @endif

                      <!-- Disiplinary Problem -->
                      <div class="form-group col-md-12 col-lg-12">
                        <input type="text" name="otherinfo" value="{{ $student->disciplinaryproblem ? 'Yes' : 'No' }}" readonly="1" >
                        <label alt='Has your child ever been asked to leave school because of any behavioral/disciplinary problems?' placeholder='Has your child ever been asked to leave school because of any behavioral/disciplinary problems?'></label>
                      </div>

                      @if($student->disciplinaryproblem && $student->disciplinaryproblemexplanation)
                        <div class="form-group col-md-12 col-lg-12">
                          <input type="text" name="sports" value="{!! $specialtalent->disciplinaryproblemexplanation !!}" readonly="1" >
                          <label alt='Explanation' placeholder='Explanation'></label>
                        </div>
                      @endif

                    
                        </div>

                    </div>
                    </div>
                    <!-- /.END OF OTHER INFORMATION -->
              </div>
              <!-- /.tab-content -->
              </div>

      </div>

    </div>
</body>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@push('after_scripts')
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  {{--   <script>
      $('.table').DataTable({
          "processing": true,
          "paging": false,
          "searching": true,
      });
      $(".dataTables_filter").css("float", "right");
      $('input[type="search"]').css("border-radius", "15px");
    </script> --}}
@endpush
