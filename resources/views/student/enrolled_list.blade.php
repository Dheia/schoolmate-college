@extends('backpack::layout_student')

@section('header')
    {{-- <section class="content-header">
      <h1>
        Enrollments<small>All enrollments list</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Enrollments</li>
      </ol>
    </section> --}}
@endsection

@section('after_styles')
  <style type="text/css">

    .dataTables_filter {
      float: right;
    }
    .swal-height {
      height: 500px;
    }

    .swal-width {
      width: 400px;
    }

    .swal-height-200 {
      height: 200px;
    }

    #enrollment_table {
      background-color: rgb(255, 255, 255); 
      border: 1px solid #d2d6de;
      border-top: 2px solid #d2d6de;
    }

    @media only screen and (max-width: 768px) {
      .swal-width {
        width: 300px;
      }
    }
    @media only screen and (min-width: 768px) {
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
    
  </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
          <li><a class="text-capitalize active">Enrollments</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollments</span>
        <small>All enrollments list</small>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <section class="row">

      <div class="col-md-12">
        
        <div class="table-responsive" style="width: 100%; overflow-x: visible;">
              <table class="box table table-striped" style="border-top: none; border-radius: 10px;">
                <thead>
                  <th>School Year</th>
                  <th>Department</th>
                  <th>Year Level</th>
                  <th>Track</th>
                  <th>Term</th>
                  <th>Tuition</th>
                  <th>Commitment Payment</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  @foreach($enrollments as $enrollment)
                    <tr>
                      <td>{{ $enrollment->schoolYear->schoolYear }}</td>
                      <td>{{ $enrollment->department->name }}</td>
                      <td>{{ $enrollment->level->year }}</td>
                      <td>{{ $enrollment->track ? $enrollment->track->code : '-' }}</td>
                      <td>{{ $enrollment->term_type }}</td>
                      <td>{{ $enrollment->tuition ? $enrollment->tuition->form_name : '-' }}</td>
                      <td>{{ $enrollment->commitmentPayment ? $enrollment->commitmentPayment->name : '-' }}</td>
                      <td>
                        @if($show_tuition && $enrollment->tuition)
                        <a href="{{ url()->current() . '/tuition/' . $enrollment->id}}" class="btn btn-sm btn-primary">View Tuition</a>
                        @endif
                        @if($enrollment->studentSectionAssignment)
                          <a href="{{ url()->current() . '/' . $enrollment->id . '/grade' }}" class="btn btn-sm btn-primary">View Grades</a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
      </div>

  </section>

  <!-- ENROLLMENT APPLICATION LIST -->
  @if( count($applications) > 0 )
  <section class="row">
      <div class="col-md-12">
        <h1 class="smo-content-title">
          <span class="text-capitalize">Enrollment Application</span>
        </h1>
      </div>
      <div class="col-md-12">
        
        <div class="table-responsive" style="width: 100%; overflow-x: visible;">
              <table class="box table table-striped" style="border-top: none; border-radius: 10px;">
                <thead>
                  <th>School Year</th>
                  <th>Department</th>
                  <th>Year Level</th>
                  <th>Track</th>
                  <th>Term</th>
                </thead>
                <tbody>
                  @foreach($applications as $application)
                    <tr>
                      <td>{{ $application->schoolYear->schoolYear }}</td>
                      <td>{{ $application->department->name }}</td>
                      <td>{{ $application->level->year }}</td>
                      <td>{{ $enrollment->track ? $enrollment->track->code : '-' }}</td>
                      <td>{{ $application->term_type }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
      </div>
  </section>
  @endif
  <!-- ENROLLMENT APPLICATION LIST -->

  @if( count($other_program_lists) > 0  || count($other_service_lists) > 0 )
  <!-- ENROLL OTHER PROGRAMS && ENROLL OTHER SERVICES -->
  <section class="row">
    <div class="col-md-12">
      @if( count($other_program_lists) > 0 )
        <div class="col-md-6 col-xs-12">
          <div class="small-box bg-primary">
            <div class="inner">
              <p>
                Enroll
                <br> 
                Other Programs
                <br>
                {{ $current_enrollment->term_type ? $current_enrollment->term_type . ' Term' : '-' }} | {{ $current_enrollment->school_year_name ?? '-' }}
              </p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="javascript:void(0)" onclick="enrollOtherProgram()" class="small-box-footer" style="font-size: 16px;">
              Enroll now <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      @endif

      @if( count($other_service_lists) > 0 )
        <div class="col-md-6 col-xs-12">
          <div class="small-box bg-primary">
            <div class="inner">
              <p>
                Enroll 
                <br>
                Other Services
                <br>
                {{ $current_enrollment->term_type ? $current_enrollment->term_type . ' Term' : '-' }} | {{ $current_enrollment->school_year_name ?? '-' }}
              </p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="javascript:void(0)" onclick="enrollOtherService()" class="small-box-footer" style="font-size: 16px;">
              Enroll now <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      @endif
    </div>
  </section>
  <!-- ENROLL OTHER PROGRAMS && ENROLL OTHER SERVICES -->
  @endif

  <!-- ELIGIBLE ENROLLMENT -->
  @if($nextEnrollment != null)
  <section class="row">

      <div class="col-md-12">
        <h3>Enrollment is now ongoing!</h3>
        
        {{-- @if($summerEnrollment != null)
        <div class="col-md-6 col-xs-12">
          <div class="small-box bg-primary">
            <div class="inner">
              <p>
                 {{ $summerEnrollment->level->year }}
                <br>
                <b>{{ $summerEnrollment->term_type . ' Term' }}</b>
                <br>
                {{ $summerEnrollment->schoolYear->schoolYear }}
              </p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="javascript:void(0)" onclick="summerEnroll()" class="small-box-footer" style="font-size: 16px;">
              Enroll now <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        @endif --}}

        @if($nextEnrollment != null)
        <div class="col-md-6 col-xs-12">
          <div class="small-box bg-primary">
            <div class="inner">
              <p>
                 {{ $nextEnrollment->level->year }}
                <br>
                <b>{{ $nextEnrollment->term_type . ' Term' }}</b>
                <br>
                {{ $nextEnrollment->schoolYear->schoolYear }}
              </p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="javascript:void(0)" onclick="nextEnrollment()" class="small-box-footer" style="font-size: 16px;">
              Enroll now <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        @endif
      </div>

  </section>
  @endif
</body>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@section('after_scripts')
  
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <script>
    $('.table').DataTable({
      "processing": false,
      "paging": false,
      "searching": false,
    });

    $(document).ready(function () {
      $('.dataTables_info').remove();
    });

    function validateEmail(email) {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
    }
  </script>

  {{-- @if($summerEnrollment != null)
  <script type="text/javascript">
    function summerEnroll()
    {
      var form  =   '<form id="enrollForm" name="enrollForm" method="post" action="' + "{{ url('student/enrollments/summer') }}" + '">\
                      @csrf\
                      <input type="hidden" name="enrollment_type" id="enrollment_type" value="summer">\
                      <div class="col-md-12">\
                        <table id="enrollment_table" class="table table-striped">\
                          <tbody class="text-left">\
                              <tr>\
                                  <td><small><strong>Student No.</strong></small></td>\
                                  <td><small>{{ $student->studentnumber }}</small></td>\
                              </tr> \
                              <tr>\
                                  <td><small><strong>Full Name</strong></small></td> \
                                  <td><small>{{ $student->full_name }}</small></td>\
                              </tr> \
                              <tr>\
                                  <td><small><strong>Enrolling As</strong></small></td> \
                                  <td>\
                                    <small>\
                                      {{ $summerEnrollment->level->year }} <br>\
                                      {{ $summerEnrollment->term_type }} <br>\
                                      {{ $summerEnrollment->schoolYear->schoolYear }} <br>\
                                    </small>\
                                  </td>\
                              </tr> \
                          </tbody>\
                        </table>\
                        <div class="form-group text-left">\
                          <label for="commitment_payment_id">Choose Commitment Basis</label>\
                          <select name="commitment_payment_id" id="commitment_payment_id" class="form-control">\
                            <option value="">-</option>\
                            @if(count($commitmentPayment) > 0)\
                              @foreach($commitmentPayment as $payment)\
                                <option value="{{ $payment->id }}">{{ $payment->name }}</option>\
                              @endforeach\
                            @endif\
                          </select>\
                        </div>\
                        <div class="form-group text-left">\
                          <label for="email">Email</label>\
                          <input type="email" class="form-control" placeholder="Enter email" name="email" id="email">\
                        </div>\
                      </div>\
                    </form>';
      Swal.fire({
        title: '<b>Summer Enrollment</b>',
        html: form,
        customClass: 'swal-height swal-width',
        // showCloseButton: true,
        showCancelButton: true,
        showDenyButton: false,
        confirmButtonText: 'Enroll',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        preConfirm: (commitment_payment_id) => {
          if(! $('#commitment_payment_id').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please choose commitment basis.',
              type: "waning"
            });
            return false;
          } else if(! $('#email').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please enter email.',
              type: "waning"
            });
            return false;
          }
          if(! validateEmail($('#email').val())) {
            new PNotify({
              title: 'Warning',
              text: 'Please enter a valid email',
              type: "waning"
            });
            return false;
          }
          return true
        }
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $('#enrollForm').submit();
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      });
    }
  </script>
  @endif --}}

  @if($nextEnrollment != null)
  <script type="text/javascript">
    function nextEnrollment()
    {
      var form  =   '<form id="enrollForm" name="enrollForm" method="post" action="' + "{{ url('student/enrollments/' . $nextEnrollment->term_type) }}" + '">\
                      @csrf\
                      <input type="hidden" name="enrollment_type" id="enrollment_type" value="summer">\
                      <div class="col-md-12">\
                        <table id="enrollment_table" class="table table-striped">\
                          <tbody class="text-left">\
                              <tr>\
                                  <td><strong>Student No.</strong></td>\
                                  <td>{{ $student->studentnumber }}</td>\
                              </tr> \
                              <tr>\
                                  <td><strong>Full Name</strong></td> \
                                  <td>{{ $student->full_name }}</td>\
                              </tr> \
                              <tr>\
                                  <td><strong>Enrolling As</strong></td> \
                                  <td>\
                                      {{ $nextEnrollment->level->year }}  <br>\
                                      {!! $nextEnrollment->track ? $nextEnrollment->track->code ."<br>" : ""  !!} \
                                      {{ $nextEnrollment->term_type . " Term" }} <br>\
                                      {{ $nextEnrollment->schoolYear->schoolYear }} <br>\
                                  </td>\
                              </tr> \
                          </tbody>\
                        </table>\
                        @if(!$nextEnrollment->track)\
                        @if(isset($nextEnrollment->department_tracks))\
                        <div class="form-group text-left">\
                          <label for="track_id">Choose Track</label>\
                          <select name="track_id" id="track_id" class="form-control">\
                            <option value="">-</option>\
                            @if(count($nextEnrollment->department_tracks) > 0)\
                              @foreach($nextEnrollment->department_tracks as $department_track)\
                                <option value="{{ $department_track->id }}">{{ $department_track->code }}</option>\
                              @endforeach\
                            @endif\
                          </select>\
                        </div>\
                        @endif\
                        @endif\
                        <div class="form-group text-left">\
                          <label for="commitment_payment_id">Choose Commitment Basis</label>\
                          <select name="commitment_payment_id" id="commitment_payment_id" class="form-control">\
                            <option value="">-</option>\
                            @if(count($commitmentPayment) > 0)\
                              @foreach($commitmentPayment as $payment)\
                                <option value="{{ $payment->id }}">{{ $payment->name }}</option>\
                              @endforeach\
                            @endif\
                          </select>\
                        </div>\
                        <div class="form-group text-left">\
                          <label for="email">Email</label>\
                          <input type="email" class="form-control" placeholder="Enter email" name="email" id="email">\
                        </div>\
                      </div>\
                    </form>';
      Swal.fire({
        title: '<b>Enrolling</b>',
        html: form,
        customClass: 'swal-height swal-width',
        // showCloseButton: true,
        showCancelButton: true,
        showDenyButton: false,
        confirmButtonText: 'Enroll',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        preConfirm: (commitment_payment_id) => {
          @if(isset($nextEnrollment->department_tracks))
          @if(count($nextEnrollment->department_tracks)>0)
          if(! $('#track_id').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please choose track.',
              type: "waning"
            });
            return false;
          }
          @endif
          @endif
          if(! $('#commitment_payment_id').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please choose commitment basis.',
              type: "waning"
            });
            return false;
          } else if(! $('#email').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please enter email.',
              type: "waning"
            });
            return false;
          }
          if(! validateEmail($('#email').val())) {
            new PNotify({
              title: 'Warning',
              text: 'Please enter a valid email',
              type: "waning"
            });
            return false;
          }
          return true
        }
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $('#enrollForm').submit();
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      });
    }
  </script>
  @endif

  @if( count($other_program_lists) > 0 )
  <script type="text/javascript">
    function enrollOtherProgram()
    {
      var form  =   '<form id="enrollProgramForm" name="enrollProgramForm" method="post" action="' + "{{ url('student/enrollments/enroll/other-program') }}" + '">\
                      @csrf\
                      <div class="col-md-12">\
                        <div class="form-group text-left">\
                          <label for="other_program_id">Select Other Program</label>\
                          <select name="other_program_id" id="other_program_id" class="form-control">\
                            <option value="">-</option>\
                            @foreach($other_program_lists as $other_program)\
                              <option value="{{ $other_program->id }}">{{ $other_program->name }} ({{ number_format($other_program->amount, 2) }})</option>\
                            @endforeach\
                          </select>\
                        </div>\
                      </div>\
                    </form>';
      Swal.fire({
        title: '<b>Enrolling Other Program</b>',
        html: form,
        customClass: 'swal-height-200 swal-width',
        // showCloseButton: true,
        showCancelButton: true,
        showDenyButton: false,
        confirmButtonText: 'Enroll',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        preConfirm: (other_program_id) => {
          if(! $('#other_program_id').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please select program.',
              type: "waning"
            });
            return false;
          }
          return true
        }
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $('#enrollProgramForm').submit();
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      });
    }
  </script>
  @endif

  @if( count($other_service_lists) > 0 )
  <script type="text/javascript">
    function enrollOtherService()
    {
      var form  =   '<form id="enrollServiceForm" name="enrollServiceForm" method="post" action="' + "{{ url('student/enrollments/enroll/other-service') }}" + '">\
                      @csrf\
                      <div class="col-md-12">\
                        <div class="form-group text-left">\
                          <label for="other_service_id">Select Other Service</label>\
                          <select name="other_service_id" id="other_service_id" class="form-control">\
                            <option value="">-</option>\
                            @foreach($other_service_lists as $other_service)\
                              <option value="{{ $other_service->id }}">{{ $other_service->name }} ({{ number_format($other_service->amount, 2) }})</option>\
                            @endforeach\
                          </select>\
                        </div>\
                      </div>\
                    </form>';
      Swal.fire({
        title: '<b>Enrolling Other Service</b>',
        html: form,
        customClass: 'swal-height-200 swal-width',
        // showCloseButton: true,
        showCancelButton: true,
        showDenyButton: false,
        confirmButtonText: 'Enroll',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        preConfirm: (commitment_payment_id) => {
          if(! $('#other_service_id').val()) {
            new PNotify({
              title: 'Warning',
              text: 'Please select service.',
              type: "waning"
            });
            return false;
          }
          return true
        }
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $('#enrollServiceForm').submit();
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      });
    }
  </script>
  @endif
    
@endsection