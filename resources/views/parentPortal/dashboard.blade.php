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
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de;">
        <div class="box-body box-profile">
          @php
            $avatar = $parent ? $parent->photo : 'images/headshot-default.png';
          @endphp
          <img class="profile-user-img img-responsive img-circle" src="{{ url($parent->photo) }}" alt="User profile picture">

          <h3 class="profile-username text-center">{{ $parent->fullname }}</h3>

          {{-- <p class="text-muted text-center bold">{{ $parent->prefixed_student_number }}</p> --}}
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
          <p class="text-muted">{{ $parent->mobile ?? '-' }}</p>
          <hr>
          <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
          <p class="text-muted">{{ $parent->residentialaddress ?? '-' }}</p>
          <hr>
          <strong><i class="fa fa-birthday-cake margin-r-5"></i> Birthday</strong>
          <p class="text-muted">{{ date('F m, Y', strtotime($parent->birthdate)) }}</p>
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
          </ul>
        </div>
        
        <div class="tab-content" style="border-radius: 5px;">
          <!-- START PROFILE INFORMATION -->
          <div class="tab-pane active" id="profile">
            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
              <div class="panel-heading"><h3><strong>Parent Information</strong></h3></div>
              <div class="panel-body">
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Name</label>
                  <p class="control-label" style="color: #333;">{{ $parent->fullname }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Email</label>
                  <p class="control-label">{{ $parent->email ?? '-' }}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                  <label class="control-label">Citizenship</label>
                  <p class="control-label">{{ $parent->citizenship ?? '-' }}</p>
                </div> 
              </div>
            </div>
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