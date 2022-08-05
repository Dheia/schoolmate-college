@extends('backpack::layout_student')

@section('header')
    {{-- <section class="content-header">
      <h1>
        Enrollments<small>All enrollments list</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Enrollments</li>
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
          <li><a class="text-capitalize active">Enrollments</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollments</span>
        <small>All enrollments list</small>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <section class="row">

      <div class="col-md-12">
        
        <div class="box">
          <div class="box-body">
            
    
          </div>
        </div>
      </div>

  </section>

  <!-- ELIGIBLE ENROLLMENT -->
  @if( $enrollment != null)
  <section class="row">

      <div class="col-md-12">
        
        @if($enrollment != null)
        <div class="col-md-6 col-xs-12">
          <div class="small-box bg-primary">
            <div class="inner">
              <p>
                 {{ $enrollment->level->year }}
                <br>
                <b>{{ $enrollment->term_type . ' Term' }}</b>
                <br>
                {{ $enrollment->schoolYear->schoolYear }}
              </p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer" style="font-size: 16px;">
              Enroll now <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        @endif

      </div>

  </section>
  @endif
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@section('after_scripts')
  
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <script>
    $('.table').DataTable({
      "processing": true,
      "paging": false,
      "searching": true,
    });
  </script>

  <script type="text/javascript">
    
  </script>
    
@endsection