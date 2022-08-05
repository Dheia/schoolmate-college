@extends('backpack::layout')

@section('header')
        <section class="content-header">
          <h1>
        {{-- <span class="text-capitalize">{{ $crud->entity_name_plural }}</span> --}}
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
          </h1>
          <ol class="breadcrumb">
            {{-- <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li> --}}
            {{-- <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li> --}}
            {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
          </ol>
        </section>
@endsection

@section('content')


<div id="app">

        <grade-encode></grade-encode>
        
</div>


@endsection


@section('after_styles')
       {{-- <script src="{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script> --}}
        {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap4.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="css/app.css"> --}}
        <link rel="stylesheet" href="{{ asset('css/clock.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('css/slick.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
        <link rel="stylesheet" type="text/css" href="../jqwidgets/styles/jqx.base.css" />
@endsection

@section('after_scripts') 
        <script type="text/javascript" src="../jqwidgets/jqxcore.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxdata.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxbuttons.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxscrollbar.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxmenu.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxlistbox.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxdropdownlist.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxgrid.js"></script>
        <script type="text/javascript" src="../jqwidgets/jqxgrid.selection.js"></script> 
        <script type="text/javascript" src="../jqwidgets/jqxgrid.columnsresize.js"></script> 
        <script type="text/javascript" src="../jqwidgets/jqxgrid.filter.js"></script> 
        <script type="text/javascript" src="../jqwidgets/jqxgrid.sort.js"></script> 
        <script type="text/javascript" src="../jqwidgets/jqxgrid.pager.js"></script> 
        <script type="text/javascript" src="../jqwidgets/jqxgrid.grouping.js"></script>
        


        <script src='js/app.js' charset="utf-8"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
@endsection