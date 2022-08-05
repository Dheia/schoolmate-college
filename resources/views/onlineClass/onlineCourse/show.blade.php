@extends('backpack::layout')

@section('header')

@endsection

@section('content')
  <div class="row">
    <div class="col-md-12 col-lg-12 oc">
      <div class="row">
        

        {{-- MAIN PANEL --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc">
          
          @include('onlineClass.partials.navbar')
          

          <div class="row">
            <!-- MAIN BODY -->
            <div class="col-md-12 col-lg-12 col-xs-12">
              <div class="box">
                <div class="box-body">
                  <!-- DESCRIPTION -->
                  <div class="col-md-8 col-lg-8 oc-box">
                    <div class="col-md-12 col-lg-12 oc">
                      <h3> {{ $selected_course->name }} </h3>
                      <h5> {{ $selected_course->level->year }} </h5>
                      @if(isset($selected_course->subject->subject_description))
                      <h5> <strong>Subject Description:</strong> {!! $selected_course->subject->subject_description !!} </h5>
                      @endif
                      <br>
                      {{-- COURSE DESCRIPTION --}}
                      <h4 class="text-uppercase"> About this Course </h4>
                      {!! $selected_course->description !!}
                      {{-- COURSE REQUIREMENTS --}}
                      @if ( $selected_course->requirements )
                        <br>
                        <h4 class="text-uppercase"> Requirements </h4>
                        {!! $selected_course->requirements !!}
                      @endif
                    </div>
                    
                  </div>
                  <!-- SUMMARY -->
                  <div class="col-md-4 col-lg-4 oc-box">
                    <div class="col-md-12 col-lg-12 oc-box" style="border: 1px #3c8dbc solid;">

                      <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 20px; text-align: left;">
                          <h5 class="mb-0"><strong>Course Code: </strong></h5>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px !important; text-align: left;">
                          <h5 class="mb-0"> {{ $selected_course->code }} </h5>
                        </div>
                      </div>

                      @if($selected_course->duration)
                      <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 20px; text-align: left;">
                          <h5><strong>Duration: </strong></h5>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px !important; text-align: left;">
                          <h5> {{ $selected_course->duration }} </h5>
                        </div>
                      </div>
                      @endif
                      
                      @if(backpack_auth()->user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $selected_course->teacher_id)
                        <a href="{{ url($crud->route.'/'.$selected_course->id.'/edit?course_code='.$selected_course->code) }}" style="" type="submit" class="btn btn-success pull-right w-100" >
                          </span> Edit Course Info
                        </a>
                      @endif
                    </div>
                  </div>

                </div>
              </div>

              <div class="box">
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 oc">
                    {{-- CONTENT/MODULES --}}
                    @if($selected_course->modules)
                      @if(count($selected_course->modules)>0)
                        <br>
                        <h4 class="text-uppercase">Content</h4>
                        <!--Accordion wrapper-->
                        <div  class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                          @foreach($selected_course->modules as $key => $module)
                              <!-- Accordion card -->
                              <div class="card" @if($loop->last) style="padding-bottom: 20px;" @endif>

                                <!-- Card header -->
                                <div class="card-header row" role="tab" id="module-header-{{$key}}" style="margin: 0px; color: #000; background-color: #3c8dbc; color:white; border: 1px solid #ccc; margin-bottom: 5px; border-radius: 5px;">
                                  <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10">
                                    <h4><span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                                        <p class="" style="margin: 0; text-align: left"> {{ $module->title }} </p></h4>
                                  </div>
                                  <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 pull-right">
                                    <a class="pull-right" data-toggle="collapse" data-parent="#accordionEx" href="#module-body-{{$key}}" aria-expanded="true" aria-controls="module-body-{{$key}}" style="color: white; padding: 0 10px;"><h5><i class="fa fa-chevron-down"></i></h5>
                                    </a>
                                    <a class="pull-right" href="{{ url('admin/online-class-topic?course_code='.$selected_course->code.'&module_id='.$module->id) }}" style="color: white; padding-left: 5px; padding: 0 10px;">
                                      <h4><i class="fa fa-th-list"></i></h4>
                                    </a>
                                  </div>
                                   
                                </div>
                              
                                <!-- Card body -->
                                <div id="module-body-{{$key}}" class="collapse" role="tabpanel" aria-labelledby="module-header-{{$key}}"
                                  data-parent="#accordionEx">
                                  <div class="card-body modules" style="padding: 5px 50px; border: 0px ; background-color:#f7f7f7;  border-radius: 5px; margin-bottom: 5px;">
                                    @if($module->topics)
                                      @if(count($module->topics)>0)
                                        @foreach($module->topics as $topic)
                                          @if(Request::get('class_code'))
                                            @php
                                              $topicURL = 'admin/online-class-topic?class_code='.request()->class_code.'&course_code='.$selected_course->code.'&module_id='.$module->id.'&topic_id='.$topic->id;
                                            @endphp
                                          @else
                                            @php
                                              $topicURL = 'admin/online-class-topic?course_code='.$selected_course->code.'&module_id='.$module->id.'&topic_id='.$topic->id;
                                            @endphp
                                          @endif
                                          <a href="{{ asset($topicURL) }}">{{ $topic->title }}</a>
                                          <br>
                                        @endforeach
                                      @else
                                        <p style="color: #000;">No Topic Available</p>
                                      @endif
                                    @else
                                      <p style="color: #000;">No Topic Available</p>
                                    @endif
                                  </div>
                                </div>

                              </div>
                              <!-- Accordion card -->
                          @endforeach
                        </div>
                        <!-- Accordion wrapper -->
                      @endif
                    @endif
                    @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $selected_course->teacher_id)
                      <h5 class="mb-0 btn btn-lg btn-block new-course" style="margin: 0px; margin-bottom: 5px; ">
                        <a href="{{url('admin/online-class-module/create?course_code=' . request()->course_code)}}" style="padding: 0 50px;" >+ Add Modules</a> 
                      </h5>
                    @endif
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        {{-- LEFT PANEL --}}
        <div class="col-md-3 col-lg-3 col-xs-12 oc">
          {{-- USER ACCOUNT PANEL --}}
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box">
            @include('backpack::inc.sidebar_user_panel')
          </div>
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box">
              <h5 class="oc-user text-center">Content</h5>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
  <style type="text/css">
    .with-border {
      border: 1px solid #6c757d !important;
    }
  </style>
@endsection

@section('after_scripts')
	<script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active"); 
  </script>
@endsection


