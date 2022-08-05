<a href="#" class="btn btn-primary ladda-button" data-style="zoom-in" id="btnAddGradeSetup"  data-toggle="modal" data-target="#gradeSetupModal">
	<span class="ladda-label"><i class="fa fa-plus"></i> Add Grade Setup</span>
</a>


@php
	// For Template
	$templateOptions = \App\Models\GradeTemplate::get();

	// For Section
    $schoolYearActive 	= \App\Models\SchoolYear::active()->first();
    $teacherSubject 	= \App\Models\TeacherSubject::where('teacher_id', backpack_auth()->user()->employee_id)->pluck('section_id');
    $section_ids 		= $teacherSubject;
    $sectionOptions 	= \App\Models\SectionManagement::whereIn('id', $section_ids)->get();

@endphp
<!-- Modal -->
<div class="modal fade" id="gradeSetupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Setup Grade</h4>
      </div>
      <div class="modal-body">

        	<form action="/{{ $crud->route }}" method="POST" id="addSetupGradeForm">
        		@csrf()
        		<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>
				{{-- TEMPLATE --}}
				<div class="form-group col-md-12">
					<label for="template">Template</label>
					<select name="template_id" id="template" class="form-control" style="width: 100%; display: unset;" required>
				        @foreach($templateOptions as $option)
				            @if(isset($_GET['template_id']))
				                <option value="{{ $option->id }}"  {{ $_GET['template_id'] == $option->id ? 'selected=true' : null }}>
				                    {{ $option->name }} 
				                    {{-- | {{ \App\Models\SchoolYear::where('id', $option->schoolyear_id)->firstOrFail()->schoolYear }} --}}
				                </option>
				            @else
				                <option value="{{ $option->id }}">
				                    {{ $option->name }} 
				                    {{-- | {{ \App\Models\SchoolYear::where('id', $option->schoolyear_id)->firstOrFail()->schoolYear }} --}}
				                </option>
				            @endif
				        @endforeach
				    </select>
				</div>
				
				{{-- SECTION --}}
				<div class="form-group col-md-6">
					<label for="section">Section</label>
					    <select name="section_id" id="section" class="form-control" style="width: 100%; display: unset;" required>
				        @foreach($sectionOptions as $option)
				            @if(isset($_GET['section_id']))
				                <option value="{{ $option->id }}"  {{ $_GET['section_id'] == $option->id ? 'selected=true' : null }}>{{ $option->name }}</option>
				            @else
				                <option value="{{ $option->id }}">{{ $option->name }}</option>
				            @endif
				        @endforeach
				    </select>
				</div>

				{{-- TERM --}}
				<div class="form-group col-md-6">
					<label for="term_type">Term</label>
					<select name="term_type" id="term_type" class="form-control" required>
						<option value="Full">Full</option>
					</select>
				</div>

				{{-- SUBJECT --}}
				<div class="form-group col-md-12">
					<label for="subject">Subject</label>
					<select name="subject_id" id="subject" class="form-control" required></select>
				</div>

				{{-- PERIOD --}}
				<div class="form-group col-md-12">
					<label for="period">Period</label>
					<select name="period_id" id="period" class="form-control" required></select>
				</div>

                {{-- TEMPLATE TYPE --}}
                <div class="form-group col-md-12">
                    <label for="period">Class Record Template</label>
                    <br>
                    <label class="radio-inline" id="detailed">
                        <input type="radio" name="class_record_type" value="detailed" checked>
                        Detailed
                    </label>
                    <label class="radio-inline" id="simplified">
                        <input type="radio" name="class_record_type" value="simplified">
                        Simplified
                    </label>
                </div>

                <div class="detailed">
                    <div class="form-group col-md-12">
                        <label>Detailed</label>
                    </div>

                    <table class="table">
                        <thead>
                            <th></th>
                            <th>Percentage (%)</th>
                            <th>No. of items</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Written Works</td>
                                <td><input type="number" name="written_work[percentage]" step="any" value="30" class="form-control" required></td>
                                <td><input type="number" name="written_work[no_of_items]" value="10" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td>Performance Tasks</td>
                                <td><input type="number" name="performance_task[percentage]" step="any" value="50" class="form-control" required></td>
                                <td><input type="number" name="performance_task[no_of_items]" value="10" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td>Quarterly Assessments</td>
                                <td><input type="number" name="quarterly_assessment[percentage]" step="any" value="20" class="form-control" required></td>
                                <td><input type="number" name="quarterly_assessment[no_of_items]" value="2" class="form-control" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="simplified hidden">
                    <div class="form-group col-md-12">
                        <label>Simplified</label>
                    </div>

                    <table class="table">
                        <thead>
                            <th></th>
                            <th>Percentage (%)</th>
                            <th>Total Accumulated Items</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Written Works</td>
                                <td>
                                    <input type="number" name="written_work[percentage]" step="any" value="30" class="form-control" disabled>
                                </td>
                                <td>
                                    <input type="number" name="written_work[raw]" step="any" value="50" class="form-control" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Performance Tasks</td>
                                <td>
                                    <input type="number" name="performance_task[percentage]" step="any" value="50" class="form-control" disabled>
                                </td>
                                <td>
                                    <input type="number" name="performance_task[raw]" step="any" value="50" class="form-control" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Quarterly Assessments</td>
                                <td>
                                    <input type="number" name="quarterly_assessment[percentage]" step="any" value="20" class="form-control" disabled>
                                </td>
                                <td>
                                    <input type="number" name="quarterly_assessment[raw]" step="any" value="100" class="form-control" disabled>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

			</form>

			<div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnAddSetupGrade">Add</button>
      </div>
    </div>
  </div>
