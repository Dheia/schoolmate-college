<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Student List</title>
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
    	var doc = new jsPDF('l');
	    // It can parse html:
	    
	    // Or use javascript directly:
	    // BODY LIST
	    var body = 	[	
			@if(count($attendances) == 0)
				[
					{content: `No Time In / Time Out`, colSpan: 5, styles: { halign: 'center' ,fontStyle: 'bold' }},

				],
				@else
					@foreach($attendances as $key => $attendance)
					[
						'{{ $attendance->user->full_name }}',
						'{{ $attendance->user_type }}',
						'{{ $attendance->time_in ? date("h:i:s A", strtotime($attendance->time_in)) : "-"}}',
						'{{ $attendance->time_out ? date("h:i:s A", strtotime($attendance->time_out)) : "-"}}',
						'{{ Carbon\Carbon::parse($attendance->created_at)->format("d F Y")  }}',
					],
					@endforeach

			@endif

        ];

	    // TABLE
	    doc.autoTable({
	    	headStyles:{
	    		fillColor: '#3c8dbc'
	    	},
	    	// HEADER
	        head: [[
	        	'User',
	        	'User Type',
	        	'Time IN',
	        	'Time OUT',
                'Date',
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
		        { header: "User", dataKey: "user" },
		        { header: "User Type", dataKey: "type" },
		        { header: "Time IN", dataKey: "time_in" },
		        { header: "Time OUT", dataKey: "time_out" },
                { header: "Date", dataKey: "date" },

		    ],
		    columnStyles: {
                time_in: { columnWidth: 20 },
                time_out: { columnWidth: 20 },

		    },
	        // columnStyles: {europe: {halign: 'center'}}, 

	        didDrawPage: function (data) {
                // SCHOOL LOGO
                var base64Img = "{{ $schoollogo }}";
	            if (base64Img) {
	                doc.addImage(base64Img, 'PNG', (data.settings.margin.left * data.settings.margin.right ) / 1.4, 6, 12, 12);
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
				doc.myText("SYSTEM ATTENDANCE REPORT", {align: "center"},0,40);

	 

	            // DATE
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Date Period: ", data.settings.margin.left, 55);

	            // Date:...
	            doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("{{ Carbon\Carbon::parse($startDate)->format('F d, Y') . ' - ' . Carbon\Carbon::parse($endDate)->format('F d, Y')}}", 33, 55);

				
                


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
	                doc.addImage(base64Img, 'PNG',  270, pageHeight - 21, 10, 12);
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
	        	
	        		top: 60,
	        	
	        	bottom: 25 ,
	        }
	    });
	    
	    doc.save('System Attendance List {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>