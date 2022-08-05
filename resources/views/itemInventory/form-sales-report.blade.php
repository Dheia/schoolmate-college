@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  
    <div class="row">
      <div class="col-md-2">
        <label for="">Report Period</label>
        <select name="report_name" id="reportPeriod" class="form-control">
          <option value="today"    {{ $report == 'today' ? 'selected' : '' }}>Today</option>
          <option value="thisWeek" {{ $report == 'thisWeek' ? 'selected' : '' }}>This Week</option>
          <option value="thisMonth"{{ $report == 'thisMonth' ? 'selected' : '' }}>This Month</option>
          <option value="custom"   {{ $report == 'custom' ? 'selected' : '' }}>Custom</option>
        </select>
      </div>
      <div class="col-md-2 start_date" style="padding: 0; margin: 0;{{ $report == 'custom' ? 'display: block' : '' }}">
        <label for="">&nbsp;</label>
        <input type="date" class="form-control" name="start_date" {{ $report == 'custom' ? 'value=' . request()->input('start_date') : '' }}>
      </div>
      <div class="col-md-2 end_date" style="padding: 0; margin: 0;{{ $report == 'custom' ? 'display: block' : '' }}">
        <label for="">&nbsp;</label>
        <input type="date" class="form-control" name="end_date" {{ $report == 'custom' ? 'value=' . request()->input('end_date') : '' }}>
      </div>
      <div class="col-md-2">
        <label for="">&nbsp;</label><br>
        <a href="javascript:void(0)" id="runReport" class="btn btn-primary">Run Report</a>
      </div>
    </div>


	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url('admin/item-inventory/') }}" class="text-capitalize">Item Inventory</a></li>
	    <li class="active">Report</li>
	  </ol>
	</section>
@endsection

@section('after_styles')
<style>
  .start_date, .end_date, .footer, #Header {
    display: none;
  },
</style>
@endsection

@section('content')
<!-- Default box -->
 {{-- {!! dd(backpack_auth()->user()) !!} --}}

