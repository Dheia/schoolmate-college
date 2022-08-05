@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">

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
      {{-- @include('student/online-class/partials/quipperAccount') --}}
      <!-- END Quipper Account -->
    </div>
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">

      <!-- START STUDENT LIST -->
      <div class="box shadow" style="border-radius: 10px;">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="row">
              <div style="padding: 10px 20px;">
                <h4 style="padding-bottom: 10px;">Student List</h4>
                <div style="padding: 20px 30px;">
                  @if($student_list)
                    @if(count($student_list)>0)

                      @foreach($student_list as $key => $student)
                        @php
                          $column = 3;
                        @endphp

                        @if( $key % $column == 0 )
                          <div class="col-md-12">
                        @endif
                        
                          <div class="col-md-4 m-t-10 m-b-10">

                            <div class="card card-primary card-outline">
                              <div class="card-body box-profile" style="border-top-color: #007bff !important;">
                                <div class="text-center">
                                  <img class="profile-user-img img-fluid img-circle" 
                                    src="{{ url($student->photo) }}" alt="Student profile picture">
                                </div>
                                <h5 class="text-center">
                                  <b>{{ $student->full_name }}</b>
                                </h5>
                                {{-- <p class="text-muted text-center">Software Engineer</p> --}}
                              </div>
                            </div>
                          </div>

                        @if( ($key + 1) % $column == 0 )
                          </div>
                        @endif

                      @endforeach

                      {{-- <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Gender</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($student_list as $key => $student)
                            <tr>
                              <td scope="row">{{ $key+1 }}</td>
                              <td>{{ $student->fullname_last_first }}</td>
                              <td>{{ $student->gender }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table> --}}
                    @else
                      <div class="box" style="border-radius: 10px;">
                        <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No Student Enrolled In This Class
                          </h4>
                        </div>
                      </div>
                    @endif
                  @else
                    <div class="box" style="border-radius: 10px;">
                      <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                        <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                          No Student Enrolled In This Class
                        </h4>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- END STUDENT LIST -->

    </div>
  
  </div>

  <script type="text/javascript">
    document.getElementById("nav-explore").classList.add("active");
  </script>

@endsection

@section('after_styles')
@endsection

@section('after_scripts')
@endsection
