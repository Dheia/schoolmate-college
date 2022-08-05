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

@section('content')

  <div class="m-t-15">
      <div class="col-md-8">
        <div class="box" style="border-radius: 5px;">
          <div class="box-header with-border" style="padding: 20px !important;">
            <h3 class="box-title"><b>Academic Profile</b></h3>

            <div class="box-tools pull-right">

            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="padding: 10px;">
            {{-- <div class="row"> --}}
              {{-- TABLE --}}
              <div class="" style="display: table; width: 100%; ">

                <div class="text-center" style="display: table-cell; vertical-align: middle; padding: 10px">
                  <img height="60" src="{{ url($avatar) }}" alt="student photo" style="display: block; box-shadow: 1px 1px 2px 1px #ccc; border-radius: 5px;">
                </div>
                <div class="text-center" style="display: table-cell; vertical-align: top; padding: 10px;">
                  <span class="label" style="color: #a8a8a8;">STUDENT NO.</span>
                  <p class="caption" style="font-weight: 500; color: #333;">{{ $student->studentnumber }}</p>
                </div>
                <div class="text-center" style="display: table-cell; vertical-align: top; padding: 10px;">
                  <span class="label" style="color: #a8a8a8;">FULL NAME</span>
                  <p class="caption" style="font-weight: 500; color: #333;">{{ $student->full_name }}</p>
                </div>
                <div class="text-center" style="display: table-cell; vertical-align: top; padding: 10px;">
                  <span class="label" style="color: #a8a8a8;">BIRTHDATE</span>
                  <p class="caption" style="font-weight: 500; color: #333;">{{ $student->birthdate->format('F   d, Y') }}</p>
                </div>
                <div class="text-center" style="display: table-cell; vertical-align: top; padding: 10px;">
                  <span class="label" style="color: #a8a8a8;">GENDER</span>
                  <p class="caption" style="font-weight: 500; color: #333;">{{ $student->gender }}</p>
                </div>
                <div class="text-center" style="display: table-cell; vertical-align: top; padding: 10px;">
                  <span class="label" style="color: #a8a8a8;">CURRENT LEVEL</span>
                  <p class="caption" style="font-weight: 500; color: #333;">{{ $student->current_level }}</p>
                </div>

              </div>
            {{-- </div> --}}
            <!-- /.row -->
          </div>
          <!-- ./box-body -->
          <!-- /.box-footer -->
        </div>
        <!-- /.box -->
      </div>
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