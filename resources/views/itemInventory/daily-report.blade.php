@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      {{-- <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('after_styles')
  <style>
    .footer, #Header {
      display: none;
    }
  </style>
@endsection

@section('content')
<!-- Default box -->
 {{-- {!! dd(backpack_auth()->user()) !!} --}}


    <!-- THE ACTUAL CONTENT -->
        
        {{-- <button id="pdf" class="btn btn-primary">Print</button> --}}

        {{-- <div class="col-xs-12"> --}}
          
          <button id="pdf" class="btn btn-primary" style="margin-bottom: 10px;">Print &nbsp;<i class="fa fa-print" aria-hidden="true"></i></button>
        
        {{-- </div> --}}

        <div class="box" id="content">
          
          <div id="Header">
            <div class="col-sm-6 col-sm-offset-3" >
              <center>
                <div style="display: inline-block;">
                    <img width="90" src="../../{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="margin: auto; margin-top: 30px; display: block;">
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
                <div class="col-md-4"><p class="text-center" style="margin-bottom: 0; font-size: 24px; font-weight: 1000">DAILY REPORT</p></div>
                <div class="col-md-4"><p class="text-right" style="margin-bottom: 0; margin-top: 10px;"><b>Date:</b> {{ \Carbon\Carbon::now()->format('M d, Y') }}</p></div>
              </div>
            </div>
          </div>



          <table class="table" border="0">
            <thead>
              <tr>
                <td>
                  <div class="header-space">&nbsp;</div>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="content">
                    <table class="table" cellspacing="0" border="0" style="border: 0;">
                      <thead>
                          <th class="text-center">INVOICE NO.</th>
                          <th class="text-center">ITEMS</th>
                          <th class="text-center">TOTAL</th>
                      </thead>
                      <tbody>
                          <?php 
                            $grandTotal = 0;
                          ?>
                          @foreach($reports as $report)
                          {{-- {{dd($report)}} --}}
                            <?php $grandTotal += $report->total; ?>
                            <tr>
                              <td class="text-center" style="vertical-align: middle;">{{ $report->invoice_no }}</td>
                              <td style="vertical-align: middle;">
                                {{-- {{ $report->items }} --}}
                                <ul class="list-group">
                                    @foreach(json_decode($report->items) as $item)
                                      <li class="list-group-item" style="display: flex; justify-content: space-between; flex-wrap: nowrap; flex-direction: row;">
                                        <span style="flex-grow:1; flex-basis: 0;">{{ \App\Models\ItemInventory::findOrFail($item->item_id)->name }}</span>
                                        <span class="text-center" style="flex-grow:1; flex-basis: 0;">x{{ $item->quantity }}</span>
                                        <span class="text-right" style="flex-grow:1; flex-basis: 0;">P{{ $item->price }}</span>
                                        {{-- <span class="text-center" style="flex-grow:1; flex-basis: 0;">{{ $item->quantity * $item->price }}</span> --}}
                                      </li>
                                    @endforeach
                                </ul>
                              </td>
                              <td class="text-center" style="vertical-align: middle;">Php {{ number_format((float)$report->total,2) }}</td>
                            </tr>
                          @endforeach
                          <tr>
                            <td colspan="2" class="text-right"><b>GRAND TOTAL:</b></td>
                            <td class="text-center"><b>Php {{ number_format((float)$grandTotal,2) }}</b></td>
                          </tr>
                      </tbody>
                      <tfoot>
                        <tr style="display: none;">
                          <td></td>
                          <td></td>
                        </tr>
                      </tfoot>
                  </table>
                  </div>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td>
                <div class="footer-space">&nbsp;</div>
                </td>
              </tr>
            </tfoot>
          </table>

         {{--  <div class="row">
            <div class="col-xs-6">
              <h1>1</h1>
            </div>
            <div class="col-xs-6">
              <h1>2</h1>
            </div>
          </div>
 --}}
          <div class="header">
            {{-- PUT CONTENT HEADER HERE --}}
          </div>
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
          
          
        </div><!-- /.box-body -->
          
        

        <div id="editor"></div>
@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  {{-- <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" /> --}}
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css"> --}}
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css"> --}}

{{-- 
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}"> --}}

  {{-- <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/reorder.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('vendor/backpack/nestedSortable/nestedSortable.css') }}"> --}}

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script> --}}
  <script>
      // var doc = new jsPDF();
        // var specialElementHandlers = {
        // '#editor': function (element, renderer) {
        // return true;
        // }
      // };

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
	{{-- @include('crud::inc.datatables_logic') --}}
{{-- 
  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script> --}}


  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
 {{--  <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/backpack/crud/js/reorder.js') }}"></script>
  <script src="{{ url('vendor/backpack/nestedSortable/jquery.mjs.nestedSortable2.js') }}" type="text/javascript"></script>
 --}}
  @yield('custom_script')
  
  @stack('crud_list_scripts')
@endsection

