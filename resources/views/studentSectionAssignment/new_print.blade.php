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
	    var bodymale = 	[
            @if(count($students) > 0)
                @if( isset($students['Male']) )
                    @foreach($students['Male'] as $keymale => $studentmale)
                        [
                    
                        '{{ $keymale + 1 }}. {{ $studentmale->full_name }}'
                    
                       ],   
                 @endforeach
            @endif
        @endif
       
        ];
        var bodyfemale = 	[
            @if(count($students) > 0)
                @if( isset($students['Female']) )
                    @foreach($students['Female'] as $keyfemale => $studentfemale)
                        [
                    
                        '{{ $keyfemale + 1 }}. {{ $studentfemale->full_name }}'
                    
                       ],   
                 @endforeach
            @endif
        @endif
       
        ];
        
	    // TABLE
	    doc.autoTable({
	    	headStyles:{
                
	    		fillColor: '#3c8dbc',
                halign: 'center'
	    	},
	    	// HEADER
	        head: [ [
             
            ],
            // {content: `No. Of Units`, colspan: 3, styles: { halign: 'center' ,fontStyle: 'bold' }},
	        ], 
	        // BODY
			tableWidth: 'auto',
	        styles: {
	        	whiteSpace: 'nowrap',
	        	fontSize: 7,
	        	cellPadding: 1,
                halign : 'center'
	        },
	        bodyStyles: {
	        	width: 'auto'
	        },
	        showFoot: 'everyPage',
	        showHead: 'everyPage',
	        theme: 'grid',

         
	        // columnStyles: {europe: {halign: 'center'}}, 
			
	        didDrawPage: function (data) {

				var pageNumber = doc.internal.getNumberOfPages()

				doc.autoTable({
				columns: [
					{content: `Male`, colspan: 1, styles: { fontStyle: 'bold' }},
				],
				body: bodymale,
				startY: 55,
				styles: { overflow: 'hidden' },
				margin: { right: 105,top:55,bottom:20},
				theme: 'plain',
				
				})

				doc.setPage(pageNumber)

				doc.autoTable({
					columns: [
						{content: `Female`, colspan: 1, styles: { fontStyle: 'bold' }},
					],
					body: bodyfemale,
					startY: 55,
					styles: { overflow: 'hidden' },
					margin: { left: 105,top:55 ,bottom:20},
					theme: 'plain',

				})

				
	        }
	    });
			var pageSize 	= doc.internal.pageSize;
			var pageWidth 	= pageSize.Width ? pageSize.Width : pageSize.getWidth();
			var pageHeight 	= pageSize.height ? pageSize.height : pageSize.getHeight();
			var pageCount 	= doc.internal.getNumberOfPages(); //Total Page Number

			var marginX = (pageWidth - 12) / 2;

			for(i = 0; i < pageCount; i++) { 
				doc.setPage(i); 
				// SCHOOL LOGO
				var base64Img = "{{ $schoollogo }}";
				if (base64Img) {
					doc.addImage(base64Img, 'PNG', marginX, 5 ,12, 12);
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
				doc.myText("ADVISORY CLASS", {align: "center"},0,40);	

				// AS OF {DATE}
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.setFontStyle('italic');
	            doc.myText("as of {{ Carbon\Carbon::today()->format('M. d, Y') }}", {align: "center", fontStyle: "italic"},0,44);

				doc.setFillColor('#ECF0F5');
                doc.rect(14, 48, 182, 7, 'FD');

                
	            doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("Level: ", 16, 53);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("{{ $student_section->section->level->year }} ", 28, 53);

                doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("Section: ", 68, 53);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("{{ $student_section->section->name }} ", 80, 53);

                doc.setFontSize(8);
	            doc.setFontType("bold");
                doc.text("School Year: ", 160, 53);
                doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.text("{{ $student_section->schoolYear->schoolYear }}", 179, 53);



				//Footer
				let pageCurrent = doc.internal.getCurrentPageInfo().pageNumber; //Current Page
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

				doc.setFontSize(8);
				doc.text('Page ' + pageCurrent ,10, doc.internal.pageSize.height - 10);
			}


	  
	    doc.save('Section Form {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>