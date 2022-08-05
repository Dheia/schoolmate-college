@extends('backpack::layout_student')

@section('header')

@endsection

@push('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
@endpush

@section('content')

  <div class="row">
    <div class="col-md-12 col-lg-12 oc pt-0">
      <div class="row">
        {{-- MAIN PANEL --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc pt-0">

          @include('student/online-class/partials/navbar')

          <div class="row">
            <!-- MAIN BODY -->
            <div class="col-md-12 col-lg-12 col-xs-12">
              <div class="box shadow">
                <div class="box-body">
                  <!-- START DESCRIPTION -->
                  <div class="col-md-8 col-lg-8 oc-box">
                    <div class="col-md-12 col-lg-12 oc">
                      <h3> {{ $class->name }} </h3>
                      <h5> {{ $class->section ? $class->section->name_level : 'Unknow Section.' }} </h5>
                      @if(isset($class->subject))
                      <h5> <strong>Subject Description:</strong>  {{ $class->subject ? $class->subject->subject_description : 'Unknown Subject' }} </h5>
                      @endif
                      <br>
                      {{-- COURSE DESCRIPTION --}}
                      <h4 class="text-uppercase"> About this Course </h4>
                      {!! $class->course->description !!}
                      {{-- COURSE REQUIREMENTS --}}
                      @if ( $class->course->requirements )
                        <br>
                        <h4 class="text-uppercase"> Requirements </h4>
                        {!! $class->course->requirements !!}
                      @endif
                    </div>
                  </div>
                  {{-- END DESCRIPTION --}}
                  <!-- SUMMARY -->
                  <div class="col-md-4 col-lg-4 oc-box">
                    <div class="col-md-12 col-lg-12 oc-box" style="border: 1px blue solid;">

                      <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 20px; text-align: left;">
                          <h5 class="mb-0"><strong>Course Code: </strong></h5>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px !important; text-align: left;">
                          <h5 class="mb-0"> {{ $class->course->code }} </h5>
                        </div>
                      </div>

                      @if($class->course->duration)
                      <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 20px; text-align: left;">
                          <h5><strong>Duration: </strong></h5>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px !important; text-align: left;">
                          <h5> {{ $class->course->duration }} </h5>
                        </div>
                      </div>
                      @endif
                     
                    </div>
                  </div>
                  <!--// SUMMARY -->


                </div>
              </div>

              <!-- START CONTENT/MODULES SECTION -->
              <div class="box" style="border-radius: 15px;">
                <div class="box-body text-justify" style="padding: 25px 50px;">
                  <h4 class="text-uppercase">Content</h4>
                  @if($class->course->modules)
                    @if(count($class->course->modules)>0)
                    <!--Accordion wrapper-->
                    <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                      @foreach($class->course->modules as $key => $module)
                          <!-- Accordion card -->
                          <div class="card" @if($loop->last) style="padding-bottom: 20px;" @endif>
                            <!-- Card header -->
                            <div class="card-header row" role="tab" id="module-header-{{$key}}" style="margin: 0px; color: #000; background-color: #3c8dbc; color:white; border: 1px solid #ccc; margin-bottom: 5px; border-radius: 5px;">
                              <div class="col-md-10">
                                <h4><span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                                    <p class="" style="margin: 0; text-align: left"> {{ $module->title }} </p></h4>
                              </div>
                              <div class="col-md-2 pull-right">
                                <a class="pull-right" data-toggle="collapse" data-parent="#accordionEx" href="#module-body-{{$key}}" aria-expanded="true" aria-controls="module-body-{{$key}}" style="color: white; padding: 0 10px;"><h4><i class="fa fa-caret-down"></i></h4>
                                </a>
                                <a class="pull-right" href="{{ url('student/online-class/' . $class->code . '/course/' . $module->id) }}" style="color: white; padding-left: 5px; padding: 0 10px;">
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
                                      <a href="{{ url('student/online-class-topic/'.$class->code.'/'.$module->id.'/'.$topic->id) }}">{{ $topic->title }}</a>
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
                    @else
                      <div class="box" style="border-radius: 10px;">
                        <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                            <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                              No available module
                            </h4>
                        </div>
                      </div>
                    @endif
                  @endif
                </div>
              </div>
              <!-- END CONTENT/MODULES SECTION -->
            </div>
          </div>

        </div>

        {{-- LEFT PANEL --}}
        <div class="col-md-3 col-lg-3 col-xs-12 oc pt-0">
          {{-- USER ACCOUNT PANEL --}}
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box shadow">
            @include('backpack::inc.sidebar_student_panel')
          </div>
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box shadow">
              <h5 class="oc-user text-center">Content</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active");
  </script>
@endsection

