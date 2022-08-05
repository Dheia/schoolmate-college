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

  <!-- HEADER START -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url( '/student/dashboard') }}">Student</a></li>
        <li class="active">Change Password</li>
      </ol>
    </section>
    </div>
  </div>
  <!-- HEADER END -->

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
          <p class="text-muted">{{ $student->residentialaddress ? $student->residentialaddress : '-' }}</p>
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
            <li class="active"><a href="#change-password" data-toggle="tab" aria-expanded="true">Change Password</a></li>
          </ul>
        </div>
        
        <div class="tab-content" style="border-radius: 5px;">
          <!-- START PROFILE INFORMATION -->
          <div class="tab-pane active" id="change-password">
            <form class="form" action="{{url('student/change-password/submit')}}" method="post">
                  {!! csrf_field() !!}
              <div class="panel panel-default" style="box-shadow: none; border-top: none;">
                <div class="panel-heading"><h3><strong>Change Password</h3></div>
                <div class="panel-body">
                    @if ($errors->count())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $e)
                              <li>{{ $e }}</li>
                              @endforeach
                          </ul>
                      </div>
                    @endif
                    <div class="form-group">
                        <label class="need">Old password</label>
                        <input class="form-control" type="password" name="old_password" id="old_password" value="" placeholder="Old password">
                    </div>
                    <div class="form-group">
                        <label class="need">New password</label>
                        <input class="form-control" type="password" name="new_password" id="new_password" value="" placeholder="New password">
                    </div>
                    <div class="form-group">
                        <label class="need">Confirm password</label>
                        <input class="form-control" type="password" name="confirm_password" id="confirm_password" value="" placeholder="Confirm password">
                    </div>
                </div>
                <div class="panel-footer">
                  <button type="submit" class="btn btn-success"><span class="ladda-label"><i class="fa fa-save"></i> {{ trans('backpack::base.change_password') }}</span></button>
                      <a href="{{ url('student/dashboard') }}" class="btn btn-default"><span class="ladda-label">{{ trans('backpack::base.cancel') }}</span></a>
                </div>
              </div>
            </form>
          </div>
          <!-- END PROFILE INFORMATION -->


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