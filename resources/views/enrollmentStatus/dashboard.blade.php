@extends('backpack::layout')

@section('header')
	{{-- <section class="content-header">
    <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
      <li><a class="text-capitalize active">{{ $crud->entity_name_plural }}</a></li>
    </ol>
  </section> --}}
@endsection

@section('after_styles')
  <style>
    @media only screen and (max-width: 768px) {
      #btnCreate {
        width: 100%;
      }
    }

  </style>
  <style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
  </style>
@endsection

@section('content')
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
          <li><a class="text-capitalize active">{{ $crud->entity_name_plural }}</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      @if ($crud->hasAccess('create'))
        <a id="btnCreate" href="{{ url($crud->route.'/create') }}" class="btn btn-success pull-left m-b-10" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
      @endif
    </div>
  </div>
  <div class="row m-t-15">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-radius: 5px;">
        <div class="box" style="border-radius: 5px;">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="container">
              <div class="col-md-6 col-lg-6">
                {{-- START  Old Student Option --}}
                <div class="row">
                   <h4>School Year</h4>
                  <div class="accordion" id="accordionExample">
                    @if(count($schoolYears)>0)
                      @foreach($schoolYears as $schoolYear)
                        <div class="card" id="school-year-{{$schoolYear->id}}">
                          <div class="card-header" id="heading-{{$schoolYear->id}}" style="margin: 0;">
                            <h2 style="margin: 0;">
                              <button style="text-align: left; width: 100%; margin: 0px; color: #fff; background-color: #3c8dbc; border: 0px solid;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{$schoolYear->id}}" aria-expanded="true" aria-controls="collapse-{{$schoolYear->id}}">
                                <i class="fa fa-caret-down"></i><span class="p-l-10">{{$schoolYear->schoolYear}}</span>
                              </button>
                            </h2>
                          </div>
                          <div id="collapse-{{$schoolYear->id}}" class="collapse" aria-labelledby="heading-{{$schoolYear->id}}" data-parent="#accordionExample">
                            <div class="card-body m-t-10">
                              @if(count($enrollmentStatuses)>0)
                                @if(count($enrollmentStatuses->where('school_year_id', $schoolYear->id))>0)
                                  @foreach($enrollmentStatuses->where('school_year_id', $schoolYear->id) as $enrollmentStatus)
                                    <h5 class="col-md-12 col-lg-12 col-12 col-xs-12 col-sm-12" style="background-color:#EEE; padding:5px; padding-left: 50px;margin: 0px;">{{$enrollmentStatus->department->name}}</h5>       
                                    
                                    @if(count($enrollmentStatus->items)>0)
                                      @foreach($enrollmentStatus->items as $item)
                                      <div class="row">
                                        <div class="col-md-4 col-lg-4 col-6 col-xs-6 col-sm-6"  style="padding-left: 100px; ">
                                          <h5>{{$item->term }} Term</h5>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-4 col-xs-4 col-sm-4" style="padding-top: 5px;">
                                          <input id="status_item_{{$item->id}}" class="pull-right" type="checkbox" data-toggle="toggle" data-style="ios" onclick="ShowHideDiv(this)" data-size="mini" data-width="50" {{$item->active ? "checked" : ""}}
                                          >
                                        </div>
                                      </div>
                                      @endforeach
                                    @endif

                                  @endforeach
                                @endif
                              @endif
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>

                </div>
                  
                {{-- END  Old Student Option --}}
              </div>
  
              <div class="col-md-6 col-lg-6" style="padding-left: 100px;">
                <div class="row">
                  
                </div>
              </div>

            </div>
          </div>
        </div>


    </div>
  </div>
@endsection

@section('after_scripts')
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script type="text/javascript"></script>
  <script>
    $(function() {
      @if($enrollmentStatuses)
        @if(count($enrollmentStatuses)>0)
          @foreach($enrollmentStatuses as $index => $enrollmentStatus)
            @if(count($enrollmentStatus->items)>0)
              @foreach($enrollmentStatus->items as $enrollmentStatusItem)

                /*
                |--------------------------------------------------------------------------
                | 
                |--------------------------------------------------------------------------
                */
                $('#status_item_{{$enrollmentStatusItem->id}}').change(function(e) {
                  e.preventDefault();
                  $.ajax({
                    url:'enrollment-status/update-item',
                    method:'POST',
                    data:{
                        id: "{{$enrollmentStatusItem->id}}",
                        checked: $(this).prop('checked'),
                        _token:"{{csrf_token()}}"
                    },
                    success:function(data){
                      console.log(data);
                      new PNotify({
                          title: data.title,
                          text: data.message,
                          type: data.error ? 'warning' : 'success'
                      });
                      if(data.off)
                      {
                        if(data.off.length > 0)
                        {
                          $.each(data.off, function( index, value ) {
                            if(value){
                              $('#status_item_'+value).bootstrapToggle('off');
                            }
                          });                        
                        }

                      }
                    },
                    error:function(data){
                      console.log(data);
                      new PNotify({
                          title: "Error",
                          text: 'Something Went Wrong, Please Try Again.',
                          type: 'warning'
                      });
                    }

                  });
                });
              @endforeach
            @endif
          @endforeach
        @endif
      @endif

    });
  </script>
@endsection
