@extends('backpack::layout')

@section('header')
  <section class="content-header">
        <span class="text-capitalize"><h1>Vendor</h1></span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
        
        
    <ol class="breadcrumb">
      {{-- <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li> --}}
      {{-- <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li> --}}
      {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
    </ol>
  </section>
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <!-- Default box -->
    {{-- @if ($crud->hasAccess('list')) --}}
      {{-- <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br> --}}
    {{-- @endif --}}
    {{-- @include('crud::inc.grouped_errors') --}}
    {{-- Show the errors, if any --}}
    @if ($errors->any())
        <div class="callout callout-danger">
            <h4>{{ trans('backpack::crud.please_fix') }}</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box">

      <div class="box-header with-border">
        {{-- <a href="{{ url()->current() }}/create" class="btn btn-primary">New Vendor</a> --}}
      </div>

      <div class="box-body">
        
        <form method="post" action="{{ url('admin/quickbooks/vendor') }}">
          {{-- @if ($crud->hasUploadFields('create')) --}}
          {{-- enctype="multipart/form-data" --}}
          {{-- @endif --}}
            {{-- > --}}
            {!! csrf_field() !!}

            <div class="form-group col-md-1">
              <label for="title">Title</label>
              <input type="text" id="title" name="title" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="firstname">First Name</label>
              <input type="text" name="firstname" id="firstname" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="middlename">Middle Name</label>
              <input type="text" name="middlename" id="middlename" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="lastname">Last Name</label>
              <input type="text" name="lastname" id="lastname" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="suffix">Suffix</label>
              <input type="text" name="suffix" id="suffix" class="form-control">
            </div>
          

            <div class="form-group col-md-12">
              <label for="company">Company</label>
              <input type="text" name="company" id="company" class="form-control">
            </div>


            <div class="form-group col-md-12">
              <label for="displayName">Display Name As*</label>
              <input type="text" name="display_name" id="displayName" class="form-control">
            </div>

            <div class="form-group col-md-12" style="margin: 0;">
              <div class="checkbox">
                <label for="useDisplayName" style="padding: 0;">
                  <div style="display: inline-block;">
                    <span>
                      <b>Print on check as</b>
                    </span>
                    <span>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="useDisplayName" checked value="1" name="use_display_name"> 
                    </span>
                    <span>Use display name</span>
                  </div>
                </label>
              </div>
            </div>

            <div class="form-group col-md-12">
              <label for="PrintOnCheckName"></label>
              <input type="text" id="PrintOnCheckName" name="PrintOnCheckName" class="form-control" disabled>
            </div>
            
            <div class="col-md-12">
              <hr>
            </div>
          
            <div class="form-group col-md-12">
              <label>Address</label>
              <textarea name="Street" id="" class="form-control" placeholder="Street"></textarea>
            </div>
            <div class="form-group col-md-6">
                <input type="text" name="CityTown" placeholder="City/Town" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <input type="text" name="StateProvince" placeholder="State/Province" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <input type="text" name="ZIPCode" placeholder="ZIP Code" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <input type="text" name="Country" placeholder="Country" class="form-control">
            </div>

            
            <div class="col-md-12">
              <hr>
            </div>

            <div class="col-md-12">
              <label for="Notes">Notes</label>
              <textarea name="Notes" id="Notes" class="form-control"></textarea>
            </div>

            <div class="col-md-12">
              <hr>
            </div>
            
            <div class="form-group col-md-12">
              <label for="PrimaryEmailAddr">Email</label>
              <input type="text" id="PrimaryEmailAddr" placeholder="Seperate multiple emails with commas" name="PrimaryEmailAddr" class="form-control">
            </div>

            <div class="form-group col-md-6">
              <label for="PrimaryPhone">Phone</label>
              <input type="text" name="PrimaryPhone" id="PrimaryPhone" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="Mobile">Mobile</label>
              <input type="text" name="Mobile" id="Mobile" class="form-control">
            </div>

            <div class="form-group col-md-12">
              <label for="Website">Website</label>
              <input type="text" name="Website" id="Website" class="form-control">
            </div>


            <div class="col-md-12">
              <hr>
            </div>

            <div class="box-footer">
              <button class="btn btn-success">Add Vendor</button>
              <a href="{{ url()->previous() }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
                      {{-- @include('crud::inc.form_save_buttons') --}}
            </div><!-- /.box-footer-->

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('after_scripts')

@endsection
