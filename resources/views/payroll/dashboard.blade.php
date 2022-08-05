@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}
        {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection

@section('after_styles')
 
@endsection

@section('content')
    
  <div class="row">

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-id-card"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Total Payrolls</span>
          <span class="info-box-number">P 1, 328, 402.26<small></small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Total No. of Employees who TAPS IN</span>
          <span class="info-box-number">45</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Total SMS Blasted</span>
          <span class="info-box-number">4747</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-bell-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Total SMS Notificatoins Sent</span>
          <span class="info-box-number">30645</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>


  
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Latest 5 Payrolls</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table no-margin">
              <thead>
              <tr>
                <th>Payroll ID</th>
                <th>Date From</th>
                <th>Date To</th>
                <th>Employees</th>
                <th>Status</th>
                <th>Total</th>
              </tr>
              </thead>
              <tbody>
                @foreach($payrollRuns as $payrollRun)
                  <tr>
                    @php
                      $payRollId = $payrollRun->id;
                      if(strlen($payRollId) < 5) {
                        $prefixZeroCount = 5 - strlen($payRollId);
                        for($i = 1; $i <= $prefixZeroCount; $i++) {
                          $payRollId = '0' . $payRollId;
                        }
                      }
                    @endphp
                    <td><a href="#">PR-{{ $payRollId }}</a></td>
                    <td>{{ $payrollRun->date_from->format('F d, Y | D') }}</td>
                    <td>{{ $payrollRun->date_to->format('F d, Y | D') }}</td>
                    <td>{{ count($payrollRun->payrollRunItems) }}</td>
                    <td>
                      <span class="label {{ $payrollRun->status === 'UNPUBLISH' ? 'label-danger' : 'label-success'}}">
                        {{ $payrollRun->status }}
                      </span>
                    </td>
                    <td><b>P{{ number_format($payrollRun->total_net_pay, 2, ".", ", ") }}</b></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
 
@endsection


@section('after_scripts')
  {{-- <script src="{{ asset('js/moment.min.js') }}"></script> --}}
  <script src="{{ asset('js/chart.min.js') }}"></script>
  
  <script src="{{ asset('js/palette.js') }}"></script>





@endsection