</div>

@push('after_scripts')

<script>
	$(document).ready(function () {
		function getSubjects (call_term) {
            console.log('Term = ', $('#term_type option:selected').val());
			$.ajax({
                url: '/{{ $crud->route }}/get-subjects',
                type: 'post',
                data: {
                    section_id: $('#section option:selected').val(),
                    term_type: $('#term_type option:selected').val()
                },
                success: function (response) {
                    var subject_select = $('select[name="subject_id"');
                    var options = '';
                    $.each(response, function (key, val) {
                        options += '<option value="' + val.id + '">' + val.subject_code + ' - ' + val.subject_title + '</option>';
                    });

                    subject_select.html(options);
                    period();
                    if(call_term) { term(); }
                }
            })
		}

		function period () {
            $.ajax({
                url: '/{{ $crud->route }}/get-periods',
                type: 'post',
                data: {
                    section_id: $('#section option:selected').val()
                },
                success: function (response) {
                    var period_select = $('select[name="period_id"]');
                    var options = '';
                    $.each(response, function (key, val) {
                        options += '<option value="' + val.id + '">' + val.name + '</option>'
                    });

                    period_select.html(options);
                }
            });
        }

        async function term () {
            await $.ajax({
                url: '/{{ $crud->route }}/get-terms',
                type: 'post',
                data: {
                    section_id: $('#section option:selected').val()
                },
                success: function (response) {
                    var term_select = $('select[name="term_type"]');
                    var options = '';

                    if(response.length > 0) {
                        $.each(response, function (key, val) {
                        	options += '<option value="' + val + '">' + val + '</option>'; 
                        });
                    } else {
                        options += '<option selected disabled>No Term Found</option>'
                    }

                    term_select.html(options);
                    getSubjects(false);
                }
            });
        }

        term();
        period();
        getSubjects(true);
        $('select[name="section_id"]').change(function () {
            $('select[name="term_type"]').val('<option selected disabled>Loading...</option>');
            $('select[name="subject_id"]').val('<option selected disabled>Loading...</option>');
        	term();
        	period();
        });
        $('select[name="term_type"]').change(function () {
            $('select[name="subject_id"]').val('<option selected disabled>Loading...</option>');
        	period();
        	getSubjects(false);
        });

        $('#btnAddSetupGrade').click(function (e) {
        	$('#addSetupGradeForm').submit();
        })



        $('input[name="class_record_type"]').change(function () {
            if($(this).val() == 'detailed') {
                $('.detailed').removeClass('hidden');
                $('.simplified').addClass('hidden');

                $('.detailed input').removeAttr('disabled');
                $('.simplified input').attr('disabled', true);
            } else {
                $('.detailed').addClass('hidden');
                $('.simplified').removeClass('hidden');

                $('.detailed input').attr('disabled', true);
                $('.simplified input').removeAttr('disabled');
            }
        })
	});
</script>

@endpush