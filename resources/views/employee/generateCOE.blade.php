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
        

	    // TABLE
	    doc.autoTable({
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
	            doc.setFontSize(14);
	            doc.setFontType("bold");
	            doc.myText("CERTIFICATE OF EMPLOYMENT AND COMPENSATION", {align: "center"},0,40);
                





             

				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("This is to certify that ", {align: "left"},27,65);

				// //first name
				// doc.setFontSize(11);
	            // doc.setFontType("bold");
	            // doc.myText("{{ strtoupper($employee->full_name) }}", {align: "left"},68,65);
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("is presently employed by ", {align: "left"},147,65);


                var line = 65 // Line height to start text at
                var lineHeight = 10
                var leftMargin = 27
                var wrapWidth = 220
                var longString = 'This is to certify that {{  strtoupper($employee->full_name) }} is presently employed by {{ config('settings.schoolname') }} as {{ $employee->position }}. He/She has been with the company since  {{ Carbon\Carbon::parse($employee->date_hired)->format('d F Y')  }}.'

                var splitText = doc.splitTextToSize(longString, wrapWidth)
                for (var i = 0, length = splitText.length; i < length; i++) {
                // loop thru each line and increase
                doc.setFontType("normal");
                doc.setFontSize(11);
                doc.text(splitText[i], leftMargin, line)
                line = lineHeight + line
                }

				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("{{ config('settings.schoolname') }} ", {align: "left"},85,65);
				// //position
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("as Technical Support –Customer Services.", {align: "left"},27,75);
				
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("He/She has been with the company since ", {align: "left"},103,75);

				// //Date Hired
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("05 May 2019.", {align: "left"},27,85);


				doc.setFillColor('#EBF3F8');
				doc.rect(25, 90, 165, 10, 'FD');
				//boxline--
				doc.setDrawColor('#3C8DBC');
				doc.rect(25, 89.8, 165, 45, 'S');
				
				doc.setFontSize(11);
	            doc.setFontType("normal");
	            doc.myText("Her receives the following compensation :", {align: "left"},27,95);

           
                @if($salary)
                    //salary
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("Php {{ number_format($salary->salary, 2, '.', ',') }}", {align: "left"},155,110);

                    //admin pay
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("Php {{ number_format($salary->admin_pay, 2, '.', ',') }}", {align: "left"},155,115);

                    //other pay
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("Php {{ number_format($salary->other_pay, 2, '.', ',') }}", {align: "left"},155,120);

                    doc.setLineWidth(0.3); 
                    doc.setDrawColor('#0A0A0F');
                    doc.line(27, 122, 188, 122,70)

                    //total
                    doc.setFontSize(11);
                    doc.setFontType("bold");
                    doc.myText("Php  {{ number_format($salary->salary+$salary->admin_pay+$salary->other_pay, 2, '.', ',') }}", {align: "left"},155,126);
                    
                @else
                	//salary
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("-", {align: "left"},155,110);

                    //admin pay
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("-", {align: "left"},155,115);

                    //other pay
                    doc.setFontSize(11);
                    doc.setFontType("normal");
                    doc.myText("-", {align: "left"},155,120);

                    //total
                    doc.setFontSize(11);
                    doc.setFontType("bold");
                    doc.myText("Php  -", {align: "left"},155,126);
                @endif

				@if($salary)
					@if($salary->salary_type == 'every_30th')
						//type of salary
						doc.setFontSize(11);
						doc.setFontType("normal");
						doc.myText("Every 30th", {align: "left"},30,110);

					@elseif($salary->salary_type == 'every_15th_and_30th')
						//type of salary
						doc.setFontSize(11);
						doc.setFontType("normal");
						doc.myText("Every 15th and 30th", {align: "left"},30,110);

					@elseif($salary->salary_type == 'every_day')
						//type of salary
						doc.setFontSize(11);
						doc.setFontType("normal");
						doc.myText("Every day", {align: "left"},30,110);
						
					@endif
				@else
					//type of salary
					doc.setFontSize(11);
					doc.setFontType("normal");
					doc.myText("-", {align: "left"},30,110);
				@endif

				//type of admin pay
				doc.setFontSize(11);
	            doc.setFontType("normal");
	            doc.myText("Admin Pay", {align: "left"},30,115);

				//type of other pay
				doc.setFontSize(11);
	            doc.setFontType("normal");
	            doc.myText("Other Pay", {align: "left"},30,120);

				doc.setFontSize(11);
	            doc.setFontType("normal");
	            doc.myText("This certification is being issued upon his request for whatever legal purpose it may serve him.", {align: "left"},27,150);

				doc.setFontSize(11);
	            doc.setFontType("normal");
	            doc.myText("Issued this {{ Carbon\Carbon::today()->format('d F Y') }} at {{ Config::get('settings.schooladdress') }}.", {align: "left"},27,165);

				//
				doc.setFontSize(11);
	            doc.setFontType("bold");
	            doc.myText("Human Resource", {align: "left"},27,200);
				// //Position
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("HRMD & Accounting", {align: "left"},27,205);
				// //COMPANY NAME
				// doc.setFontSize(11);
	            // doc.setFontType("normal");
	            // doc.myText("Tigernet Hosting and IT Services  ", {align: "left"},27,210);


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

	        },
	        margin: {
				top: 50,
	        	bottom: 30,
	        }
	    });
	    
	    doc.save('Employee Certification Of Employment {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>