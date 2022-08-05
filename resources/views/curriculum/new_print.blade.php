<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Curriculum List</title>
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

    	var doc = new jsPDF();
		var first = 0;
		var last  = 0;

	    // TABLE
	    doc.autoTable({
	    	headStyles:{fillColor: '#3c8dbc', halign: 'center'},
			tableWidth: 'auto',
	        styles: {whiteSpace: 'nowrap', fontSize: 7, cellPadding: 1, halign : 'center' },
	        bodyStyles: { width: 'auto'},
	        showFoot: 'everyPage',
	        showHead: 'everyPage',
	        theme: 'grid',
	        
	        didDrawPage: function (data) {

                {!! $a = 0 !!}
				// BODY LIST
				@foreach($curriculum_subjects['subjectMappings'] as $subject_mapping)

					@php
						$total_units 	= 0;
						$total_lec_hrs  = 0;
						$total_lab_hrs  = 0;
						
					@endphp
				var body{!! $a!!} = [
					[
						
						{content: `Code`,  rowSpan:2,colspan: 1, styles: { valign: 'middle', halign: 'center' ,fontStyle: 'bold',height:'100' }},
						{content: `Subject Title`, rowSpan: 2, colspan: 1,styles: { valign: 'middle', halign: 'center' ,fontStyle: 'bold' }},
						{content: `Number of Hours`, colSpan: 2, rowSpan: 1, styles: { halign: 'center' ,fontStyle: 'bold' }},
						{content: `No. Of Units`, colspan: 2,rowSpan: 2, styles: {  valign: 'middle', halign: 'center' ,fontStyle: 'bold' }},
						{content: `Prerequisities`, rowSpan:2, styles: { valign: 'middle', halign: 'center' ,fontStyle: 'bold' }}
					],
					[
						{content: `Lecture`, styles: { valign: 'middle', fontStyle: 'bold' }},
						{content: `Lab`, styles: { valign: 'middle', fontStyle: 'bold' }},
					],
					@foreach($subject_mapping['subjects'] as $subject)

						// TOTAL COUNT
							@php 
								$total_units += \App\Models\SubjectManagement::find($subject->subject_code)->no_unit; 

							@endphp
							@if(isset($subject->lab_min))
								@php 
									$total_lec_hrs += $subject->lec_min/60; 
								@endphp
								
							@else
								@php
									$total_lec_hrs += 0;
								@endphp

							@endif		

							@if(isset($subject->lab_min))
								@php 
									$total_lab_hrs += $subject->lab_min/60; 
								@endphp
								
							@else
								@php 
									$total_lab_hrs += 0; 
								@endphp
												
							@endif
						
						[
							@if( \App\Models\SubjectManagement::find($subject->subject_code) ?? '')
							'{{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_code }}',
							@endif
							'{{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_title }}',
							@if(isset($subject->lec_min))
								'{{$subject->lec_min/60}}',
							@else
							'-',
							@endif

							@if(isset($subject->lab_min))
								'{{$subject->lab_min/60}}',
							@else
								'-',
							@endif
							'{{ number_format( (float) \App\Models\SubjectManagement::find($subject->subject_code)->no_unit, 1, '.', '') }} ',
							
							@if(isset($subject->pre_requisite))
								@if($subject->pre_requisite)
								'{{\App\Models\SubjectManagement::find($subject->pre_requisite)->subject_title}}'
								@else
									'-',
								@endif
							@else
							'-'
							@endif
						
						],
					
						@endforeach	
						
						[
							{content: `Total`, colSpan: 2, styles: { halign: 'right' ,fontStyle: 'bold' }},
							@if(isset($subject->lab_min))
							{content: `{{$subject->lec_min/60}}`, colSpan: 1, styles: { fontStyle: 'bold' }},
							@else
							{content: `{{$total_lec_hrs}}`, colSpan: 1, styles: { fontStyle: 'bold' }},
							@endif
							@if(isset($subject->lab_min))
							{content: `{{$subject->lec_min/60}}`, colSpan: 1, styles: { fontStyle: 'bold' }},
							@else
							{content: `{{$total_lab_hrs}}`, colSpan: 1, styles: { fontStyle: 'bold' }},
							@endif
							{content: `{{$total_units}}`, colSpan: 1, styles: { fontStyle: 'bold' }},
						
							]
						
						];
					{!! $a++ !!}
						@endforeach


                doc.autoTable({
					margin: {
						top:55
					},
				})
				@php $b = 0; @endphp
				@foreach ($curriculum_subjects['subjectMappings'] as $subject_mapping)
					
					@if($b % 2 == 0)
					
						var margin = {right: 106.5,bottom:20,top:60} ;
						yaxis = doc.lastAutoTable.finalY +2;
						
					@else
						var margin = {left: 106.5,bottom:20,top:60} ;
						
					@endif

				
				
				var pageNumber = doc.internal.getNumberOfPages() 
				doc.autoTable({
					headStyles:{ fillColor: '#3c8dbc', halign: 'center' },
					head: [[{content: "{!! $subject_mapping->level->year !!}", colSpan:6, styles: { valign: 'middle', halign: 'center' ,fontStyle: 'bold' }} ],], 
					body: body{{$b}},
					tableWidth: 'auto',
					styles: { whiteSpace: 'nowrap', fontSize: 7, cellPadding: 1, overflow: 'linebreak', halign : 'center' },
					startY: yaxis,
					theme: 'grid',
					margin: margin,
					columnStyles: {
				0: { columnWidth: 20 },
		        2: { columnWidth: 11 },
		        3: { columnWidth: 10 },
				4: { columnWidth: 9 },
		   		 },
					
				})
				
				doc.setPage(pageNumber)
				@php $b++; @endphp
				@endforeach


	           
	        },
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
				doc.myText("CURRICULUM", {align: "center"},0,40);	

				// AS OF {DATE}
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.setFontStyle('italic');
	            doc.myText("as of {{ Carbon\Carbon::today()->format('M. d, Y') }}", {align: "center", fontStyle: "italic"},0,44);

				//Curriculum Name
				doc.setFontSize(8);
				doc.setFontType("normal");
				doc.text("Curriculum Name: ", 14, 55);

				//Curriculum Name...
				doc.setFontType("bold");
				doc.text("{{ $curriculum_subjects->curriculum_name }}", 39, 55);



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


	    doc.save('Curriculum List {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf');
	    history.go(-1);
	</script>
</body>



</html>