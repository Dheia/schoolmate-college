@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Vendors
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Payments</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
            <a href="{{ url()->current() }}/create" class="btn btn-primary">New Vendor</a>
          </div>
        
        <div class="box-body">
            
            
              



        </div> <!-- .box-body -->

      </div>
      </div>
    </div>
@endsection

@push('after_scripts')

@endpush