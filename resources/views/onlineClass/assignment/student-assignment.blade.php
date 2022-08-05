@extends('backpack::layout')

@section('header')
@endsection

@section('content')  
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">

  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>

  <div class="row p-l-20 p-r-20">

    <!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

    <!-- START OF MAIN FEED -->  
    <div class="col-md-8 col-lg-8 col-one">
      <div class="">
        <div class="info-box add-post shadow">
          <div class="box-body">
            <!-- <div class="row"> -->
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px;">
                <a class="thumbnail form-control" >
                  @if($user->employee->photo)
                    @if(file_exists($user->employee->photo))
                    <img src="{{ asset($user->employee->photo) }}" alt="...">
                    @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                    @endif
                  @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                  @endif
                </a>
              </div>
              <div class="form-group col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10 p-t-10" style="margin: 0px;">
                  <input style="border: none; margin-top: 5px;" 
                    placeholder="Add an assignment..." 
                    class="form-control" data-toggle="modal" data-target="#formModal">
              </div>
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px; padding: 0px;">
                <button id="btnAddAssignment" type="submit" class="btn btn-primary btn-circle" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 5px;" title="Create Assignment">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
          <!-- </div> -->
        </div>
      </div>

      <!-- START ASSIGNMENT INFORMATION -->
      <div class="">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="col-md-12 col-lg-12"> 
              {{ $assignment->title }}
              {!! $assignment->instructions !!}
              <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($assignment->due_date)) }}</p>
            </div>
          </div>
        </div>
      </div>
      <!-- END ASSIGNMENT INFORMATION -->

      <!-- START STUDENT'S SUBMIITED ASSIGNMENT -->
      @if($submittedAssignments)
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">

            <!-- START STUDENT INFORMATION -->
            <div class="col-md-12 col-lg-12">
              <label class="pt-5">Submitted by:</label>
              <table class="table table-bordered mb-0">
                  <tbody>
                      <tr>
                          <td><b><small>Student ID:</small></b></td>
                          <td id="studentID"> {{ $student->studentnumber }} </td>
                          
                          <td><b><small>Fullname:</small></b></td>
                          <td id="fullname"> {{ $student->fullname }} </td>

                          <td><b><small>Date:</small></b></td>
                          <td id="year">{{ date('F j, Y h:i A', strtotime($submittedAssignments->created_at)) }}</td>
                      </tr>
                  </tbody>
              </table>
            </div>
            <!-- END STUDENT INFORMATION -->

            <!-- START STUDENT'S SUBMIITED FILE -->
            <div class="col-md-12 col-lg-12">
              <br>
              <label class="pt-5">Files:</label>
              <br>
              @if($submittedAssignments->files)
                @if(count($submittedAssignments->files)>0)
                  @foreach($submittedAssignments->files as $file)
                    @if($file['filepath'])
                      @php
                        $file_extension = pathinfo($file['filepath'], PATHINFO_EXTENSION);
                      @endphp
                      <a class="pull-left" target="_blank" href="{{ asset($file['filepath']) }}" download="{{ asset($file['filepath']) }}">
                        @if($file_extension == 'pdf')
                          <i class="fa fa-file-pdf-o"></i>
                        @elseif($file_extension == 'docx' || $file_extension == 'doc' || $file_extension == 'docm')
                          <i class="fa fa-file-word-o"></i>
                        @elseif($file_extension == 'xlsx' || $file_extension == 'xltx' || $file_extension == 'xlsm')
                          <i class="fa fa-file-excel-o"></i>
                        @elseif($file_extension == 'pptx' || $file_extension == 'ppt' || $file_extension == 'pptm' || $file_extension == 'potx' || $file_extension == 'ppsx')
                          <i class="fa fa-file-powerpoint-o"></i>
                        @else
                          <i class="fa fa-file"></i>
                        @endif
                        {{$file['filename']}}
                      </a>
                    @endif
                    <br>
                  @endforeach
                @endif
              @endif
            </div>
            <!-- END STUDENT'S SUBMIITED FILE -->

            <!-- START STUDENT'S SUBMIITED ANSWER -->
            <div class="col-md-12 col-lg-12">
              <!-- Answer -->
              <div class="ckeditor" style="padding: 20px 30px;">
                {!!$submittedAssignments->answer!!}
              </div>
            </div>
            <!-- END STUDENT'S SUBMIITED ANSWER -->

            @if($submittedAssignments)
              @if($submittedAssignments->status == 'Scored')
                <div class="col-md-12 col-lg-12">
                  <h3 class="text-center" style="margin-bottom: 20px;">Rubrics</h3>
                  <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Points</th>
                        <th scope="col">Score</th>
                        <th scope="col">Comment</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $rubrics = json_decode($submittedAssignments->rubrics); @endphp
                      @if(count($rubrics)>0)
                        @foreach($rubrics as $rubric)
                          <tr>
                            <td scope="col">{{ $rubric->name }}</td>
                            <td scope="col">{{ $rubric->points }}</td>
                            <td scope="col">{{ $rubric->score }}</td>
                            <td scope="col">{{ $rubric->comment }}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                  <label class="pt-5">Total Score: {{ $submittedAssignments->student_score }}</label>
                </div>
              @endif
            @endif

          </div>
        </div>
      @endif
      <!-- END STUDENT'S SUBMIITED ASSIGNMENT -->

      @if($submittedAssignments)
        @if($submittedAssignments->status != 'Scored')
          <!-- START STUDENT SCORE / RUBRICS -->
          <div class="row m-t-20">
            <div class="{{ $crud->getEditContentClass() }}">
              <!-- Default box -->

              @include('crud::inc.grouped_errors')

                <form method="post"
                    action="{{ url($crud->route.'/'.$entry->getKey()) . '?assignment_id=' . $assignment->id . '&studentnumber=' . $student->studentnumber . '&class_code=' . $class->code}}"
                  @if ($crud->hasUploadFields('update', $entry->getKey()))
                  enctype="multipart/form-data"
                  @endif
                    >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="col-md-12">
                  @if ($crud->model->translationEnabled())
                  <div class="row m-b-10">
                    <!-- Single button -->
                  <div class="btn-group pull-right">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                        <li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  </div>
                  @endif
                  <div class="row display-flex-wrap">
                    <!-- load the view from the application if it exists, otherwise load the one in the package -->
                    @if(view()->exists('vendor.backpack.crud.form_content'))
                      @include('vendor.backpack.crud.form_content', ['fields' => $fields, 'action' => 'edit'])
                    @else
                      @include('crud::form_content', ['fields' => $fields, 'action' => 'edit'])
                    @endif
                  </div><!-- /.box-body -->

                      <div class="">
                        
                        @include('crud::inc.form_save_buttons')

                  </div><!-- /.box-footer-->
                </div><!-- /.box -->
                </form>
            </div>
          </div>
          <!-- END STUDENT SCORE / RUBRICS -->
        @endif
      @endif

    </div>
    <!-- END OF MAIN FEED -->
  </div>

  {{-- START ONLINE CLASS FORM --}}
  @if(backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)
    <form action="{{url('admin/teacher-online-class/video_conference') }}" method="GET" target="_blank" id="form{{ $class->code }}">
      @csrf
      <input type="hidden" name="_method" value="GET">
      <input type="hidden" name="classid" value="{{$class->id}}">
      <input type="hidden" name="class_code" value="{{$class->code}}">
    </form>
  @endif
  <script type="text/javascript">
    document.getElementById("nav-assignments").classList.add("active");
  </script>

@endsection

@push('after_styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush

@push('after_scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script>
      $('.startVideoConferencing').click(function () {
        var classCode = $(this).attr('classcode');
        console.log(classCode);
        $('#form' + classCode).submit();
      });

      $('#btnAddAssignment').click(function () {
        window.location.href = "{{ url('admin/online-class/assignment/create?class_code=' . $class->code) }}";
      });
  </script>
@endpush
