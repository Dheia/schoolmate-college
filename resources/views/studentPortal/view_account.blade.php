@extends('backpack::layout_student')

@section('header')
   {{--  <section class="content-header">
      <h1>
        Enrollments<small>Tuition Fees</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('student/enrollments') }}">Enrollments</a></li>
        <li class="active">Tuition</li>
      </ol>
    </section> --}}
@endsection

@section('content')
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('student/enrollments') }}">Enrollments</a></li>
          <li><a class="text-capitalize active">Tuition</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollments</span>
        <small>Tuition Fees</small>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <section class="row">

      <div class="col-md-12">
        
        {{-- <div class="box">
          <div class="box-body">
            <tuition-table-student school-name="{{ config('settings.schoolname') }}" :id={{request()->enrollment_id}}>
            </tuition-table-student>           

          </div>
        </div> --}}
        <tuition-table-student school_name="{{ config('settings.schoolname') }}">
            </tuition-table-student>
      </div>

  </section>
@endsection

@section('after_styles')

@endsection

@section('after_scripts')
  <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection