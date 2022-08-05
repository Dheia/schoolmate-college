<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Enrolment List</title>
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script> --}}
	{{-- <script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script> --}}
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
                     @foreach($students as $key => $student)
			        		[
			        			'{{ $key + 1 }}', 
			        			'{{ $student->lrn }}',
			        			'{{ $student->studentnumber }}',
			        			'{{ $student->lastname }}',
			        			'{{ $student->firstname }}',	
                                '{{ $student->middlename }}',		        			
			        			@if($level??'')
	        					@else
			        			'{{ $student->level->year ?? '-' }}',
			        			@endif
			        			@if($department->with_track && $track == null)
			        			'{{ $student->track_name ?? '-' }}',
			        			@endif
			        			'{{ $student->gender }}',
                                '{{ $student->is_enrolled }}',
			        		],
		        		@endforeach
	        		];

	    // TABLE
	    doc.autoTable({
	    	headStyles:{
	    		fillColor: '#3c8dbc'
	    	},
	    		// HEADER
                head: [[
	        	'No.',
	        	'Lrn',
	        	'Student No.',
	        	'Last Name',
	        	'First Name',
	        	'Middle Name',
	        	@if($level??'')
	        	@else
	        	'Level',
	        	@endif
	        	@if($department->with_track && $track == null)
	        	'Track',
	        	@endif
	        	'Gender',
	        	'Status',
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
		        { header: "No.", dataKey: "number" },
		        { header: "Lrn", dataKey: "lrn" },
		        { header: "Student No.", dataKey: "studentnumber" },
		        { header: "Last Name", dataKey: "lastname" },
		        { header: "First Name", dataKey: "firstname" },
		        { header: "Middle Name", dataKey: "middlename" },
		        @if($level??'')
				@else
    			{ header: "Level", dataKey: "level" },
    			@endif
    			@if($department->with_track && $track == null)
    			 { header: "Track", dataKey: "track" },
    			@endif		        
		        { header: "Gender", dataKey: "gender" },
		        { header: "Status", dataKey: "status" }
		    ],
		    columnStyles: {
		        number: { columnWidth: 8 },
		        gender: { columnWidth: 10 }
		    },
	        // columnStyles: {europe: {halign: 'center'}}, 


	        didDrawPage: function (data) {
	         // SCHOOL LOGO
             var base64Img = "{{ $schoollogo }}";
	            if (base64Img) {
	                doc.addImage(base64Img, 'PNG', (data.settings.margin.left * data.settings.margin.right ) / 2, 5, 12, 12);
	            }

	            // SCHOOL NAME
	            doc.setFontSize(9);
	            doc.setFontType("bold");
	            doc.myText("{{ strtoupper(config('settings.schoolname')) }}",{align: "center"},0,25);
				
				// SCHOOL ADDRESS
	            doc.setFontSize(6.5);
	            doc.setFontType("normal");
	            doc.myText("{{ Config::get('settings.schooladdress') }}", {align: "center", },0,28);

 
	            // TITLE
	            doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.myText("STUDENT LIST", {align: "center"},0,40);
	            
	            // AS OF {DATE}
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.setFontStyle('italic');
	            doc.myText("as of {{ Carbon\Carbon::today()->format('M. d, Y') }}", {align: "center", fontStyle: "italic"},0,44);

	            // SCHOOL YEAR
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("School Year: ", data.settings.margin.left, 55);

	            // SCHOOL YEAR: 2019 - 2020
	            doc.setFontType("bold");
	            doc.text("{{ $schoolYear ?? '*' }}", data.settings.margin.left + 20, 55);

	            // DEPARTMENT
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Department: ", data.settings.margin.left, 60);

	            // DEPARTMENT: Grade School, Senior High, etc..
	            doc.setFontType("bold");
	            doc.text("{{ $department ? $department->name : '*' }}", data.settings.margin.left + 20, 60);

	           // LEVEL
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Level: ", data.settings.margin.left, 65);

	            // LEVEL: Grade 1, Grade 2, Grade 3, etc...
	            doc.setFontType("bold");
	            doc.text("{{ $level ?? '*' }}", data.settings.margin.left + 20, 65);

	            @if($department->with_track)
	            // TRACK
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Track: ", data.settings.margin.left, 70);

	            // TRACK: Track: stem, gas, humss, etc...
	            doc.setFontType("bold");
	            doc.text("{{ $track ?? '*' }}", data.settings.margin.left + 20, 70);
		            @if(count($department->term->ordinal_terms) > 1)
			            // TERM
			            doc.setFontSize(8);
			            doc.setFontType("normal");
			            doc.text("Term: ",  data.settings.margin.left, 75);

			            // TERM: Term: full, first, second
			            doc.setFontType("bold");
			            doc.text("{{ $term ?? '*' }}",  data.settings.margin.left + 20, 75);
		            @endif
	            @endif

	            // TERM
	            @if(!$department->with_track && count($department->term->ordinal_terms) > 1)
		            doc.setFontSize(8);
		            doc.setFontType("normal");
		            doc.text("Term: ",  data.settings.margin.left, 70);

		            // TERM: Term: full, first, second
		            doc.setFontType("bold");
		            doc.text("{{ $term ?? '*' }}",  data.settings.margin.left + 20, 70);
	            @endif

	       
	            @if($department->with_track && $track == null)
		            var y   = statistics_y;
		             // STATISTICS
		            doc.setFontSize(7);
		            doc.setFontType("bold");
		            doc.text("Statisctics", statistics_x, statistics_y - 5);
		            @foreach($total_students_tracks as $key => $value)
		            	
			            if((num % 2) == 0){
			            	doc.setFontSize(7);
			            	doc.setFontType("normal");
			            	doc.text("{{ $key }}: ", statistics_x , statistics_y);

			            	doc.setFontSize(7);
			                doc.setFontType("bold");
			                doc.text("{{ $value }}", statistics_x + 30, statistics_y);
			                statistics_y = statistics_y + 3;
			            }
			            else{
			            	doc.setFontSize(7);
			            	doc.setFontType("normal");
			            	doc.text("{{ $key }}: ", 155 , statistics_y - 3);

			            	doc.setFontSize(7);
			                doc.setFontType("bold");
			                doc.text("{{ $value }}", 180, statistics_y - 3);
			            }
			            num++;
		            @endforeach
	            @else
		            // STATISTICS
		            doc.setFontSize(7);
		            doc.setFontType("bold");
		            doc.text("Statisctics", statistics_x, statistics_y - 5);
	            @endif

	            
                // MALE
                doc.setFontSize(7);
                doc.setFontType("normal");
                doc.text("Male: ", statistics_x, statistics_y+=1);

                // MALE: {number}
                doc.setFontSize(7);
                doc.setFontType("bold");
                doc.text("{{ $total_male }}", statistics_x + 30, statistics_y);

	            // FEMALE
	            doc.setFontSize(7);
	            doc.setFontType("normal");
	            doc.text("Female: ", statistics_x, statistics_y+=3);

	            // FEMALE: {number}
	            doc.setFontSize(7);
	            doc.setFontType("bold");
	            doc.text("{{ $total_female }}", statistics_x + 30, statistics_y);


                // Applicant
                doc.setFontSize(7);
	            doc.setFontType("normal");
	            doc.text("Applicant: ", 155, statistics_y-3);

	            // Applicant: {number}
	            doc.setFontSize(7);
	            doc.setFontType("bold");
	            doc.text("{{ $total_applicant }}", 180, statistics_y-3);
	            
                // Status
                doc.setFontSize(7);
	            doc.setFontType("normal");
	            doc.text("Enrolled: ", 155, statistics_y);

	            // Status: {number}
	            doc.setFontSize(7);
	            doc.setFontType("bold");
	            doc.text("{{ $total_enrolled }}", 180, statistics_y);



	            // TOTAL
	            doc.setFontSize(7);
	            doc.setFontType("bold");
	            doc.text("Total: ", 155, statistics_y += 3);

	            // TOTAL: {number}
	            doc.setFontSize(7);
	            doc.setFontType("bold");
	            doc.text("{{ $total_male + $total_female }}", 180, statistics_y);

	            // RECTANGLE ( x, y, width, height)
	            @if($department->with_track && $track == null)
	            	doc.rect(106, 52, 90, statistics_y - 61 + 8 +5);
	            @else
	            	doc.rect(106, 52, 90, 20);
	            @endif

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
	        	@if($department->with_track && $track == null)
	        		top: {{$level ? '80' : '85'}},
	        	@else
	        		top: {{count($department->term->ordinal_terms) > 1 ? '80' : '76'}},
	        	@endif
	        	bottom: 30,
	        }
	    });
	    
	    doc.save('{{$department->name}} Student List {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>