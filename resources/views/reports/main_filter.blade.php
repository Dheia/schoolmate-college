

@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Report Generator<small>Filter</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href=""></a></li>
        <li class="active">List Report</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="row">
	<form action='/admin/reports/enrollment-list' id='enrollment-list' target='_blank' method='GET'>
		<div class="col-md-6">
			<div class="form-group">
				<label for="school_year_id">Select Report</label>
				<select class="form-control" name="reports" id="reports">
					{{-- <option value="students">Student List</option> --}}
					<option value="enrollment">Enrollment List</option>
					{{-- <option value="enrollment">Requirements Submission Report</option> --}}
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="school_year_id">Select School Year</label>
				<select class="form-control" name="school_year_id" id="school_year_id">
					@foreach($school_year as $key => $item)
						<option value="{{$key}}">{{$item}}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label for="department_id">Select Department</label>
				<select class="form-control" id="department_id" name="department_id">
					<option value="all">All Departments</option>'
					@foreach($department as $key => $item)
						<option value="{{$key}}">{{$item}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label for="level_id">Select Level</label>
				<select class="form-control" name="level_id" id="level_id">
					
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label for="track_id">Select Track</label>
				<select class="form-control" name="track_id" id="track_id">
					
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label for="track_id">Select Term</label>
				<select class="form-control" name="term_type" id="term_type">
					<option value="FullTerm">Full Term</option>
					<option value="Semester">Semester</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="sort_gender">Sort By Gender</label>
				<select class="form-control" name="sort_gender">
					<option value="male_female">Alphabetical Male then Female</option>
					<option value="female_male">Alphabetical Female then Male</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<input type="submit" value="Generate Report" class="btn btn-primary float-right" id="generate">
		</div>	
	</div>
	</form>

  </section>
@endsection

@section('after_scripts')
	<script>
		
		$('#department_id').on('change', function() {
			$('#level_id').find('option').remove().end()
			$('#track_id').find('option').remove().end()
			$('#level_id').append('<option value="all">All Levels</option>');
			$('#track_id').attr('disabled', true)
			
			var department_id = this.value;
			var school_year_id = $('#school_year_id').value;

			if(department_id) {
				$.ajax({
				  url: "/admin/reports/filter-department",
				  data: { department_id: department_id, school_year_id: school_year_id, sort_gender: sort_gender},
				  context: document.body
				}).done(function(msg) {
					 $('#level_id').attr('disabled', false)
					$.each(msg,function(key, value) 
					{

					    $('#level_id').append('<option value=' + key + '>' + value + '</option>');
					});
				  	       
				});
			}

		});

		$('#level_id').on('change', function() {

			$('#track_id').find('option').remove().end()
			
			var department_id = $('#department_id').value;
			var level_id = this.value;
			var school_year_id = $('#school_year_id').value;
			
			if(level_id) {
				$.ajax({
				  url: "/admin/reports/filter-track",
				  data: { department_id: department_id, school_year_id: school_year_id, level_id: level_id, sort_gender:sort_gender },
				  context: document.body
				}).done(function(msg) {
					
					$.each(msg,function(key, value) 
					{
						if(value) {
							$('#track_id').append('<option value="all">All Tracks</option>');
							$('#track_id').attr('disabled', false)
							$('#track_id').append('<option value=' + key + '>' + value + '</option>');	
						}else{
							$('#track_id').attr('disabled', true)
						}
					    
					});
				  	       
				});
			}

		});

		$('#generate').on('submit',function(e) {
		

		})
	</script>
@endsection


