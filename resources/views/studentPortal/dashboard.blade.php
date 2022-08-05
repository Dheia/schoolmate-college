@extends('backpack::layout_student')

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
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de;">
        <div class="box-body box-profile">
          @php
            $avatar = $student ? $student->photo : 'images/headshot-default.png';
          @endphp
          <img class="profile-user-img img-responsive img-circle" src="{{ url($student->photo) }}" alt="User profile picture">

          <h3 class="profile-username text-center">{{ $student->fullname }}</h3>

          <p class="text-muted text-center bold">{{ $student->prefixed_student_number }}</p>

          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item text-center">
              <b>{{ $student->current_level }}</b>
            </li>
            @if($student->student_section_name)
             <li class="list-group-item text-center">
              <b>{{ $student->student_section_name }}</b>
            </li>
            @endif
            @if($student->track_name)
              <li class="list-group-item text-center">
                <b>{{ $student->track_name }}</b>
              </li>
            @endif
            <li class="list-group-item text-center">
              <b>{{ $student->department_name }}</b>
            </li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->


      <!-- About Me Box -->
       <div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de;">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <strong><i class="fa fa-mobile margin-r-5"></i> Contact No.</strong>
          <p class="text-muted">{{ $student->contactnumber ?? '-' }}</p>
          <hr>
          <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
          <p class="text-muted">{{ $student->residentialaddress ?? '-' }}</p>
          <hr>
          <strong><i class="fa fa-birthday-cake margin-r-5"></i> Birthday</strong>
          <p class="text-muted">{{ date('F m, Y', strtotime($student->birthdate)) }}</p>
        </div>
        <!-- /.box-body -->
      </div> 
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="box" style="border-radius: 5px;">
      <div class="nav-tabs-custom" style="border-radius: 5px;">
        <div class="box-header with-border">
          <ul class="nav nav-pills">
            <li class="active"><a href="#profile" data-toggle="tab" aria-expanded="true">Profile</a></li>
            <li class=""><a href="#family-background" data-toggle="tab" aria-expanded="false">Family Background</a></li>
            <li class=""><a href="#requirement" data-toggle="tab" aria-expanded="false">Requirement</a></li>
          </ul>
        </div>
        
        <div class="tab-content" style="border-radius: 5px;">
          <!-- START PROFILE INFORMATION -->
          <div class="tab-pane active" id="profile">
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"><h3><strong>Student Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Name</label>
                  <p class="control-label" style="color: #333;">{{ $student->fullname }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Email</label>
                  <p class="control-label">{{ $student->email ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Citizenship</label>
                  <p class="control-label">{{ $student->citizenship ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label  class="control-label">Place of Birth</label>
                  <p class="control-label">{{ $student->birthplace ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Religion</label>
                  <p class="control-label">{{ $student->religion ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label  class="control-label">Student No.</label>
                  <p class="control-label">{{ $student->prefixed_student_number ?? '-' }}</p>
                </div>   
                <div class="col-md-6 col-lg-6">
                  <label  class="control-label">LRN</label>
                  <p class="control-label">{{ $student->lrn ?? '-' }}</p>
                </div>
              </div>
            </div>
          </div>
          <!-- END PROFILE INFORMATION -->


          <!-- START FAMILY BACKGROUND -->
          <div class="tab-pane" id="family-background">

            <!-- Start Father Information -->
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"> <h3 class="hh3"><strong>Father Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Firstname</label>
                  <p class="control-label" style="color: #333;">{{ $student->fatherfirstname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Lastname</label>
                  <p class="control-label" style="color: #333;">{{ $student->fatherlastname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Contact No.</label>
                  <p class="control-label">{{ $student->fatherMobileNumber ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Citizenship</label>
                  <p class="control-label">{{ $student->fathercitizenship ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Occupation</label>
                  <p class="control-label">{{ $student->father_occupation ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Employer/Organization</label>
                  <p class="control-label">{{ $student->fatheremployer ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Office Number</label>
                  <p class="control-label">{{ $student->fatherofficenumber ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                    <label  class="control-label">Graduate Degree</label>
                    <p class="control-label">{{ $student->fatherdegree ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Graduate School</label>
                  <p class="control-label">{{ $student->fatherschool ?? '-' }}</p>
                </div>
              </div>
            </div>
            <!-- End Father Information -->

            <!-- Start Mother Information -->
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"> <h3 class="hh3"><strong>Mother Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Firstname</label>
                  <p class="control-label" style="color: #333;">{{ $student->motherfirstname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Lastname</label>
                  <p class="control-label" style="color: #333;">{{ $student->motherlastname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Contact No.</label>
                  <p class="control-label">{{ $student->motherMobileNumber ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Citizenship</label>
                  <p class="control-label">{{ $student->mothercitizenship ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Occupation</label>
                  <p class="control-label">{{ $student->mother_occupation ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Employer/Organization</label>
                  <p class="control-label">{{ $student->motheremployer ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Office Number</label>
                  <p class="control-label">{{ $student->motherofficenumber ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                    <label  class="control-label">Graduate Degree</label>
                    <p class="control-label">{{ $student->motherdegree ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Graduate School</label>
                  <p class="control-label">{{ $student->motherschool ?? '-' }}</p>
                </div>
              </div>
            </div>
            <!-- End Mother Information -->

            <!-- Start Legal Guardian Information -->
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"> <h3 class="hh3"><strong>Legal Guardian Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Firstname</label>
                  <p class="control-label text-capitalize" style="color: #333;">{{ $student->legal_guardian_firstname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Lastname</label>
                  <p class="control-label text-capitalize" style="color: #333;">{{ $student->legal_guardian_lastname ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Contact No.</label>
                  <p class="control-label text-capitalize">{{ $student->legal_guardian_contact_number ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Citizenship</label>
                  <p class="control-label text-capitalize">{{ $student->legal_guardian_citizenship ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Occupation</label>
                  <p class="control-label text-capitalize">{{ $student->legal_guardian_occupation ?? '-' }}</p>
                </div>
              </div>
            </div>
            <!-- End Legal Guardian Information -->

            <!-- Start Emergency Contact Information -->
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"> <h3 class="hh3"><strong>Emergency Contact Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Fullname</label>
                  <p class="control-label text-capitalize" style="color: #333;">{{ strtolower($student->emergency_contact_name_on_record) }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Relationship To Child</label>
                  <p class="control-label">{{ $student->emergencyRelationshipToChild ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Address</label>
                  <p class="control-label">{{ $student->emergency_contact_address_on_record ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Contact No.</label>
                  <p class="control-label">{{ $student->emergency_contact_number_on_record ?? '-' }}</p>
                </div>
              </div>
            </div>
            <!-- End Emergency Contact Information -->
          </div>
          <!-- END FAMILY BACKGROUND -->


          {{-- START UPLOAD REQUIREMENTS --}}
          <div class="tab-pane" id="requirement">
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"> <h3 class="hh3"><strong>Requirements</strong></h3></div>
              <div class="panel-body">
                <form id="requirementForm" role="form" method="POST" action="{{ url('student/upload-requirements') }}"  style="width: 100%;" enctype="multipart/form-data">
                  {{-- UPDATE REQUIREMENTS --}}
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label for="firstname">Proof of Payment</label>
                      <div id="items-container"></div>
                      <div id="preview" class="form-group"></div>
                      <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="payment_upload" id="payment_upload">
                            <label class="custom-file-label" for="payment_upload">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- // UPDATE REQUIREMENTS --}}
                </form>
              </div>
            </div>
          </div>
          {{-- END UPLOAD REQUIREMENTS --}}


        </div>
        <!-- /.tab-content -->
      </div>
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
@endsection


@section('custom-script')
@endsection


{{--  <div class="col-md-12">
            <div class="box box-default">
              <div class="box-body" style="background-color: #FFF; padding: 20px;">
                <div class="row">
                    
                  <div class="col-md-3">
                    <img height="200" src="{{ url($avatar) }}" alt="student photo" style="display: block; margin: auto;">
                  </div> 

                  <div class="col-md-6">
                    <h3 class="m-b-0" style="color: #81b8e8;">{{ $student->fullname }}</h3>
                    <p style="font-weight: 500;">WIS - {{ $student->studentnumber }}</p>
                    <br>
                    <p><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-weight: 500;">{{ $student->residentialaddress ?? 'n/a' }}</span></p>
                    <p><i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp; <span style="font-weight: 500;">{{ $student->contactnumber ?? 'n/a' }}</span></p>
                    <p><i class="fa fa-envelope"></i>&nbsp;&nbsp; <span style="font-weight: 600;">{{ $student->email ?? 'n/a' }}</span></p>
                  </div>

                  <div class="col-md-3">
                    <h5>GUARDIAN</h5>
                    <br>
                    
                    <div class="guardian-wrapper">
                      <p style="padding: 10px; border: 1px solid #DCE9F1; text-align: center; font-weight: 600; border-radius: 5px;">{{ $student->legalguardian ?? 'n/a' }}</p>
                    </div>
                  </div>
                  
                  <div class="col-lg-12">
                    <hr>
                  </div>

                </div>
              </div>
            </div>
          </div> --}}