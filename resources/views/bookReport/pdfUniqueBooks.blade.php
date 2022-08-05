<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Book Report</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">


	<style>
		@include('bootstrap4')
		table td, table th {
			/*border: 0 !important;*/
			/*padding: 3px !important;*/
			padding: 3px !important;
		}
		body {
			font-size: 9px;
			margin-bottom: 50px !important;
			margin-top: 140px !important;
		}
		header {
			position: fixed;
			top: 0px;
			height: 100px;
		}
		footer { 
			position: fixed;
			bottom: 0px; 
			height: 50px;
			font-size: 10px;

		}
		.thead-1 {
			background-color: #eee;
		}
	</style>


</head>
<body>
	<header>
		<center>	
			<img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
			<p class="text-uppercase mb-0" style="font-size: 12px;"><b>{{ Config::get('settings.schoolname') }}</b></p>
			<p style="font-size: 10px;"><small>{{ Config::get('settings.schooladdress') }}</small></p>
		</center>
		<center class="text-uppercase">
			<b>{{ $title }}</b>
			<br>
			<p style="font-style: italic;">as of {{ Carbon\Carbon::today()->format('M. d, Y') }}</p>
		</center>
	</header>
	<footer>
		<center>
			<img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
		</center>
		<center>
			<p class="mb-0">Copyright &copy; 2019</p>
			<p class="pt-0">Powered by: Tigernet Hosting and IT Services</p>
		</center>
	</footer>	
	<main>
		<div class="col-md-12">
		    <div class="row display-flex-wrap">
				<div class="box attendance-table-logs col-md-10 padding-10 p-t-20 p-b-20">
						<table class="table table-bordered table-sm">
							<thead>
								<tr>
									<th class="text-center" style="vertical-align: middle;">No.</th>
									<th class="text-center" style="vertical-align: middle;">Call No.</th>
									<th class="text-center" style="vertical-align: middle;">Title</th>
									<th class="text-center" style="vertical-align: middle;">Category</th>
									<th class="text-center" style="vertical-align: middle;">Edition</th>
									<th class="text-center" style="vertical-align: middle;">Year <br>Published</th>
									<th class="text-center" style="vertical-align: middle;">Publisher</th>
									<th class="text-center" style="vertical-align: middle;">Authors</th>
									<th class="text-center" style="vertical-align: middle;">Quantity</th>
								</tr>
							</thead>

							<tbody>
								@php
									$key = 0;
								@endphp
								@foreach($books as $book)
									@php
										$key++;
									@endphp
									<tr>
										<td class="text-center" style="vertical-align: middle;"> {{ $key }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->call_number }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->title }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->category->title }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->edition }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->year_published }} </td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->publisher }} </td>
										<td class="text-center" style="vertical-align: middle;"> 
											@foreach($book->authors as $author) 
												{{ $author['name']}}
											@endforeach 
										</td>
										<td class="text-center" style="vertical-align: middle;"> {{ $book->quantity }} </td>
									</tr>
								@endforeach
							</tbody>
						</table>
				</div><!-- /.box -->
		  	</div>
		</div>

	</main>
	
</body>
</html>
