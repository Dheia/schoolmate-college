@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        <div class="row m-b-10">
          <div class="col-xs-6">
            @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
            <div class="hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

              @include('crud::inc.button_stack', ['stack' => 'top'])

            </div>
            @endif
          </div>
          <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-right"></div>
          </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="overflow-hidden">
    
          <table id="list"><tr><td/></tr></table>
          <div id="pager"></div>


          @if ( $crud->buttons->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="hidden-print">
            @include('crud::inc.button_stack', ['stack' => 'bottom'])

            <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
          </div>
          @endif

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_styles')
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/redmond/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="http://www.ok-soft-gmbh.com/jqGrid/jquery.jqGrid-4.0.0/css/ui.jqgrid.css" />
    

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://www.ok-soft-gmbh.com/jqGrid/jquery.jqGrid-4.0.0/js/i18n/grid.locale-en.js"></script>
    <script type="text/javascript" src="http://www.ok-soft-gmbh.com/jqGrid/jquery.jqGrid-4.0.0/js/jquery.jqGrid.src.js"></script>


<script>

$(document).ready(function () {
            var mydata = [
                    {
                      id:"1", 
                      invdate:"2007-10-01",
                      name:"test_test_test_test_test",  
                      note:"note", 
                      amount:"200.00",
                      tax:"10.00",
                      closed:true, 
                      ship_via:"TN",
                      total:"210.00"
                    },
                    {
                      id:"2",
                      invdate:"2007-10-02",
                      name:"test2222222222222222",
                      note:"note2", 
                      amount:"300.00",
                      tax:"20.00",
                      closed:false,
                      ship_via:"FE",
                      total:"320.00"
                    },
                ],
            
            mygrid = $("#list"),
            colModel, i, cmi, tr = "<tr>", skip = 0, ths;

            mygrid.jqGrid({
                datatype:'local',
                autowidth: true,
                inserting: true,
                cellEdit : true,
                data: mydata,
                colNames:['Inv No','Date','Client A','Client B','Amount','Tax','Total','Closed','Shipped via','Notes'],
                colModel:[
                    {
                      name:'id',
                      index:'id',
                      width:70,
                      align:'center', 
                      sorttype: 'int'
                    },
                    {
                      name:'invdate',
                      index:'invdate',
                      width:80, 
                      align:'center', 
                      sorttype:'date',
                      formatter:'date', 
                      formatoptions: {
                        newformat:'d-M-Y'
                      }, 
                      datefmt: 'd-M-Y'
                    },
                    {
                      name:'name',
                      index:'name', 
                      width:70,
                      cellattr: function(rowId, tv, rawObject, cm, rdata) {
                        if (Number(rowId) < 5) { return ' colspan=' }
                      }
                    },
                    {
                      name:'nameB',
                      index:'nameB', 
                      width:70,
                      cellattr: function(rowId, tv, rawObject, cm, rdata) {
                          if (Number(rowId) < 5) { return ' style="display:none;"' }
                      }
                    },
                    {
                      name:'amount',
                      index:'amount',
                      width:100, 
                      editable:true, 
                      formatter:'number', 
                      align:'right'
                    },
                    {
                      name:'tax',
                      index:'tax',
                      width:70, 
                      formatter:'number', 
                      editable:true, 
                      align:'right'
                    },
                    {
                      name:'total',
                      index:'total',
                      width:120, 
                      formatter:'number', 
                      align:'right'
                    },
                    {
                      name:'closed',
                      index:'closed',
                      width:110,
                      align:'center', 
                      formatter: 'checkbox',
                      edittype:'checkbox',
                      editable: true,
                      editoptions:{
                        value:'Yes:No',
                        defaultValue:'Yes'
                      }
                    },
                    {
                      name:'ship_via',
                      index:'ship_via',
                      width:120,
                      align:'center',
                      formatter:'select',
                      edittype:'select',
                      editoptions:{
                        value:'FE:FedEx;TN:TNT;IN:Intim',
                        defaultValue:'Intime'
                      }
                    },
                    {
                      name:'note',
                      index:'note',
                      width:100,
                      sortable:false
                    }
                ],
                rowNum:10,
                rowList:[5,10,20],
                pager: '#pager',
                gridview:true,
                rownumbers:true,
                sortname: 'invdate',
                viewrecords: true,
                sortorder: 'desc',
                caption:'Student',
                height: '100%',
            });

            colModel = mygrid[0].p.colModel;
            ths = mygrid[0].grid.headers;
            for(i=0;i<colModel.length;i++) {
                cmi = colModel[i];
                if (cmi.name !== 'amount') {
                    if (skip === 0) {
                        $(ths[i].el).attr("rowspan", "2");
                    } else {
                        skip--;
                    }
                } else {
                    tr += '<th class="ui-state-default ui-th-ltr" colspan="3" role="columnheader">Information about the Price</th>';
                    skip = 2; // because we make colspan="3" the next 2 columns should not receive the rowspan="2" attribute
                }
            }
            tr += "</tr>";
            mygrid.closest("div.ui-jqgrid-view").find("table.ui-jqgrid-htable > thead").append(tr);
        });
   

</script>
  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->


  @stack('crud_list_scripts')
@endsection
