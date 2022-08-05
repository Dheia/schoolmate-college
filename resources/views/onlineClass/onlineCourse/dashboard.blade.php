@extends('backpack::layout')

@section('header')

@endsection

@section('content')
  
  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>

  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div class="row">
        {{-- MAIN PANEL --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc">

          <div class="col-md-12 col-lg-12 ">
             <h2 class="oc-header-title m-t-0">My Courses<br></h2>
          </div> 
          
          <div class="col-md-12 col-lg-12 oc">
            <div class="" style="">
              <div class="" style="">
              {{--   <br><br><br> --}}
                 <!-- START SECTION CLASS POSTS -->
                @if($my_courses)
                  @if(count($my_courses)>0)
                    @php 
                      $course_index = 0; 
                    @endphp
                    @foreach($my_courses as $my_course)
                      @if($course_index % 3 ==0)
                        <div class="row">
                      @endif
                      @php
                        $course_index =  $course_index + 1;
                      @endphp
                      <div class="course_item">
                        <div class="col-xs-12 col-md-4 col-lg-4" >
                          
                            <div class="box shadow" style="border-radius: 5px; border-color: #eee;">
                              <div class="box-header course_image" style="border-radius: 5px 5px 0 0;  background-color:{{ $my_course->color }};">                           
                              </div>

                              <div class="box-body">
                                <div class="row" style="margin: 0">
                                  <div class="course_content course_content_m course_content_xs"> 
                                    <!-- Course Name -->
                                    <h3 class="course_content_title" style="">
                                      {{ $my_course->name }}
                                    </h3>
                                    <!-- Course Subject -->
                                    <h5 style="">
                                      {!! $my_course->subject->subject_title !!}
                                    </h5>
                                    <!-- Course Teacher -->
                                    <h5 style="">
                                      {{ $my_course->teacher ? $my_course->teacher->prefix . '. '. $my_course->teacher->firstname . ' ' . $my_course->teacher->lastname : '-'}}
                                    </h5>
                                    <!-- Course Duration -->
                                    @if($my_course->duration)
                                    <h5 style="">
                                      Duration: {{ $my_course->duration }}
                                    </h5>
                                    @endif
                                    

                                  </div>
                                </div>
                              </div>
                              <div class="box-footer" style="border-radius: 5px; padding-right: 20px !important; ">
                                <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" style="padding: 0">
                                  <a href="{{ url($crud->route) }}?course_code={{$my_course->code}}" style="color: #646464 !important;">
                                    <h5 style="color:#3c8dbc">View Course</h5>
                                  </a>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding: 0">
                                  <div class="dropdown">
                                    <a style="width: 20px; text-align: right;" href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><h5><i class="fa fa-ellipsis-v"></i></h5></a>
                                    <ul class="dropdown-menu pull-right" style="margin-right: -26px; margin-top: 35px;">
                                      <li>
                                        <a href="{{ asset('admin/online-class-module?course_code='.$my_course->code) }}">Manage Modules</a>
                                      </li>
                                      @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $my_course->teacher_id)
                                        <li>
                                          <a href="{{ asset($crud->route.'/'.$my_course->id.'/edit?course_code='.$my_course->code) }}">Edit Course</a>
                                        </li>
                                      @endif
                                      <li>
                                        <!-- <a href="{{ asset('admin/online-class-module?course_code='.$my_course->code) }}">Delete Course</a> -->
                                        <a href="javascript:void(0)" onclick="deleteCourse(this)" data-route="{{ url($crud->route.'/'.$my_course->id.'?course_code='.$my_course->code) }}" data-code="{{ $my_course->code }}" data-button-type="delete" title="Delete">Delete Course</a>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                      @if($course_index%3 == 0)
                        </div>
                      @endif
                    @endforeach
                    @if($course_index%3 == 0)
                      <div class="row">
                    @endif
                    <div class="course_item">
                      <div class="col-xs-12 col-md-4 col-lg-4 ">
                        <a href="{{ url($crud->route) }}/create">
                          <div class="oc-new-item">
                            <div class="box-body" style="padding: 60px 0px 85px 0px ">
                              <div class="text-center" style="">
                                <h4 style="font-size: 100px;">+</h4>
                                {{-- <i class="oc-icon oc-icon-adjustments oc-icon-2x"></i> --}}

                                <h5 class="text-center">
                                  Create New Course
                                </h5>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    @if($course_index%3 != 0)
                      </div>
                    @endif
                  @else
                    <div>
                      <div class="col-xs-12 col-md-3 col-lg-3">
                        <a   href="{{ url($crud->route) }}/create?teacher_id={{ Request::get('teacher_id') }}">
                          <div class="oc-new-item course_content_m course_content_xs" >
                            <div class="box-body">
                              <div class="text-center">
                                <h4 style="">+</h4>
                                <h4 class="text-center">
                                  Create New Course
                                </h4>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  @endif
                @else
                  <div>
                    <div class="col-xs-12 col-md-4 col-lg-4">
                      <a class ="new-course"  href="{{ url($crud->route) }}/create?teacher_id={{ Request::get('teacher_id') }}">
                        <div class="oc-new-item course_content_m course_content_xs">
                          <div class="box-body" style="">
                            <div class="text-center" style="">
                              <h4 style="">+</h4>
                              <h4 class="text-center">
                                Create New Course
                              </h4>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                @endif
                <!-- END SECTION CLASS POSTS -->
              </div>
            </div>
          </div>

        </div>


        {{-- LEFT PANEL --}}
        {{-- <div class="col-md-3 col-lg-3 col-xs-12 oc">
          <!-- USER ACCOUNT PANEL -->
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box shadow">
            <i class="oc-icon oc-icon-profile-male"></i>
            @include('backpack::inc.sidebar_user_panel')
          </div>
          <div class="col-md-12 col-lg-12 col-xs-12 oc oc-box shadow">
            <i class="oc-icon oc-icon-desktop"></i>
            <h5 class="oc-user text-center">Content</h5>
          </div>
        </div> --}}
      </div>
    </div>
  </div>




@endsection

@section('after_styles')
   <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
@endsection

@section('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active");

    function deleteCourse(button) {
      // ask for confirmation before deleting an item
      // e.preventDefault();
      var button = $(button);
      var route = button.attr('data-route');
      var meeting_id = button.attr('data-route');

      $.confirm({
        title: 'Delete',
        content: 'Are you sure you want to delete?',
        buttons: {
          cancel: function () {
              // $.alert('Canceled!');
          },
          delete: {
            text: 'Delete', // text for button
            btnClass: 'btn-danger', // class for the button
            isHidden: false, // initially not hidden
            isDisabled: false, // initially not disabled
            action: function(event){
              // $.alert('Confirmed!');
              var form = '<form action="' + route + '" method="POST">@csrf</form>';
              $(form).appendTo('body').submit();
            }
          }
             
        }
      });

    }
  </script>

@endsection
