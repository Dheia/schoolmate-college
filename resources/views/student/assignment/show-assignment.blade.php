@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">
  <style>
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
  </style>

  <body style="background: #3c8dbc;">
  <div class="row p-l-30 p-r-30">
    @include('student/online-class/partials/navbar')
  </div>
  
  <div class="row p-l-30 p-r-30">

    <!-- START RIGHT SIDEBAR -->
    <div class="col-md-4 col-lg-4 col-two">
      <!-- START CLASS INFORMATION -->
      @include('student/online-class/partials/class_information')
      <!-- END CLASS INFORMATION -->

      <!-- Start Quipper Account -->
      @include('student/online-class/partials/quipperAccount')
      <!-- END Quipper Account -->
    </div>
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">

      <!-- START ASSIGNMENT -->
      <div class="">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="col-md-12 col-lg-12">
              {{ $assignment->title }}
              {!! $assignment->instructions !!}
              <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($assignment->due_date)) }}</p>

              @if($assignment_status != "Scored")
                <p><strong> Rubrics : </strong></p>
                <table class="table table-striped table-bordered">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Points</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $rubrics = json_decode($assignment->rubrics); @endphp
                    @if(count($rubrics)>0)
                      @foreach($rubrics as $rubric)
                        <tr>
                          <td scope="col">{{ $rubric->name }}</td>
                          <td scope="col">{{ $rubric->points }}</td>
                        </tr>
                      @endforeach
                    @endif
                    <tr>
                      <td></td>
                      <th>{{ $assignment->total_points }}</th>
                    </tr>
                  </tbody>
                </table>
              @endif
            </div>

            @if($submittedAssignments)
              @if($submittedAssignments->status == 'Scored')
                <div class="col-md-12 col-lg-12">
                  <p><strong> Rubrics : </strong></p>
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
                  <label class="pt-5">Total Score: {{ $submittedAssignments->score }}</label>
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>

      <!-- START SUBMITTED ASSIGNMENT -->
      @if($submittedAssignments)
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <h4 class="text-center">Your Submitted Assignment</h4>
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

            <!-- Answer -->
            <div class="ckeditor" style="padding: 20px 30px;">
              {!!$submittedAssignments->answer!!}
            </div>

          </div>
        </div>
      @endif
      <!-- END SUBMITTED ASSIGNMENT FORM -->

      <!-- START SUBMIT ASSIGNMENT FORM -->
      @if(!$submittedAssignments)
        <form method="post"
          action="{{ url('student/online-class-assignments' . '?id=' . $assignment->id) }}"
          @if ($crud->hasUploadFields('create'))
          enctype="multipart/form-data"
          @endif
            >
          {!! csrf_field() !!}
          <div class="row">
           {{--  @include('crud::inc.grouped_errors') --}}

            <div class="col-md-12 col-lg-12">
              @if(view()->exists('vendor.backpack.crud.form_content'))
                @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
              @else
                @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
              @endif
            </div>

          </div>
          @if(!$submittedAssignments)
            <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-success pull-right" >Submit</button>
          @endif
        </form>
      @endif
      <!-- END SUBMIT ASSIGNMENT FORM -->

    </div>
    
  </div>

  <script type="text/javascript">
    document.getElementById("nav-assignment").classList.add("active");
  </script>
  </body>
@endsection
