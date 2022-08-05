@extends('backpack::layout')

@section('header')
@endsection

@push('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
@endpush

@section('content')
  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>
  <div class="row">
        {{-- MAIN PANEL --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc">
          <h2 class="oc-header-title p-l-20 p-r-20 m-t-0">My Classes<br></h2>
          {{-- SEARCH CLASS VUE JS --}}
          <search-class></search-class>
          {{-- SEARCH CLASS VUE JS --}}

        </div>

        {{-- LEFT PANEL --}}
        {{-- <div class="col-md-3 col-lg-3 col-xs-12 oc"> --}}
          {{-- USER ACCOUNT PANEL --}}
          {{-- <div class="col-md-12 col-lg-12 col-xs-12 oc-box shadow">
            <i class="oc-icon oc-icon-profile-male"></i>
            @include('backpack::inc.sidebar_user_panel')
          </div>
          <div class="col-md-12 col-lg-12 col-xs-12 oc oc-box shadow">
            <i class="oc-icon oc-icon-desktop"></i>
            <h5 class="oc-user text-center">Content</h5>
          </div>
        </div> --}}
      </div>
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
  <link rel="stylesheet" href="/css/app.css">
@endsection

@push('before_scripts')
  {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/searchClass.js') }}"></script>
  <script>
      document.getElementById("nav-classes").classList.add("active");

      $('.startVideoConferencing').click(function () {
        var classCode = $(this).attr('classcode');
        console.log(classCode);
        $('#form' + classCode).submit();
      });

  </script>

@endpush
