<?php 
  
  $turnstiles = App\Models\Turnstile::get();

?>

@extends('backpack::layout')

@section('after_styles')
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css"> --}}
  <style>
    .content-wrapper {
/*        background: rgb(105,155,200);
        background: -moz-radial-gradient(top left, ellipse cover, rgba(105,155,200,1) 0%, rgba(181,197,216,1) 57%);
        background: -webkit-gradient(radial, top left, 0px, top left, 100%, color-stop(0%,rgba(105,155,200,1)), color-stop(57%,rgba(181,197,216,1)));
        background: -webkit-radial-gradient(top left, ellipse cover, rgba(105,155,200,1) 0%,rgba(181,197,216,1) 57%);
        background: -o-radial-gradient(top left, ellipse cover, rgba(105,155,200,1) 0%,rgba(181,197,216,1) 57%);
        background: -ms-radial-gradient(top left, ellipse cover, rgba(105,155,200,1) 0%,rgba(181,197,216,1) 57%);
        background: radial-gradient(ellipse at top left, rgba(105,155,200,1) 0%,rgba(181,197,216,1) 57%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#699bc8', endColorstr='#b5c5d8',GradientType=1 );*/
    }
  </style>
@endsection

@section('header')
    <section class="content-header">
      {{-- <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol> --}}
    </section>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12" style="padding: 40px;">
            <div class="box box-default">
                <div class="box-header with-border">
                    {{-- <div class="box-title">{{ trans('backpack::base.login_status') }}</div> --}}
                  
                  @if($crud->hasAccess('create'))
                    <a href="{{ url($crud->route) }}/create" class="btn btn-primary"><i class="fa fa-plus"></i> &nbsp; Add Turnstile</a>
                  @endif
                </div>

                {{-- <div class="box-body">{{ trans('backpack::base.logged_in') }}</div> --}}

                
            </div>

            <div class="box-default row">
              
              @foreach($turnstiles as $turnstile)
                  <div class="col-md-4" id="turnstile-card">
                    <div class="card" style="background-color: #FFF; box-shadow: 1px 1px 1px 1px #ccc;">
                        {{-- <svg src="{{ asset('images/turnstiles.svg') }}" alt="" style="height: 300px; display: block"></svg> --}}
                        <h4 style=" padding: 10px;
                                    text-align: center;
                                    background-color: #13608B;
                                    display: block;
                                    color: #FFF;">
                                  {{ $turnstile->name }}
                        </h4>
                        <div style="padding: 20px; width: fit-content; margin: auto;">
                            <p><strong>IP Address </strong>: <small>{{ $turnstile->ip_address }}</small></p>
                            <p><strong>Status </strong>: <small id="turnstile_status" class="label label-success">connecting...</small></p>
                            
                            <div class="btn-action" style="margin-top: 30px;">
                              {{-- <button class="btn btn-default btn-sm">View Logs</button> --}}
                              <button class="btn btn-danger btn-xs" onclick="reboot('{{ str_slug($turnstile->name) }}')">Reboot</button>
                              @if ($crud->hasAccess('update'))
                                <a href="turnstile/{{ $turnstile->id }}/edit" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> Edit</a>
                              @endif
                              @if ($crud->hasAccess('delete'))
                                <a href="javascript:void(0)" onclick="deleteEntry({{ $turnstile->id }})" data-route="{{ url($crud->route.'/'.$turnstile->id) }}" class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i> {{ trans('backpack::crud.delete') }}</a>
                              @endif
                              {{-- <button class="btn btn-primary btn-sm">Tunnel</button> --}}
                            </div>
                        </div>
                    </div>
                  </div>
              @endforeach

            </div> <!-- END OF BOX-DEFAULT -->
        </div>
    </div>
@endsection

@section('after_scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script> --}}
<script>
    
  function ping( name, ipaddress ) {
      this.name = name;
      this.ipaddress = ipaddress;
      $.ajax({
          type: 'GET',
          url: window.location.protocol + '//' + window.location.host + '/admin/turnstile/ping',
          data: { ip_address: this.ipaddress , name: this.name },
          success: function (res) {
            console.log(res);
              $('#' + res.TURNSTILE_NAME + ' #turnstile_status').text(res.TURNSTILE_MESSAGE);
          },
          error: function(data) {
            $('#' + name.toLowerCase() + ' #turnstile_status').text('Not Connected');
          }
      });
  }

  function reboot(name) {
    console.log(name);
    $.ajax({
      type: 'GET',
      url: window.location.protocol + '//' + window.location.host + '/admin/turnstile/reboot',
      data: { name: name },
      success: function (res) {
        console.log('success: ',res);
      },
      error: function (res) {
        console.log('error: ' , res);
      }
    });
  }

  @foreach($turnstiles as $turnstile)
    new ping( '{!! strtolower($turnstile->name) !!}', '{!! $turnstile->ipaddress !!}' );
  @endforeach
</script>

<script>
  if (typeof deleteEntry != 'function') {
    $("[data-button-type=delete]").unbind('click');

    function deleteEntry(button) {
        // ask for confirmation before deleting an item
        // e.preventDefault();
        var button = $(button);
        console.log(button);
        var route = button.attr('data-route');
        var row = $("#turnstile-card a[data-route='"+route+"']").closest('.card');

        if (confirm("{{ trans('backpack::crud.delete_confirm') }}") == true) {
            $.ajax({
                url: route,
                type: 'DELETE',
                success: function(result) {
                    // Show an alert with the result
                    new PNotify({
                        title: "{{ trans('backpack::crud.delete_confirmation_title') }}",
                        text: "{{ trans('backpack::crud.delete_confirmation_message') }}",
                        type: "success"
                    });

                    // Hide the modal, if any
                    $('.modal').modal('hide');

                    // Remove the details row, if it is open
                    if (row.hasClass("shown")) {
                        row.next().remove();
                    }

                    // Remove the row from the datatable
                    row.remove();
                },
                error: function(result) {
                    // Show an alert with the result
                    new PNotify({
                        title: "{{ trans('backpack::crud.delete_confirmation_not_title') }}",
                        text: "{{ trans('backpack::crud.delete_confirmation_not_message') }}",
                        type: "warning"
                    });
                }
            });
        } else {
            // Show an alert telling the user we don't know what went wrong
            new PNotify({
                title: "{{ trans('backpack::crud.delete_confirmation_not_deleted_title') }}",
                text: "{{ trans('backpack::crud.delete_confirmation_not_deleted_message') }}",
                type: "info"
            });
        }
      }
  }

  // make it so that the function above is run after each DataTable draw event
  // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@endsection