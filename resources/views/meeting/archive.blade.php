@extends('backpack::layout')

@section('header')
@endsection

@section('content')

  <div class="row">
    <div class="col-md-12 col-lg-12 oc">
      <div class="row">

        <!-- RIGHT PANEL -->
        <div class="col-md-3 col-lg-3 col-xs-12 oc col-two">

          <!-- ARCHIVED MEETINGS -->
          <div class="col-md-12 col-lg-12 ">
            <div class="box br-0" style="min-height: 0">
              <div class="box-header">
                <a href="{{ url($crud->route) }}">{{-- <i class="fa fa-archive"></i> --}} <b>Active Meetings</b></a>
              </div>
            </div>
          </div>
          <!-- END OF ARCHIVED MEETINGS -->

          <!-- Create New Meeting Button -->
          <div class="col-md-12 col-lg-12 ">
            <a href="#" data-toggle="modal" data-target="#formModal">
              <div class="oc-new-item">
                <div class="box-body">
                  <div class="text-center" style="">
                    <h4 >+</h4>

                    <h5 class="text-center">
                      Create New Meeting
                    </h5>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <!-- End of Create New Meeting Button -->
         
        </div>
        <!-- END OF RIGHT PANEL -->

        <!-- MAIN PANEL -->
        <div class="col-md-9 col-lg-9 col-xs-12 oc col-one">

          <!-- Main Panel Title -->
          <div class="col-md-12 col-lg-12 ">
             <h2 class="oc-header-title p-t-0 m-t-0">Archive Meetings<br></h2>
          </div> 
          
          <!-- USER MEETINGS -->
          <div class="col-md-12 col-lg-12 oc">
            <!-- START SECTION CLASS POSTS -->
            @if(count($meetings)>0)
              @php 
                $meeting_index = 0; 
              @endphp
              @foreach($meetings as $meeting)
                @if($meeting_index % 3 ==0)
                  <div class="row">
                @endif
                @php
                  $meeting_index =  $meeting_index + 1;
                @endphp
                <div class="course_item">
                  <div class="col-xs-12 col-md-4 col-lg-4" >
                    
                      <div class="box shadow" style="border-radius: 5px; border-color: #eee;">
                        <div class="box-header course_image" style="border-radius: 5px 5px 0 0;  background-color:{{ $meeting->color }};">                           
                        </div>

                        <div class="box-body meeting-info">
                          <div class="row" style="margin: 0">
                            <div class="meeting_content" style="min-height: 175px;">
                              <h4 class="meeting_title" style="">
                                {{ $meeting->name }}
                              </h4>
                              <h6 class="meeting-desc">
                                {!! $meeting->description !!}
                              </h6>
                              <hr>
                              <!-- Meeting Employee -->
                              <h6 class="meeting-desc">
                                <b>By:</b> {!! $meeting->employee->fullname !!}
                              </h6>
                              <!-- Meeting Date -->
                              <h6 class="meeting-desc">
                                <strong> Date:</strong> {!! date("F j, Y", strtotime($meeting->date)) !!}
                              </h6>
                              <!-- Meeting Time -->
                              <h6 class="meeting-desc">
                                <strong>Time:</strong> {!! date("h:i A", strtotime($meeting->start_time)) !!} - {!! date("h:i A", strtotime($meeting->end_time)) !!}
                              </h6>
                              <br>

                              <!-- VIDEO CON STATUS -->
                              {{-- <h5 style="margin-top: 0px; margin-bottom: 0px; text-align: -webkit-center;">
                                @if($meeting->conference_status)
                                  <span class="badge label-success w-100" id="video_conference" style="margin-bottom: 5px;" >
                                    <i class="fa fa-video-camera"></i> 
                                      Meeting On-going
                                  </span>
                                  @if(backpack_auth()->user()->employee_id == $meeting->employee_id)
                                    <a href="javascript:void(0)" class="btn btn-info btn-block btn-xs startVideoConferencing" code="{{ $meeting->code }}">
                                      Start Video Conferencing  
                                    </a>
                                    <form action="{{url($crud->route) }}/video_conference" method="GET" target="_blank" id="form{{ $meeting->code }}">
                                      @csrf
                                      <input type="hidden" name="_method" value="GET">

                                      <input type="hidden" name="meeting_id" value="{{$meeting->id}}">
                                      <input type="hidden" name="code" value="{{$meeting->code}}">
                                    </form>
                                  @else
                                    <a href="javascript:void(0)" class="btn btn-default btn-block btn-xs joinVideoConferencing" code="{{ $meeting->code }}">Join Video Conference</a>
                                    <form action="{{url($crud->route . '/video/'.$meeting->code) }}" method="POST" target="_blank" id="join-form{{ $meeting->code }}">
                                        @csrf
                                        <input type="hidden" name="_method" value="GET">

                                        <input type="hidden" name="meeting_id" value="{{$meeting->id}}">
                                        <input type="hidden" name="code" value="{{$meeting->code}}">
                                    </form>
                                  @endif
                                @else
                                  <span class="badge label-default w-100" id="video_conference" style="margin-bottom: 5px;">
                                    <i class="fa fa-video-camera"></i> 
                                    No On-going Meeting
                                  </span>
                                  @if(backpack_auth()->user()->employee_id == $meeting->employee_id)
                                    <a href="javascript:void(0)" class="btn btn-info btn-block btn-xs startVideoConferencing" code="{{ $meeting->code }}">
                                        Start Video Conferencing  
                                    </a>
                                    <form action="{{url($crud->route) }}/video_conference" method="GET" target="_blank" id="form{{ $meeting->code }}">
                                      @csrf
                                      <input type="hidden" name="_method" value="GET">

                                      <input type="hidden" name="meeting_id" value="{{$meeting->id}}">
                                      <input type="hidden" name="code" value="{{$meeting->code}}">
                                    </form>
                                  @endif
                                @endif
                              </h5> --}}
                              <!-- END OF VIDEO CON STATUS -->

                            </div>
                          </div>
                        </div>

                        <!-- BOX FOOTER -->
                        <div class="box-footer" style="border-radius: 5px; padding-right: 20px !important; ">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0">
                            <div class="dropdown">
                              <a style="width: 20px; text-align: right;" href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><h5><i class="fa fa-ellipsis-v"></i></h5></a>
                              <ul class="dropdown-menu pull-right" style="margin-right: -26px; margin-top: 35px;">

                                @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $meeting->employee_id)
                                  <li>
                                    <a href="javascript:void(0)" class="archivedMeeting" id="archivedMeeting" code="{{ $meeting->code }}">
                                      Restore Meeting
                                    </a>
                                    <form action="{{ url($crud->route . '/' . $meeting->code . '/restore-archive' )}}" method="POST" id="archived-form{{ $meeting->code }}">
                                      @csrf
                                      <input type="hidden" name="code" value="{{$meeting->code}}">
                                    </form>
                                  </li>
                                @endif

                                @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $meeting->employee_id)
                                  <li>
                                    <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/'.$meeting->id) }}" data-button-type="delete" title="Delete">
                                      Delete Meeting
                                    </a>
                                  </li>
                                @endif

                                
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
                @if($meeting_index%3 == 0)
                  </div>
                @endif
              @endforeach
              
              @if($meeting_index%3 != 0)
                </div>
              @endif

              <div class="text-center">
                {{ $meetings->links() }}
              </div>
            @else
              <!-- No Post Found -->
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-t-20 p-b-20 text-center">
                <i class="fa fa-video-slash" style="margin: auto; font-size: 50px;"></i>
                <h3 class="text-center">No Meeting.</h3>
              </div>
              <!-- End Of No Post Found -->
            @endif
            <!-- END SECTION CLASS POSTS -->
          </div>
          <!-- END OF USER MEETINGS -->

        </div>
        <!-- END OF MAIN PANEL -->

      </div>
    </div>
  </div>

  <!-- START CREATE FORM MODAL -->
  <div id="formModal" class="modal fade" role="dialog"  style="border-radius: 5px;">
    <div class="modal-dialog">

      <!-- FORM -->
      <form style="border-radius: 5px;" method="post" action="{{ url($crud->route) }}" @if ($crud->hasUploadFields('create')) enctype="multipart/form-data" @endif >
        {!! csrf_field() !!}
        <div class="box" style="border-radius: 5px; border: none; box-shadow: none !important;">
          <div class="box-body" style="padding: 0px; border: none; box-shadow: none !important;">
            
            <div class="col-md-12" style="border-radius: 5px; border: none;">

              <div class="row display-flex-wrap" style="padding-bottom: 0px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);" >
                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                @if(view()->exists('vendor.backpack.crud.form_content'))
                  @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                @else
                  @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                @endif

              </div><!-- /.box-body -->
              <div class="">
                 
              </div><!-- /.box-footer-->

            </div><!-- /.box -->
            
          </div>
          <div class="box-footer" style="padding: 0px 10px; border-radius: 5px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
            <div class="pull-right" style="padding-left: 20px;">
                 <button style="margin-right: 10px;" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-primary pull-right" >Post</button>
              </div>
          </div>
        </div>
      </form>
      <!-- END OF FORM -->

    </div>
  </div>
  <!-- END CREATE FORM MODAL -->

@endsection

@push('after_styles')
   <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
@endpush

@push('after_scripts')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script type="text/javascript">
    $('.archivedMeeting').click(function () {
        var meetingCode = $(this).attr('code');
        $('#archived-form' + meetingCode).submit();
      });

    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    })
  </script>

  <!-- POP UP CREATE FORM MODAL IF THERE'S ERROR -->
  @if($errors->any())
    <script type="text/javascript">
      $('#formModal').modal('show');
    </script>
  @endif
  <!-- POP UP CREATE FORM MODAL IF THERE'S ERROR -->

  <script>

    if (typeof deleteEntry != 'function') {
      $("[data-button-type=delete]").unbind('click');

      function deleteEntry(button) {
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
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
  </script>

@endpush
