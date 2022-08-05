@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
      </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.preview') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>

	<a href="javascript: window.print();" class="pull-right hidden-print"><i class="fa fa-print"></i></a>
@endif
<div class="row">
	<div class="{{ $crud->getShowContentClass() }}">

	<!-- Default box -->
	  <div class="m-t-20">
	    <div class="box no-padding no-border">
        <div class="box-header text-center">
            <h4>
                <b>Announcement</b>
            </h4>
        </div>
        <div class="box-body text-center">

            <!-- Message -->
            <h4>{!! $entry->message !!}</h4>

            <!-- Image -->
            @if($entry->image)
                <br>
                <img src="{{ asset($entry->image) }}" alt="">
            @endif
            <br>
            <br>
            <!-- Files -->
            @if($entry->files)
                @if( count($entry->files) > 0 )
                    <h5><b>Files: </b></h5>
                    @foreach ( $entry->files as $file )
                        <a href="{{ url($file) }}" target="_blank" download="{{ url($file) }}"> {{ url($file) }} </a>
                        @if(!$loop->last)
                            <br>
                        @endif
                    @endforeach
                @endif
            @endif

        </div>
        <div class="box-footer text-center">
            Posted By: <b>{{ $entry->user ? $entry->user->full_name : '-' }}</b>
        </div>
	    </div><!-- /.box-body -->
	  </div><!-- /.box -->

	</div>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
	<script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
@endsection