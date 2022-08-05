<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Section Management | View</title>
	<link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css">
</head>
<body>
	
	@php
		$curriculums = App\Models\CurriculumManagement::all();
		$years = App\Models\YearManagement::all();
		$subject_details = json_decode($data->subject_details);
		$total = 0;
	@endphp
	{{-- {{ $subject_details }} --}}
	<div class="container mt-3">

		<h1 class="text-center">WIS</h1>

		<div class="row">
				<table class="table table-sm table-hover mt-4">
				  <thead>
				    <tr class="text-center">
				      <th scope="col">Section</th>
				      <th scope="col">Subject(s)</th>
				      <th scope="col">Year</th>
				      <th scope="col">Description</th>
				      <th scope="col">Unit</th>
				    </tr>
				  </thead>
				  <tbody>
				  		<tr row>
				  			<td rowspan="{{ count($subject_details) + 1 }}" class="text-center" style="vertical-align: middle">CCIS1A</td>
				  		</tr>
				  	@foreach($subject_details as $subject_detail)
					    <tr class="text-center">
				  			@foreach($curriculums as $curriculum)
					  			@if($curriculum->id == $subject_detail->subject)
							     	<td>{{ $curriculum->curriculum_name }}</td>
							    @endif
			    			@endforeach

			    			@foreach($years as $year)
					  			@if($year->id == $subject_detail->year_id)
							     	<td>{{ $year->year }}</td>
							    @endif
			    			@endforeach

			    			<td>{{ $subject_detail->description }}</td>
			    			<td>{{ $subject_detail->unit }}</td>
			    			@php $total += (int)$subject_detail->unit  @endphp
					    </tr>
				    @endforeach
				    	<tr>
				    		<th colspan="3"></th>
				    		<th class="text-right">Total Units</th>
				    		<td> <strong>= {{ $total }}</strong> </td>
				    	</tr>
				  </tbody>
				</table> 
		</div>
	</div>
</body>
</html>