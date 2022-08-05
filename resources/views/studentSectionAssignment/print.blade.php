<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Section Form</title>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


	<style>
		@include('bootstrap4')
		body {
			font-size: .7rem;
		}
		table td, table th {
			border: 0 !important;
			padding: 3px !important;
		}

		/*table.profile tr td{font-size: 11px;}
		table.profile tr td:first-child{font-weight: 900;}
		table.profile tr td:nth-child(4){font-weight: 900;}


		table.profile tr td {width: 25%;}
		table.profile {margin-bottom: 0px;}
		table.profile {border: 0.5px solid #ddd;}


		.profilediv {
			border: 1px solid #ddd;
			border-radius: 5px;
			margin-bottom: 10px;
			padding: 5px;
		}

		.signature-over-printed-name p {
			font-size: 9px;
			/*font-weight: 700;*/
		}*/

		footer { 
			position: fixed;
			/*border: 1px solid black; */
			bottom: -0px; 
			/*right: 0px; */
			height: 50px;
			font-size: 10px;

		}

	
	</style>


</head>
<body>
    	<div class="col-md-12 m-b-30 m-t-20">
			<center>	
				<img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
				<p class="text-uppercase mb-0"><b>{{ Config::get('settings.schoolname') }}</b></p>
				<p><small>{{ Config::get('settings.schooladdress') }}</small></p>

				<p class="text-uppercase mt-3 mb-5"><b>Student Section</b></p>
			</center>

	    	
			<div class="col-md-12 m-0" style="padding: 0;">
	    		<table class="table">
	    			<tr>
						<td class="col-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
							<b>Level: </b> {{ $student_section->section->level->year }}
						</td>
						<td class="col-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
							<b>Section: </b> {{ $student_section->section->name }}
						</td>
						<td class="col-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
							<b>School Year: </b> {{ $student_section->schoolYear->schoolYear }}
						</td>
					</tr>
				</table>
			</div>


	    	{{-- MALE --}}
	    	<table class="table p-0">
	    		<tr>
	    			<td class="col-6 p-0">
					    	<table class="table table-striped table-bordered">
					    		<thead>
					    			<tr>
					    				<th  class="text-center">MALE</th>
					    			</tr>
					    		</thead>
					    		<tbody>
					    			@if(count($students) > 0)
					    				@if( isset($students['Male']) )
					    					@foreach($students['Male'] as $key => $student)
					    						<tr>
					    							<td >{{ $key + 1 }}. {{ $student->full_name }}</td>
					    						</tr>
					    					@endforeach
					    				@endif
					    			@endif
					    		</tbody>
					    	</table>
		    		</td>

		    	{{-- FEMALE --}}
		    		<td class="col-6 p-0">
					    	<table class="table table-striped table-bordered">
					    		<thead>
					    			<tr>
					    				<th  class="text-center">FEMALE</th>
					    			</tr>
					    		</thead>
					    		<tbody>
					    			@if(count($students) > 0)
					    				@if( isset($students['Female']) )
					    					@foreach($students['Female'] as $key => $student)
					    						<tr>
					    							<td >{{ $key + 1 }}. {{ $student->full_name }}</td>
					    						</tr>
					    					@endforeach
					    				@endif
					    			@endif
					    		</tbody>
					    	</table>
	    			</td>
	    		</tr>
    		</table>
    	</div>

		<footer>
			<center>
				<img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
			</center>
			<center>
				<p class="mb-0">Copyright &copy; 2019</p>
				<p class="pt-0">Powered by: Tigernet Hosting and IT Services</p>
			</center>
		</footer>	
	
</body>
</html>
