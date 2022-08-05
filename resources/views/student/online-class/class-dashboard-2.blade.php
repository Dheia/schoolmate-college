@extends('backpack::layout_student')

@push('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
  <link rel="stylesheet" href="{{ asset('js/calendar/style.css')}}">
  <link rel="stylesheet" href="{{ asset('js/calendar/theme.css')}}">
  <style>
    .row-text{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .column-text-right{
      display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-end;
    }
    .column-text-left{
    display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-start;
    }
    .calendar-wrapper {
          padding: 15px;
    
      }
      @media only screen and (min-width: 768px) {
        .content-wrapper{
      border-top-left-radius: 50px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    .main-footer{
      border-bottom-left-radius: 50px;
    
    }
    .dot-assignment{
      background-color: rgb(60, 141, 188);
      border-radius: 15px;
      position: absolute;
      margin-right: 10px;
      padding-right: 10px;
      width: 80px;
    }
    .row-teacher{
      margin-top:20px;
      margin-left:20px;
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .column-teacher-pic{
        display: flex;
        flex-direction: column;
      }
      .column-teacher-name{
      display: flex;
      flex-direction: column;
      width: 60%;
    }
    .profile-pic{
      max-width: 50px;
      width: 100%;
      border-radius: 100%;
      overflow: hidden;
      padding: 0px;
      margin: 0px;
      border: 1.5px #d2d6de solid;
      border-radius: 100%;
    }
    .profile-pic:hover{
      border: 1.5px #3C8DBC solid;
    }
    .box-body:hover{
          margin-left:0px;
      
    }

    .btn_assignment {
      margin-top: 5px;
      height: 40px;
      width: 40px;
      line-height: 40px;
      font-size: 2em;
      font-weight: bold;
      border-radius: 50%;
      background-color: #2683B9;
      color: white;
      text-align: center;
      border: none;
      cursor: pointer;
      box-shadow: 0 3px 4px -1px rgba(114, 114, 114, 0.2), 0 6px 10px 0 rgba(119, 119, 119, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12);
    }
    .pagination>li:last-child>a, .pagination>li:last-child>span {
      margin-top: 5px;
      height: 50px;
      width: 100px;
      line-height: 40px;
      font-size: 1.5em;
      font-weight: bold;
      border-radius: 10px;
      background-color: #2683B9;
      color: white;
      text-align: center;
      border: none;
      box-shadow: 0 3px 4px -1px rgba(114, 114, 114, 0.2), 0 6px 10px 0 rgba(119, 119, 119, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12);
    }
    .pagination>li:first-child>a, .pagination>li:first-child>span {
      margin-top: 5px;
      height: 50px;
      width: 100px;
      line-height: 40px;
      font-size: 1.5em;
      font-weight: bold;
      border-radius: 10px;
      background-color: #2683B9;
      color: white;
      text-align: center;
      border: none;
      margin-right: 20px;
      box-shadow: 0 3px 4px -1px rgba(114, 114, 114, 0.2), 0 6px 10px 0 rgba(119, 119, 119, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12);
    }
    
  </style>
@endpush

@section('content')
<body style="background: #3c8dbc;">
  <div class="row">
    <div class="col-md-12 col-lg-12 oc pt-0">
      <div class="row">
        {{-- MAIN PANEL START --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc pt-0">
          <div class="col-md-12 col-lg-12">
            @include('student/online-class/partials/navbar')
          </div>
  
          <div class="col-md-12 col-lg-12">
             <h2 class="oc-header-title">My Classes</h2>
          </div> 
          <div class="col-md-12 col-lg-12 oc">
            <student-search-class :term_types="'{{ json_encode($term_types) }}'"></student-search-class>
          </div>

        </div>
          <div class="col-md-3 col-lg-3 col-xs-12">
            <div class="col-md-12 col-lg-12 p-l-20">
                  <h3 class="oc-header-title" style="font-size:17px; color:#2683B9;"><b>Calendar</b> </h3>
              
                  {{-- CALENDAR --}}
                  <div class="info-box calendar-wrapper">
                  <div class="calendar-container"></div>
                </div>
            </div> 
           

            <div class="col-md-12 col-lg-12 p-l-20">
              <div class="row-text">
                <div class="column-text-left" >
                   <h3 class="oc-header-title " style="font-size:19px; color:#2683B9;"><b>Assignments</b> </h3>
                </div>
                  <div class="column-text-right" style="align-items: right">
                    <a href="{{ url('student/online-class-assignments') }}">
                       <h3 class="oc-header-title" style="font-size:14px; color:#a5a5a5;"><b>View all</b> </h3>
                    </a>
                  </div>
              </div>
            </div>

            <div class="col-md-12 col-lg-12 oc">
              <div class="" style="">
                <div class="" style="">
                  @if($assignments)
                    @if(count($assignments)>0)
                      @php
                        $assignment_count = 0;
                      @endphp
                      @foreach($assignments as $assignment)

                        @if($assignment_count%2 == 0)
                        @endif
                          <div class="row">
                          <div class="col-xs-12 col-md-12 col-lg-12">
                            <span class="dot-assignment" style="height: 87%; background-color:#ff7f43;"></span>
                              <div class="box shadow" style="margin-left:10px;">
                                
                              
                                <div class="box-body" style="padding: 15px !important;">
                                  <div class="row">
                                    <div class="">
                                      
                                      <!-- Class Name -->
                                      <a href="{{ url('student/online-post')}}?class_code={{$assignment->class->code }}">
                                        @if($assignment->title)
                                          <h4 class="class-header" style="padding-left: 0px;">
                                            {{ $assignment->title ? $assignment->title : '-' }}
                                          </h4>
                                          @endif
                                      </a>

                                      <!-- Class Teacher -->
                                      <h6 class = "class-desc p-0" style="margin-top: 3px;">
                                        Status : {{  count($submittedAssignments)>0 ? $submittedAssignments->where('assignment_id', $assignment->id)->pluck('status')->first() : 'Not Yet Submitted' }}
                                      </h6>

                                      <!-- Grade and Section -->
                                      @if($assignment->due_date)
                                        <h6 class = "class-desc p-0" style="margin-top: 3px;">
                                          Due Date : {{ date('F j, Y', strtotime($assignment->due_date)) }}
                                        </h6>
                                      @endif
                                      <div class="row-teacher">
                                          <div class="column-teacher-pic">
                                            <img src="http://127.0.0.1:8000/images/headshot-default.png" alt="..." class="profile-pic">
                                          </div>
                                          <div class="column-teacher-name">
                                            <!-- Teacher Name -->
                                            <h6 class = "class-desc p-0 " style="margin-left: 4px; margin-top:5px;">
                                              <b>
                                              {{ $assignment->class->teacher_fullname ? $assignment->class->teacher_fullname  : 'Unknown Teacher' }}
                                            </b>
                                            <h6 class = "class-desc p-0 " style="margin-left: 4px;">
                                              Teacher
                                            </h6>
                                            </h6>
                                          </div>
                                          <div class="column-teacher-pic">
                                            <a href="{{ url('student/online-class-assignments?id=' . $assignment->id) }}">
                                            <div class="btn_assignment">+</div>
                                            </a>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>

                          </div>
                        @if($assignment_count%2 != 0)   
                        @elseif($loop->last)
                          </div>
                        @endif
                        
                        @php
                        if($assignment_count == 2){
                          break;
                        }else{
                          $assignment_count =  $assignment_count + 1;
                        }
                        @endphp
                      @endforeach
                    @else
                      <div>
                        <div class="col-xs-12 col-md-12 col-lg-12">
                          <div class="box" style="border-radius: 5px;">
                            <div class="box-body" style="padding: 10px; height: 120px;">
                              <div class="text-center" style="margin-top: 40px !important;">
                                <h3 class="text-center">
                                  No Assignment
                                </h3>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @else
                    <div>
                      <div class="col-xs-12 col-md-12 col-lg-12">
                        <div class="box" style="border-radius: 5px;">
                          <div class="box-body" style="padding: 10px; height: 120px;">
                            <div class="text-center" style="margin-top: 40px !important;">
                              <h3 class="text-center">
                                No Assignment
                              </h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                  <!-- END SECTION CLASS POSTS -->
                </div>
              </div>

        </div> 
    </div>
        {{-- MAIN PANEL END --}}

      </div>
    </div>
  </div>
</body>
@endsection

@section('after_scripts')
<script src="{{ asset('js/onlineclass/studentSearchClass.js') }}"></script>
<script src="{{ asset('js/calendar/calendar.js')}}"></script>
  <script>
      $('.calendar-container').calendar({
        date:new Date()// today
    });


  </script>
<script>
  document.getElementById("nav-classes").classList.add("active");

  // function getMeetingInfo(meetingId) {
   
  //   var meetingId = meetingId;

  //     $.get('/admin/teacher-online-class/video_conference_status',{meetingId: meetingId}, function( data ) {
  //       if(data.video_conference_info.original.returncode == "SUCCESS"){
  //         var info = data.video_conference_info.original;
          
  //         if(info.returncode == "SUCCESS"){
  //           $('#video_conference').removeClass('btn-default');
  //           $('#video_conference').addClass('btn-success');
  //           $('#video_conference').html('Video Conference On-Going');
  //         }
  //       }
        
  //     });
  // }

</script>

@endsection
