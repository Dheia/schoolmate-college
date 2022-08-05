@extends('backpack::layout')

@section('header')

@endsection

@section('content')

  <!-- HEADER -->
    <div class="row" style="padding: 15px;">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
        <section class="content-header">
          <ol class="breadcrumb">
            <li>
              <a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
            </li>
            <li>
              <a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a>
            </li>
            <li class="active">Grades</li>
          </ol>
        </section>
        <h1 class="smo-content-title">
          <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name !!}</span>
        </h1>
        {{-- <div class="col-xs-6">
            <div id="datatable_search_stack" class="pull-left" placeholder="Search Student"></div>
        </div> --}}
      </div>

    </div>
  <!-- END OF HEADER -->
  


  <!-- CONTENT INFORMATION -->
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="info-box shadow">
          <div class="box-body" style="padding-top:25px;">

          	<div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">School Year</span>
              	<span class="info-box-number">
              		<small>{{ $schoolYear ? $schoolYear->schoolYear : '-' }}</small>
              	</span>
            </div>

            <div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Department</span>
              	<span class="info-box-number">
              		<small>{{ $department ? $department->name : '-' }}</small>
              	</span>
            </div>

            <div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Level</span>
              	<span class="info-box-number">
              		<small>{{ $level ? $level->year : '-' }}</small>
              	</span>
            </div>

            <div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Section</span>
              	<span class="info-box-number">
              		<small>{{ $section ? $section->name : '-' }}</small>
              	</span>
            </div>

          	<div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Class Code</span>
              	<span class="info-box-number">
              		<small>{{ $student_section_assignment ? $student_section_assignment->class_code : '-' }}</small>
              	</span>
            </div>

            <div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Term Type:</span>
              	<span class="info-box-number">
              		<small>{{ $department ? $department->department_term_type : 'Term Type Not Set' }}</small>
              	</span>
            </div>
            <div class="col-md-3 col-lg-3">
              	<span class="info-box-text text-info">Term:</span>
              	<span class="info-box-number"><small>{{ $student_section_assignment->term_type }}</small></span>
            </div>

            <div class="col-md-3 col-lg-3">
               	<span class="info-box-text text-info">Adviser:</span>
              	<span class="info-box-number">
              		<small>{{ $student_section_assignment->adviser }}</small>
              	</span>
            </div>

          </div>
        </div>
      </div>
    </div>  
  <!-- END OF CONTENT INFORMATION -->

  <!-- DATA TABLE -->
    <div class="row">
      <div class="col-md-12 col-lg-12">

        <div>
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Class Roster</a>
              </li>
            @foreach($periods as $period)
              <li role="presentation" class="">
                <a href="#{{ Str::slug($period->name) }}" aria-controls="{{ Str::slug($period->name) }}" role="tab" data-toggle="tab" key="{{ $period->id }}">
                  {{ $period->name }}
                </a>
              </li>
            @endforeach
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
              <table class="box table table-striped table-hover display responsive nowrap m-t-0 shadow" cellspacing="0" id="tablePeriod{{ $period->id }}">
                <thead>
                    <tr>
                      <th>No.</th>
                      <th>
                          Student No.
                      </th>
                      <th>
                          Student
                      </th>
                    </tr>
                </thead>
                  
                {{-- BODY --}}
                <tbody>
                  @foreach($students as $key => $student)
                    <tr studentnumber="{{ $student->studentnumber }}">
                      <td>{{ $key+1 }}</td>
                      <td>{{ $student->studentnumber }}</td>
                      <td>{{ $student->fullname_last_first }}</td>
                    </tr>
                  @endforeach
                </tbody>
                  
                {{-- FOOTER --}}
                <tfoot>
                    <th>No.</th>
                    <th>Student No.</th>
                    <th>Student</th>
                </tfoot>
              </table>
            </div>
            @foreach($periods as $period)
              <div role="tabpanel" class="tab-pane" id="{{ Str::slug($period->name) }}">

                  <table class="box table table-striped table-hover display responsive nowrap m-t-0 shadow" cellspacing="0" id="tablePeriod{{ $period->id }}">
                    <thead>
                        <tr>
                          <th>
                              Student No.
                          </th>
                          <th>
                              Student
                          </th>
                          @if( count($subjects) > 0 )
                            @foreach ($subjects as $key => $subject)
                              <th>{{ $subject->subject_code }}</th>
                            @endforeach
                          @endif
                        </tr>
                    </thead>
                      
                    {{-- BODY --}}
                    <tbody>
                      @foreach($students as $key => $student)
                        <tr studentnumber="{{ $student->studentnumber }}">
                          <td>{{ $student->studentnumber }}</td>
                          <td>{{ $student->fullname_last_first }}</td>
                          @foreach($subjects as $subject)
                          <td  subject-id="{{ $subject->id }}" class="text-center" grade-loaded="false"><span style="color: red;">NG</span></td>
                          @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                      
                    {{-- FOOTER --}}
                    <tfoot>
                        <th>Student No.</th>
                        <th>Student</th>
                        @if( count($subjects) > 0 )
                          @foreach ($subjects as $key => $subject)
                            <th>{{ $subject->subject_code }}</th>
                          @endforeach
                        @endif
                    </tfoot>
                  </table>


              </div>
            @endforeach
          </div>

        </div>
        
        <!-- TABLE -->
{{--         <table class="box table table-striped table-hover display responsive nowrap m-t-0 shadow" cellspacing="0">
            <thead>
              	<tr>
	               	<th>
	                    Student
	              	</th>
                  @if( count($subjects) > 0 )
                    @foreach ($subjects as $key => $subject)
                      <th>{{ $subject->subject_code }}</th>
                    @endforeach
                  @endif
              	</tr>
            </thead>


            <tbody>
            	@foreach($students as $key => $student)
	            	<tr>
	            		<td>{{ $student->fullname_last_first }}</td>
	            	</tr>
            	@endforeach
            </tbody>
            
            <tfoot>
              	<th>
                    Studentnumber
              	</th>
            </tfoot>
          </table> --}}

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_scripts')

<script>

  var periods = [];
  var subjects = {{ json_encode(collect($subjects)->pluck('id')) }};
  var students = {{ json_encode(collect($students)->pluck('studentnumber')) }};
  var periods = [@foreach($periods as $period){id: {{ $period->id }}, isLoaded: false},@endforeach];

  console.log(periods);

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var periodId = $(e.target).attr('key');
    {{-- var subjects = {!! collect($subjects)->pluck('id')->toArray() !!} --}}
    // console.log(subjects);
    // $.each(students, function(k, val) {

        $.ajax({
          url: '{{ url('admin/advisory-class/' . $student_section_assignment->id . '/student-grades') }}',
          method: 'get',
          data: {
            subjects: subjects,
            period_id: periodId,
            // student_section_assignment_id: '{{ $section ? $section->id : null }}',
            // studentnumber: val,
          },
          success: function (grades) {
            console.log(grades);
            $.each(grades, function(gradeKey, grade) {

              $.each(grade.rows, function(rowKey, row) {
                var qGrade = row["initial_grade"] !== undefined ? row.quarterly_grade : '';
                // console.log(row);
                // console.log(qGrade);
                $('tr[studentnumber="' + row.studentnumber + '"]')
                  .find('td[subject-id="' + grade.subject_id + '"]')
                  .attr('grade-loaded', true)
                  .html('<span><b>' + row.quarterly_grade + '</b></span>');

              });

            });
          }
        });

        $('#tablePeriod' + periodId).find('td[grade-loaded="false"]').html('<span style="color: red;">NG</span>');
  })
</script>

@endsection