@extends('backpack::layout')

@section('header')
	<!-- <section class="content-header">
	  <h1>
      <span class="text-capitalize">
        {{ $class->name ?? 'Unknown' }}
        {{-- {!! $crud->getHeading() ?? $crud->entity_name_plural !!} --}}
      </span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
      <br>
      <small>{{ $teacher->fullname ?? 'Unknown' }} | {{ $class->subject->subject_title ?? 'Unknown' }} | {{ $class->section->name_level ?? 'Unknown' }}</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}?teacher_id={{ request()->get('teacher_id') }}&class_code={{ request()->get('class_code') }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section> -->
@endsection

@section('content')
 
  <style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
    a { word-wrap: break-word; }
  </style>

  
  <div class="row p-l-20 p-r-20">
    <h3>Kiosk Settings</h3>
    <div class="col-md-12 col-lg-12" style="border-radius: 5px; padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px;">

        <div class="box" style="border-radius: 5px;">
          <div class="box-header with-border" style="padding: 50px 30px !important;">

              <div class="col-md-12 col-lg-6">
                @if($settings)
                  @if(count($settings)>0)
                    @foreach($settings as $index => $setting)
                      {{-- START  {{$setting->name}} --}}
                      <div class="row">
                        <h5 class="col-md-7 col-lg-7 col-7 col-xs-7 col-sm-7" style="padding-top: 5px; margin-top: 5px; margin-bottom: 5px;">{{$setting->name}}</h5>
                        <div class="col-md-1 col-lg-1 col-1 col-xs-1 col-sm-1" style="margin-top: 5px; margin-bottom: 5px;">
                          <input id="{{$setting->key}}" class="pull-right" type="checkbox" data-toggle="toggle" data-style="ios" {{$setting->active ? 'checked' : ''}}>
                        </div>
                      </div>
                      {{-- END  {{$setting->name}} --}}
                    @endforeach
                  @endif
                @endif
              </div>

              <div class="col-md-12 col-lg-6">
                @if($announcement)
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <h4>Announcement</h4>
                    <form id="annoucenment-form" method="POST" action="kiosk-setting/announcement/update" enctype="multipart/form-data">
                      @csrf
                      {!! csrf_field() !!}
                      <div class="form-group">
                        <textarea disabled style="min-height: 110px;" id="announcement-input" name="announcement-input" class="form-control" rows="3">{{ $announcement->description }}</textarea>
                        <br>
                        <button id="announcementEdit" onclick="edit()" type="button" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                        <button style="display: none;" id="announcementSave" type="submit" class="btn btn-primary">Save</button>
                        <button style="display: none;" id="announcementCancel" onclick="cancelEdit()" type="button" class="btn btn-default">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
                @endif

                @if($termsConditions)
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <h4>Terms, Conditions and Data Privacy</h4>
                    <div id="termsConditions-display" class="p-l-10 p-r-10" style="border: 1px solid #ccc; border-radius: 5px; background-color: #eee; opacity: 1; min-height: 50px;">
                      {!! str_limit($termsConditions->description, 700) !!}
                    </div>
                    <br>
                    <form id="termsConditions-form" method="POST" action="kiosk-setting/terms-and-condition/update" enctype="multipart/form-data">
                      @csrf
                      {!! csrf_field() !!}
                      <div class="form-group" style="display: none;" id="termsConditions-div">
                        <textarea style="min-height: 110px; display: none;" id="termsConditions-input" name="termsConditions-input" class="form-control" rows="3">{{ $termsConditions->description }}</textarea>
                        <br>
                      </div>
                      <button id="termsConditionsEdit" onclick="termsEdit()" type="button" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                      <button style="display: none;" id="termsConditionsSave" type="submit" class="btn btn-primary">Save</button>
                      <button style="display: none;" id="termsConditionsCancel" onclick="termsConditionsCancelEdit()" type="button" class="btn btn-default">Cancel</button>
                    </form>
                  </div>
                </div>
                @endif
                
                @if($additionalPage)
                  <div class="row">
                    <div class="col-md-12 col-lg-12">
                      <h4>Additional Page</h4>
                      <div id="page-description-display" class="p-l-10 p-r-10" style="border: 1px solid #ccc; border-radius: 5px; background-color: #eee; opacity: 1;">
                        {!! $additionalPage->description !!}
                      </div>
                      <br>
                      <form id="additionalPage-form" method="POST" action="kiosk-setting/additional-page/update" enctype="multipart/form-data">
                        @csrf
                        {!! csrf_field() !!}
                        <div class="form-group" style="display: none;" id="additionalPage-div">
                          <textarea style="min-height: 110px; display: none;" id="additionalPage-input" name="additionalPage-input" class="form-control" rows="3">{{ $additionalPage->description }}</textarea>
                          <br>
                        </div>
                        <button id="additionalPageEdit" onclick="pageEdit()" type="button" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                        <button style="display: none;" id="additionalPageSave" type="submit" class="btn btn-primary">Save</button>
                        <button style="display: none;" id="additionalPageCancel" onclick="additionalPageCancelEdit()" type="button" class="btn btn-default">Cancel</button>
                      </form>
                    </div>
                  </div>
                @endif

              </div>

          </div>
        </div>

    </div>


  </div>


  
@endsection

@section('after_scripts')
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script src="{{ asset('vendor/backpack/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('vendor/backpack/ckeditor/adapters/jquery.js') }}"></script>
  @if($additionalPage)
  <script>
    jQuery(document).ready(function($) {
        $('#additionalPage-input').ckeditor({
            "filebrowserBrowseUrl": "{{ url(config('backpack.base.route_prefix').'/elfinder/ckeditor') }}",
            
            "extraPlugins" : '{{ isset($field['extra_plugins']) ? implode(',', $field['extra_plugins']) : 'oembed,widget,justify,font' }}'
            @if (isset($field['options']) && count($field['options']))
                {!! ', '.trim(json_encode($field['options']), "{}") !!}
            @endif
        });
        // CKEDITOR.config.readOnly = true;

    });
  </script>
  @endif

  @if($termsConditions)
  <script>
    jQuery(document).ready(function($) {
        $('#termsConditions-input').ckeditor({
            "filebrowserBrowseUrl": "{{ url(config('backpack.base.route_prefix').'/elfinder/ckeditor') }}",
            
            "extraPlugins" : '{{ isset($field['extra_plugins']) ? implode(',', $field['extra_plugins']) : 'oembed,widget,justify,font' }}'
            @if (isset($field['options']) && count($field['options']))
                {!! ', '.trim(json_encode($field['options']), "{}") !!}
            @endif
        });
        // CKEDITOR.config.readOnly = true;

    });
  </script>
  @endif

  <script>
    @if($announcement)
      function edit()
      {
        $('#announcementEdit').hide();
        $('#announcementSave').show();
        $('#announcementCancel').show();
        $('#announcement-input').removeAttr("disabled");
      }
      function cancelEdit()
      {
        $('#announcementEdit').show();
        $('#announcementSave').hide();
        $('#announcementCancel').hide();
        $('#announcement-input').attr('disabled', true);
      }
    @endif

    @if($additionalPage)
      function pageEdit()
      {
        $('#additionalPageEdit').hide();
        $('#additionalPageSave').show();
        $('#additionalPageCancel').show();
        $('#additionalPage-input').removeAttr("disabled");
        $('#additionalPage-div').show();
        $('#page-description-display').hide();
      }
      function additionalPageCancelEdit()
      {
        $('#additionalPageEdit').show();
        $('#additionalPageSave').hide();
        $('#additionalPageCancel').hide();
        $('#additionalPage-input').attr('disabled', true);
        $('#additionalPage-div').hide();
        $('#page-description-display').show();

      }
    @endif

    @if($termsConditions)
      function termsEdit()
      {
        $('#termsConditionsEdit').hide();
        $('#termsConditionsSave').show();
        $('#termsConditionsCancel').show();
        $('#termsConditions-input').removeAttr("disabled");
        $('#termsConditions-div').show();
        $('#termsConditions-display').hide();
      }
      function termsConditionsCancelEdit()
      {
        $('#termsConditionsEdit').show();
        $('#termsConditionsSave').hide();
        $('#termsConditionsCancel').hide();
        $('#termsConditions-input').attr('disabled', true);
        $('#termsConditions-div').hide();
        $('#termsConditions-display').show();

      }
    @endif
    
    $(function() {
      @if($settings)
        @if(count($settings)>0)
          @foreach($settings as $index => $setting)
            /*
            |--------------------------------------------------------------------------
            | {{$setting->description}}
            |--------------------------------------------------------------------------
            */
            $('#{{$setting->key}}').change(function(e) {
              e.preventDefault();
              $.ajax({
                url:'kiosk-setting/kiosk-settings/update',
                method:'POST',
                data:{
                    key: "{{$setting->key}}",
                    checked: $(this).prop('checked'),
                    _token:"{{csrf_token()}}"
                },
                success:function(data){
                  new PNotify({
                      title: data.title,
                      text: data.message,
                      type: data.error ? 'warning' : 'success'
                  });

                },
                error:function(data){
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
      @endif

    });
  </script>
@endsection