<div class="row">
  <div class="col-md-12">
    <!-- THE ACTUAL CONTENT -->
        
        {{-- <button id="pdf" class="btn btn-primary">Print</button> --}}

        {{-- <div class="col-xs-12"> --}}
          
        
        {{-- </div> --}}

        <div class="box">
          <div class="box-header">
            <div class="col-md-6"></div>
            <div class="col-md-6">
              @if($report !== null)
                <button id="pdf" class="btn btn-success" style="float: right;"><i class="fa fa-print"></i> &nbsp;Print</button>
              @endif
            </div>

          </div>

          
          <div class="box-body">
            @if($report !== null)
              <div id="content">

                <div id="Header2">
                  <div class="col-sm-6 col-sm-offset-3" >
                    <center>
                      <div style="display: inline-block;">
                          <img width="80" src="../../../{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="margin: auto; margin-top: -90px; display: block;">
                      </div>      
                      <div style="display: inline-block;">
                          <h3 class="text-left">Sales Cafeteria</h3>
                          <h4 class="text-left">Westfield International School</h4>
                          <p>&nbsp;</p>
                      </div>
                    </center>
                  </div>


                   <div class="col-sm-12" style="margin-top: 20px;">
                    <div class="row">
                      <div class="col-md-4"></div>
                      <div class="col-md-4"><p class="text-center" style="margin-bottom: 0; font-size: 24px; font-weight: 1000">SALES REPORT</p></div>
                      <div class="col-md-4">
                        <p class="text-right" style="margin-bottom: 0; margin-top: 10px;">
                          @if($report == 'today')
                            <b>Date:</b> {{ \Carbon\Carbon::now()->format('M d, Y') }}
                          @elseif((
                            request()->input('start_date') == request()->input('end_date')) && 
                            request()->input('start_date') !== null && request()->input('end_date') !== null &&
                            request()->input('start_date') !== '' && request()->input('end_date') !== '' )
                            <b>Date:</b> {{ \Carbon\Carbon::parse(request()->input('end_date'))->format('M d, Y') }}
                          @else
                            <?php 
                              $start_year  = \Carbon\Carbon::parse(request()->input('start_date'))->format('Y');
                              $end_year    = \Carbon\Carbon::parse(request()->input('end_date'))->format('Y');
                              $start_month = \Carbon\Carbon::parse(request()->input('start_date'))->format('m');
                              $end_month   = \Carbon\Carbon::parse(request()->input('end_date'))->format('m');
                            ?>
                            @if($start_year == $end_year && $start_month == $end_month)
                              <b>Date:</b> {{ $reports['start_date']->format('M d') }} - {{ $reports['end_date']->format('d, Y') }}
                            @elseif($start_year == $end_year && $start_month !== $end_month)
                              <b>Date:</b> {{ $reports['start_date']->format('M d') }} - {{ $reports['end_date']->format('M d, Y') }}
                            @else
                              <b>Date:</b> {{ $reports['start_date']->format('M d, Y') }} - {{ $reports['end_date']->format('M d, Y') }}
                            @endif
                          @endif
                        </p>
                      </div>
                    </div>
                  </div>
                </div> <!-- End of #Header -->
                
                @include('itemInventory.table-sales-report', ['reports' => $reports['data']])               

                <div class="footer">
                  <div class="row" style="height: 80px;">
                    <div class="col-xs-6" style="padding-left: 0">
                      {{-- <div id="menu-outer" style="float: left;"> --}}
                          {{-- <ul id="horizontal-list"> --}}
                            <img width="50" src="{{ asset('images/WIS_LOGO.png') }}" alt="schoolmate logo">
                            <span>&nbsp;SchoolMate Copyright © {{ \Carbon\Carbon::now()->format('Y') }}</span>
                          {{-- </ul> --}}
                        {{-- </div> --}}
                      {{-- </div> --}}
                     
                    </div>
                    
                    <div class="col-xs-6">
                      <img width="100" src="{{ asset('images/logo-new-black.png') }}" alt="schoolmate logo" style="float: right; margin-top: -20px;">
                    </div>
                </div>

              </div> <!-- #content -->

            @else
                <h1 class="text-center">SELECT REPORT PERIOD</h1>
            @endif
          </div>
        
        <div class="clearfix"></div>
        </div><!-- /.box-body -->
          
  </div>
</div>
        
@endsection

@section('after_scripts')

  <script>
  
    $('#runReport').click(function () {
      if($('#reportPeriod option:selected').val() == 'custom') {

        if($('.start_date input').val() !== '' && $('.end_date input').val() !== '') {

          location.href = '{{ url("admin/item-inventory/sales-report") }}/' + $('#reportPeriod option:selected').val() + '?start_date=' + $('.start_date input').val() + '&end_date=' + $('.end_date input').val();

        }
        else { alert("Please Enter A Date"); }
      } 
      else {
        location.href = '{{ url("admin/item-inventory/sales-report") }}/' + $('#reportPeriod option:selected').val();
      }
    });

    $('#reportPeriod').on('change', function () {
        console.log(this.value);
        if(this.value == "custom") {
          $('.start_date, .end_date').css('display', 'block');
        } else {
          $('.start_date, .end_date').css('display', 'none');
        }
    });
  </script>


  <script>
    $('#pdf').click(function () {
        var divToPrint=document.getElementById('content');

        // var newWin=window.open('','Print-Window');
        var styles = "<style>\
                        \
                        .header {\
                          position: fixed;\
                          top: 0;\
                        }\
                        .footer {\
                          position: fixed;\
                          bottom: 0;\
                          width: 100%;\
                        }\
                      </style>";

        var assets ='<head><link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />' + styles + '</head>';
        var frame1 = document.createElement('iframe');
          frame1.name = "frame1";
          frame1.style.position = "absolute";
          frame1.style.top = "-1000000px";
          document.body.appendChild(frame1);
          var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
          frameDoc.document.open();
        // newWin.document.open();

        frameDoc.document.write('<html>' + assets + '<body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        frameDoc.document.close();

        setTimeout(function(){
          indow.frames["frame1"].focus();
              window.frames["frame1"].print();
              document.body.removeChild(frame1);
              // newWin.close();
        },10);
        return false;
    });
  </script>

@endsection

