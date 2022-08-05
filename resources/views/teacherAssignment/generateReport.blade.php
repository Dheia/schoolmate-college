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
    	var doc = new jsPDF();
	    // It can parse html:

	    // Or use javascript directly:
     
	    // BODY LIST
	    var body = 	[
					@foreach($teacher_subjects as $key => $teacher_subject)
						[
						'{{$teacher_subject->schoolYear->schoolYear}}',
						'{{$teacher_subject->term_type}}',
						'{{$teacher_subject->level_name}}',
						@if(!$teacher_subject->track_name)
								'-',
							@else
								'{{$teacher_subject->track_name}}',
							@endif
						'{{$teacher_subject->section->name}}',
						'{{$teacher_subject->subject->subject_title}}',
							@if(!$teacher_subject->summer)
								'-',
							@else
								'{{$teacher_subject->summer}}',
							@endif
							'No Grades Submitted'
						
						,
						],   
					@endforeach
       
        ];
        
	    // TABLE
	    doc.autoTable({
	    	headStyles:{
                
	    		fillColor: '#3c8dbc',
                halign: 'center'
	    	},
	    	// HEADER
			head: [[
				{content: `School Year`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Term Type`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Level`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Track`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Section`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Subject`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Summer`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},
				{content: `Submitted Grades`, colSpan: 1, styles: {  valign: 'middle', fontStyle: 'bold' }},

	        ]],
	        // BODY
			tableWidth: 'auto',
	        styles: {
	        	whiteSpace: 'nowrap',
	        	fontSize: 7,
	        	cellPadding: 1,
                halign : 'center'
	        },
            body:body,
	        bodyStyles: {
	        	width: 'auto'
	        },
	        showFoot: 'everyPage',
	        showHead: 'everyPage',
	        theme: 'grid',
			columns: [
		        { header: "School Year", dataKey: "number" },
		        { header: "Term Type", dataKey: "fullname" },
		        { header: "Level", dataKey: "created_by" },
				{ header: "Track", dataKey: "created_by" },
				{ header: "Section", dataKey: "created_by" },
				{ header: "Subject", dataKey: "created_by" },
				{ header: "Summer", dataKey: "summer" },
				{ header: "Submitted Grades", dataKey: "created_by" },
		    
		    ],
		    columnStyles: {
		        summer: { columnWidth: 12 },
		    },
		

	        // columnStyles: {europe: {halign: 'center'}}, 

	        didDrawPage: function (data) {
                // SCHOOL LOGO
                var base64Img = "{{ $schoollogo }}";
	            if (base64Img) {
	                doc.addImage(base64Img, 'PNG', (data.settings.margin.left * data.settings.margin.right ) / 2, 6, 12, 12);
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
	            doc.myText("TEACHER ASSIGNMENT", {align: "center"},0,40);

                doc.setFillColor('#ECF0F5');
                doc.rect(14, 45, 182, 7, 'FD');

                
	            doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("Employee No: ", data.settings.margin.left + 2, 50);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("MDSI - {{ $employee_datas->employee_id}}", data.settings.margin.left +23, 50);
              
                doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("Fullname: ", data.settings.margin.left + 70, 50);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("{{ $employee_datas->fullname}}", data.settings.margin.left + 85, 50);
           
                doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("Type: ", data.settings.margin.left + 135, 50);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("{{ $employee_datas->type}}", data.settings.margin.left + 145, 50);

            
				
			

				
              

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
	        	
	        		top: 55,
	        	
	        	bottom: 20,
	        }
	    });
	  
	    doc.save('Teacher Assignment {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>