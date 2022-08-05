@extends('backpack::layout')

@push('after_styles')
<style>
  .p-t-1px{
    padding-top: 1px;
  }
  .p-t-2px{
    padding-top: 2px;
  }
  .p-t-3px{
    padding-top: 3px;
  }
  .p-t-4px{
    padding-top: 4px;
  }
  .p-t-5px{
    padding-top: 5px;
  }
</style>
@endpush

@section('header')
	{{-- <section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section> --}}
@endsection

@section('content')
<!-- HEADER START -->
<div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
          </li>
          <li>
            <a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a>
          </li>
          <li class="active">{{ trans('backpack::crud.list') }}</li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      </h1>
      <div class="col-xs-6">
          <div id="datatable_search_stack" class="pull-left" placeholder="Search Student"></div>
      </div>
    </div>
  </div>
<!-- HEADER END -->

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

        <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-visible="{{ var_export($column['visibleInTable'] ?? true) }}"
                    data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}"
                    data-visible-in-export="{{ var_export($column['visibleInExport'] ?? true) }}"
                    >
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th>{!! $column['label'] !!}</th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

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
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	@include('crud::inc.datatables_logic')

    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

    <!-- PUBLISH SCRIPT -->
    @if ($crud->hasAccess('publish'))
    <script>
      if (typeof publishPayment != 'function') {
        $("[data-button-type=publishPayment]").unbind('click');

        function publishPayment(button)
        {
          // ask for confirmation before publishing an item
          // e.preventDefault();
          var button = $(button);
          var id = button.attr('data-id');
          var route = button.attr('data-route');
          var btnFullname = button.attr('data-fullname');

          var btnAmount       = button.attr('data-amount');
          var btnFee          = button.attr('data-fee');
          var btnTotalPayment = button.attr('data-total-payment');

          var btnPublishedAmount = button.attr('data-published-amount');
          var btnUnpublishedAmount = button.attr('data-unpublished-amount');

          var btnPaymentMethod = button.attr('data-payment-method');

          $.confirm({
            title: "Publish",
            content: " " +
              '<form id="publishForm" action="' + route + '" method="post">' +
                '@csrf' +
                '<input type="hidden" id="paynamics_payment_id" name="paynamics_payment_id" class="form-control" value="' + id + '"/>'+
                '<div class="form-group">' +
                  '<table class="table table-striped">' +
                    '<tbody>' +
                      '<tr>' +
                        '<th>Student:</th>' +
                        '<td>' + btnFullname + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Payment Method:</th>' +
                        '<td>' + btnPaymentMethod + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Amount:</th>' +
                        '<td>' + btnAmount + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Fee:</th>' +
                        '<td>' + btnFee + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Total:</th>' +
                        '<td>' + btnTotalPayment + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Published Amount:</th>' +
                        '<td>' + btnPublishedAmount + '</td>' +
                      '</tr>' +
                      '<tr>' +
                        '<th>Unpublish Amount:</th>' +
                        '<td>' + btnUnpublishedAmount + '</td>' +
                      '</tr>' +
                    '</tbody>' +
                  '</table>' +
                '</div>' +
                '<div class="form-group loading-gif" style="display: block;">' +
                    '<img class="img-responsive" src="/vendor/backpack/crud/img/ajax-loader.gif" \
                      alt="Loading..." style="margin: auto;">' +
                '</div>' +
                '<div class="form-group payment_for" style="display: none;">' +
                    '<label>Payment For:</label>' +
                    '<select class="form-control" id="payment_for" name="payment_for" required>' +
                        '<optgroup label="Enrollment" id="optgroup-tuition"> \
                            <option value selected>Tuition And Other Fee</option> \
                        </optgroup>' +
                        '<optgroup label="Other Programs" id="optgroup-other-programs"> \
                        </optgroup>' +
                        '<optgroup label="Other Services" id="optgroup-other-services"> \
                        </optgroup>' +
                        '<optgroup label="Additional Fees" id="optgroup-additional-fees"> \
                        </optgroup>' +
                    '</select>' +
                '</div>' +
                '<div class="form-group amount" style="display: none;">' +
                    '<label>Amount</label>' +
                    '<input type="number" id="amount" name="amount" placeholder="Amount" class="form-control" required/>' +
                '</div>' +
                '<div class="form-group description" style="display: none;">' +
                    '<label>Description</label>' +
                    '<textarea id="description" name="description" placeholder="Description" class="form-control" required></textarea>' +
                '</div>' +
              '</form>',
            icon: "fa fa-question-circle",
            buttons: {
              cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              btnClass: "btn-default",
              closeModal: true,
              },
              confirm: {
                text: "Publish",
                value: true,
                visible: true,
                btnClass: 'btn-success',
                action: function(){
                  var url = route;
                  var inputAmount = $('#amount').val();
                  var inputDesc   = $('#description').val();
                  if(! inputAmount > 0) {
                    pNotify('You must enter amount', 'warning', true);
                    return false;
                  }
                  if(! inputDesc > 0) {
                    pNotify('You must enter description', 'warning', false);
                    return false;
                  }
                  $('#publishForm').submit();
                }
              }
            }
          });
          getPaymentFor(id); 
        }
      }

      /********************
      * GET PAYMENT FOR DATA
      *********************/
      function getPaymentFor(id)
      {
        $.ajax({
          url: "api/online-payments/" + id  +"/payment-types/get",
          success: function(response){
            if(response.status == 'success') {
              // Other Programs
              var selected_other_programs = '';
              $.each(response.data.selected_other_programs, function( index, value ) {
                if(value.remaining_balance > 0) {
                  selected_other_programs += '<option value="' + value.id + '|OtherProgram">' + 
                                                value.other_program.name + ' (PHP ' + value.remaining_balance +')'  + 
                                              '</option>';
                }
              });
              $('#optgroup-other-programs').append(selected_other_programs);
              // Other Services
              var selected_other_services = '';
              $.each(response.data.selected_other_services, function( index, value ) {
                if(value.remaining_balance > 0) {
                  selected_other_services += '<option value="' + value.id + '|OtherService">' + 
                                                value.other_service.name + '<br> (PHP ' + value.remaining_balance +')'  + 
                                              '</option>';
                }
              });
              $('#optgroup-other-services').append(selected_other_services);
              // Additional Fees
              var additional_fees = '';
              $.each(response.data.additional_fees, function( index, value ) {
                if(value.remaining_balance > 0) {
                  additional_fees +=  '<option value="' + value.id + '|AdditionalFee">' + 
                                        value.description + ' (PHP ' + value.remaining_balance +')'  + 
                                      '</option>';
                }
              });
              $('#optgroup-additional-fees').append(additional_fees);

              stopLoading();
              $('.payment_for').show();
              $('.amount').show();
              $('.description').show();
            }
            else {
              pNotify(response.message, 'warning', true);
            }
            
          }
        });
      }

      /********************
      * PNotify
      *********************/
      function pNotify(message, type, icon)
      {
        new PNotify({
          text: message,
          type: type
        });
        if(!icon) {
          $('.ui-pnotify-icon').remove();
        }
        else {
          let iconElement = document.querySelector(".ui-pnotify-icon");
          iconElement.classList.add("p-t-4px");
        }
      }

      /********************
      * STOP LOADING
      *********************/
      function stopLoading()
      {
        $('.loading-gif').hide();
      }

      /********************
      * START LOADING
      *********************/
      function startLoading()
      {
        $('.loading-gif').show();
      }
    </script>
    @endif

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
@endsection
