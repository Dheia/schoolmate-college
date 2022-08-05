@extends('backpack::layout')
@section('header')
{{-- {{ dd(get_defined_vars()) }} --}}
        <section class="content-header">
          <h1>
        <span class="text-capitalize">Encode</span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
          </h1>
          <ol class="breadcrumb">
            <li><a href="" class="text-capitalize">Encode Grade</a></li>
            <li class="active">encode</li>
          </ol>
        </section>
@endsection


@section('content')


<div id="app">
      
      <div class="row m-b-10">
        <div class="col-md-12">
          <a href="/admin/encode-grade" class="hidden-print">
            <i class="fa fa-angle-double-left"></i> Back to all <span>Encode Grades</span>
          </a>
          <br><br>        
        </div> 
      </div>
      <table class="table table-bordered table-striped m-b-0" style="box-shadow: 1px -2px 21px #CCC;">
        <tbody>
          <tr>
            <td style="width: calc(100% / 5)">
              <b>School Year:</b>
              <span>&nbsp; {{ $schoolYear->schoolYear ?? '-' }}</span> 
            </td> 
            <td style="width: calc(100% / 5)">
              <b>Template Name:</b>
              <span>&nbsp; {{ $template->name ?? '-' }}</span> 
            </td>            
            <td style="width: calc(100% / 5)">
              <b>Subject:</b>
              <span>&nbsp; {{ $subject->subject_code ?? '-' }}</span>
            </td>            
            <td style="width: calc(100% / 5)">
              <b>Section:</b>
              <span>&nbsp; {{ $section->name ?? '-' }}</span>
            </td>     
            <td style="width: calc(100% / 5)">
              <b>Term:</b>
              <span>&nbsp; {{ request()->term_type ?? '-' }}</span>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="box">
        <grade-encode></grade-encode>
      </div>
        
</div>


@endsection


@section('after_styles')
        {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('jqwidgets/styles/jqx.base.css') }}">
        <link rel="stylesheet" href="{{ asset('jqwidgets/styles/jqx.bootstrap.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('jqwidgets/styles/jqx.material.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/jquery-confirm.css') }}">

@endsection

@section('after_scripts') 
  

        <script>
          function  getUrlVars() {
              var vars = {};
              var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                  vars[key] = value;
              });
              return vars;
          }
          let allParameters = '?template_id=' + this.getUrlVars().template_id + '&subject_id=' + this.getUrlVars().subject_id + '&section_id=' + this.getUrlVars().section_id + '&term_type=' + this.getUrlVars().term_type + '&school_year_id=' + this.getUrlVars().school_year_id;
   
        </script>

        

        <script src="{{ \Request::getSchemeAndHttpHost() }}/js/app.js"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
        <script src="{{ asset('js/jquery-confirm.js') }}"></script>
@endsection