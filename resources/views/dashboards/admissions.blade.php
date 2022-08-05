

@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Admissions Dashboard
        {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Admission Dashboard</li>
      </ol>
    </section>
@endsection

@section('after_styles')
    
@endsection

@section('content')

<div id="app">

    <admission-dashboard></admission-dashboard>

</div>

@endsection


@section('after_scripts')

<script src="/js/app.js"></script>


@endsection