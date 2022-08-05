@extends("backpack::layout_parent")

@section('header')
    
@endsection

@section('after_styles')

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

@endsection

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url('parent/dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('parent/add-student') }}">Students</a></li>
          <li><a href="{{ url('student/student-enrollments/') }}">Enrollments</a></li>
          <li><a class="text-capitalize active">Grades</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Grades</span>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  
  <div class="row">
    <!-- STUDENT INFORMATION -->
    <div class="col-md-12 m-r-15">
      <div class="box" style="background: #3c8dbc; color: #FFF;">
        <div class="box-body">
          <div class="col-md-3 col-xs-5" style="padding-right: 0;">
            <h5><b>Student ID:</b></h5>
          </div>
          <div class="col-md-3 col-xs-7" style="padding-left: 0;">
            <h5>{{ $student->studentnumber }}</h5>
          </div>
          <div class="col-md-3 col-xs-5" style="padding-right: 0;">
            <h5><b>Fullname:</b></h5>
          </div>
          <div class="col-md-3 col-xs-7" style="padding-left: 0;">
            <h5>{{ $student->fullname }}</h5>
          </div>
        </div>
      </div>
    </div>
    <!-- END OF STUDENT INFORMATION -->

    <div class="col-xs-12 m-b-15">
      <div class="hidden-print with-border">
        {{-- <a href="{{ URL::to('admin/quickbooks/customer/create') }}" class="btn btn-primary">Add Customer</a> --}}
      </div>
    </div>

    <div class="col-md-12">
      
   
      <div class="box">

        {{-- <div class="box-header with-border">
        </div> --}}
          
        <div class="box-body">

          <table class="table table-bordered">
            <tbody>
              <tr style="background: #3c8dbc; color: #FFF;">
                <td class="text-center"><b>School Year:</b></td>
                <td class="text-center">{{ $schoolYear->schoolYear }}</td>
                <td class="text-center"><b>Grade/Level:</b></td>
                <td class="text-center">{{ $subjectMapping->level->year }}</td>
                <td class="text-center"><b>Track:</b></td>
                <td class="text-center">-</td>
                <td class="text-center"><b>Section:</b></td>
                <td class="text-center">{{ $section->name }}</td>
              </tr>
            </tbody>
          </table>

          <table class="table grades table-bordered table-striped">
            <thead>
              <th class="text-center">Subject Code</th>
              <th class="text-center">Subject Percentage</th>
              {{-- <th class="text-center">Subject Description</th> --}}
              @foreach($periods as $period)
                <th class="text-center">{{ $period->name }}</th>
              @endforeach
              <th class="text-center">Final Grade</th>
              <th class="text-center">Teacher</th>
            </thead>
            <tbody>

              @foreach($grades as $subjectKey => $subject)
                <tr id="tr-{{ $subject['id'] }}">
                  <td class="text-center" style="font-weight: 500">{{ $subject['subject_code'] }}</td>
                  <td class="text-center">{{ $subject['percent'] }}</td>

                  @foreach($periods as $periodkey => $period)
                    <td style="font-weight: 500" class="text-center grade-value" data-period-id="{{ $period['id'] }}"></td>
                  @endforeach

                  <td style="font-weight: 500" class="text-center final-grade"></td>
                  <td style="font-weight: 500" class="text-center proctor"></td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
                <tr>
                  <td style="font-weight: 700;" class="text-right" colspan="{{ count($periods) + 2 }}">General Average</td>
                  <td style="font-weight: 500;" class="text-center general-average"></td>
                  <td></td>
                </tr>
            </tfoot>
          </table>
  

            {{-- {{ dd($grades) }} --}}
            
        </div>

      </div>
      </div>
    </div>
@endsection

@section('after_scripts')

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <script>
    $(document).ready( function () {
        $('.grades').DataTable({
          "processing": true,
          "paging": false,
          "searching": false,
          // "serverSide": true,
          {{-- "ajax" : "{{ url('admin/quickbooks/customer/page/(:num)') }}" --}}
        });
    } );
  </script>


  <script>
    var grades = {!! json_encode($grades) !!};  

    $.each(grades, function(key, grade) {

      if(grade.hasOwnProperty('subjects')) {
        $.each(grade.subjects, function (subjectKey, subject) {
          $('#tr-' + grade.id).find('td[data-period-id="' + subject.period_id + '"]').text(subject.initial_grade);
        })

        $('#tr-' + grade.id).find('.final-grade').text(grade.final_grade.toFixed(2));
      }
    });


    //   var sum = 0;
    // $('.final-grade').each(function () {
    //   var finalGrade = $(this).text();
    //   if (!isNaN(finalGrade) && finalGrade.length !== 0) {
    //       sum += parseFloat(finalGrade);
    //   }
    // });

    var sum = 0;
      var inputedGradesCount = 0;
      var average = 0;
    $('.final-grade').each(function () {
      var finalGrade = $(this).text();
      if (!isNaN(finalGrade) && finalGrade.length !== 0) {
          sum += parseFloat(finalGrade);
          inputedGradesCount += 1;
      }
    });
    average = parseFloat(sum) / inputedGradesCount;
    if (isNaN(average)) {
      average = '-';
    } else {
      average = average.toFixed(2);
    }

    $('.general-average').text(average);
  </script>

@endsection