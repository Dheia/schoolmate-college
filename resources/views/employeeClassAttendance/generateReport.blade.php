<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Class Attendance</title>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


	<style>
		{{-- @include('bootstrap4') --}}
		
		table td, table th {
			border: 0 !important;
			padding: 1px !important;
			font-size: 10px;
			background-color: #3c8dbc;
		}
		/*table.profile {border: 0.5px solid #ddd;}*/

		.profilediv {
			border: 1px solid #ddd;
			border-radius: 5px;
			margin-bottom: 10px;
			padding: 5px;
		}

		.signature-over-printed-name p {
			font-size: 9px;
			/*font-weight: 700;*/
		}
		
		/*footer { 
			position: fixed;
			bottom: -0px; 
			height: 50px;
			font-size: 10px;

		}*/

		@media screen {
		  footer {
		    display: none;
		  }
		}
		@media print {
			footer {
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
			}

			.pagebreak {
		        clear: both;
		        page-break-before: always;
		    }
		 /*	#table-body {
		 		margin: 25mm 25mm 25mm 25mm;
		 	}*/

		}

	
	</style>


</head>
<body>
	<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="{{ asset('js/jspdf.min.js') }}"></script>
	<script src="{{ asset('js/jspdf.plugin.autotable.js') }}"></script>

	<script>
		(function(API){
		    API.myText = function(txt, options, x, y) {
		        options = options ||{};
		        /* Use the options align property to specify desired text alignment
		         * Param x will be ignored if desired text alignment is 'center'.
		         * Usage of options can easily extend the function to apply different text 
		         * styles and sizes 
		        */
		        if( options.align == "center" ){
		            // Get current font size
		            var fontSize = this.internal.getFontSize();

		            // Get page width
		            var pageWidth = this.internal.pageSize.width;

		            // Get the actual text's width
		            /* You multiply the unit width of your string by your font size and divide
		             * by the internal scale factor. The division is necessary
		             * for the case where you use units other than 'pt' in the constructor
		             * of jsPDF.
		            */
		            txtWidth = this.getStringUnitWidth(txt)*fontSize/this.internal.scaleFactor;

		            // Calculate text's x coordinate
		            x = ( pageWidth - txtWidth ) / 2;
		        }

		        // Draw text at x,y
		        this.text(txt,x,y);
		    }
		})(jsPDF.API);

		var statistics_x = 109;
		var statistics_y = 61;
		var num = 2;
    	var doc = new jsPDF();
	    // It can parse html:
	    
	    // Or use javascript directly:
	    // BODY LIST
	    var body = 	[
		    			@foreach($employeeAttendances as $key => $employeeAttendance)
		    				@if($employeeAttendance->onlineClass)
				        		[
				        			'{{ date("F j, Y", strtotime($employeeAttendance->created_at)) }}',
				        			'{!! $employeeAttendance->onlineClass->section_level_name !!}',
				        			'{!! $employeeAttendance->onlineClass->subject_name !!}',

				        			'{{ $employeeAttendance->time_in ? date("h:i:s A", strtotime($employeeAttendance->time_in)) : "-" }}',

				        			'{{ $employeeAttendance->time_out ? date("h:i:s A", strtotime($employeeAttendance->time_out)) : "-" }}',

				        			@if($employeeAttendance->time_in && $employeeAttendance->time_out)
				        				@php
				        					$time_in = $employeeAttendance->time_in ? date_create($employeeAttendance->time_in) : null;
				        					$time_out = $employeeAttendance->time_out ? date_create($employeeAttendance->time_out) : null;

				        					$duration = $time_in && $time_out 
				        								? date_diff($time_in, $time_out)
				        									->format("%h Hours %i Minute %s Seconds") 
				        								: '-';
				        				@endphp
				        				'{{ $duration ? $duration : "-" }}'
				        			@else
				        			'-'
				        			@endif
				        		],
			        		@endif
		        		@endforeach
	        		];

	    // TABLE
	    doc.autoTable({
	    	headStyles:{
	    		fillColor: '#3c8dbc'
	    	},
	    	// HEADER
	        head: [[
	        	'Date',
	        	'Level & Section',
	        	'Subject',
	        	'Time In',
	        	'Time Out',
	        	'Duration'
	        ]],
	        // BODY
	        body: body,
			tableWidth: 'auto',
	        styles: {
	        	whiteSpace: 'nowrap',
	        	fontSize: 7,
	        	cellPadding: 1
	        },
	        bodyStyles: {
	        	width: 'auto'
	        },
	        showFoot: 'everyPage',
	        showHead: 'everyPage',
	        theme: 'grid',
	        columns: [
		        { header: "Date", dataKey: "date" },
		        { header: "Level & Section", dataKey: "level_section" },
		        { header: "Subject", dataKey: "subject" },
		        { header: "Time In", dataKey: "timein" },	        
    			{ header: "Time Out", dataKey: "timeout" },
    			{ header: "Duration", dataKey: "duration" },
		    ],
		    columnStyles: {
		        date: { columnWidth: 25 },
		        level_section: { columnWidth: 50 },
		        subject: { columnWidth: 35 },
		        timein: { columnWidth: 20 },
		        timeout: { columnWidth: 20 },
		    },
	        // columnStyles: {europe: {halign: 'center'}}, 

	        didDrawPage: function (data) {
	        	// SCHOOL LOGO
	            var base64Img = "{{ $schoollogo }}";
	            if (base64Img) {
	                doc.addImage(base64Img, 'PNG', (data.settings.margin.left * data.settings.margin.right ) / 2, 5, 12, 12);
	            }

	            // SCHOOL NAME
	            doc.setFontSize(10);
	            doc.setFontType("bold");
	            doc.myText("{{ config('settings.schoolname') }}",{align: "center"},0,25);
	            
	            // SCHOOL ADDRESS
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.myText("{{ config('settings.schooladdress') }}", {align: "center"},0,30);

	            // TITLE
	            doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.myText("Employee Class Attendance", {align: "center"},0,40);
	            
	            // AS OF {DATE}
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.setFontStyle('italic');
	            doc.myText("as of {{ Carbon\Carbon::today()->format('M. d, Y') }}", {align: "center", fontStyle: "italic"},0,44);

	            // EMPLOYEE NUMBER
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Employee No.: ", data.settings.margin.left, 55);

	            // EMPLOYEE NUMBER: 00000
	            doc.setFontType("bold");
	            doc.text("{{ $employee->employee_id }}", data.settings.margin.left + 20, 55);

	            // EMPLOYEE FULLNAME
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Name: ", data.settings.margin.left, 60);

	            // EMPLOYEE FULLNAME: NAME
	            doc.setFontType("bold");
	            doc.text("{{ $employee->full_name }}", data.settings.margin.left + 20, 60);

	            // DATE PERIOD
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Date Period: ", data.settings.margin.left, 65);

	            // DATE PERIOD: MMMM DD, YYYYY
	            var date_period = '{{date("F j, Y", strtotime($startDate))}}' + ' - ' + '{{date("F j, Y", strtotime($endDate))}}';

	            doc.setFontType("bold");
	            doc.text(date_period, data.settings.margin.left + 20, 65);

	            // (FOOTER)
	            // Footer
	            var str = "Page " + doc.internal.getNumberOfPages()

	            // Total page number plugin only available in jspdf v1.0+
	            if (typeof doc.putTotalPages === 'function') {
	                str = str;
	            }
	            // doc.setFontSize(10);

	            // jsPDF 1.4+ uses getWidth, <1.4 uses .width
	            var pageSize = doc.internal.pageSize;
	            var pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();

	            // SchoolMATE LOGO
	            var base64Img = "{{ $schoolmate_logo }}";
	            // if (base64Img) {
	            //     doc.addImage(base64Img, 'PNG', (data.settings.margin.left * data.settings.margin.right ) / 2, pageHeight - 33, 12, 15);
	            // }
	            if (base64Img) {
	                doc.addImage(base64Img, 'PNG',  185, pageHeight - 21, 10, 12);
	            }

	            // COPYRIGHT {DATE}
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.myText("Copyright  © {{ Carbon\Carbon::today()->format('Y') }}", {align: "center", fontStyle: "italic"},0, pageHeight - 15);

	            // Powered by: Tigernet Hosting and IT Services
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.myText("Powered by: Tigernet Hosting and IT Services", {align: "center", fontStyle: "italic"},0, pageHeight - 10);

	            // NEXT PAGE X AND Y FOR STATISTICS
	            statistics_x = 109;
				statistics_y = 61;
				num = 2;

	            doc.text(str, data.settings.margin.left, pageHeight - 10);
	        },
	        margin: {
	        	top: 80,
	        	bottom: 30,
	        }
	    });
	    
	    doc.save('{{$employee->full_name}} - Class Attendance ({{ Carbon\Carbon::now()->format('m-d-Y') }}).pdf');
	    history.go(-1);
	</script>
</body>



</html>