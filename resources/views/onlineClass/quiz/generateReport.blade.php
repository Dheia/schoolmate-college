<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Enrolment Applicant</title>
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
				var question_number = 0;
				var question_count = 70;
				var multiple_c = 0;
				
				

	   			var doc = new jsPDF();
       			var body = 	[
					
				
	        		];
					

              
	    doc.autoTable({
            headStyles:{
	    		fillColor: '#3c8dbc'
	    	},
            
	    	
	        // BODY
	        body: body,
			tableWidth: 'wrap',
	        styles: {
	        	whiteSpace: 'nowrap',
	        	fontSize: 7,
	        	cellPadding: 1
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
				doc.setFillColor(60, 141, 188);
				doc.rect(0, 30, 300, 10, 'F')

				doc.setTextColor(255, 255, 255);
	            // APPLICANT FORM
	            doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.myText("Quiz FORM", {align: "center"},0,35);
	            
	            // AS OF {DATE}
	            doc.setFontSize(6);
	            doc.setFontType("normal");
	            doc.myText("(SYSTEM GENERATED)", {align: "center", },0,38);
				

				doc.setTextColor(0, 0, 0);
				// APPLICANT FOR:
	            doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.setFontType("bold");
	            doc.text("Quiz Title: {{$quiz->title}}", data.settings.margin.left, 45);

                //SCHOOL YEAR
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Description: {{$quiz->description}}", data.settings.margin.left, 50);

				//SCHOOL YEAR
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Question: {{$quiz->total_questions}}", data.settings.margin.left, 55);

				//SCHOOL YEAR
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Points: {{$quiz->total_score}}", data.settings.margin.left, 60);

				@foreach($quiz->questions as  $quizs)
					question_number ++;
					@if($quizs['question_type'] == 'choose_one')
						doc.setFontSize(8);
						doc.setFontType("normal");
						doc.text(question_number+". {{$quizs['title']}} ({{$quizs['points']}} points)",data.settings.margin.left, question_count);
						@foreach($quizs['choices'] as  $choices)
							doc.circle(15, question_count+4, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['a']}}", 20, question_count+5);
							doc.circle(15, question_count+9, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['b']}}", 20, question_count+10);
							doc.circle(15, question_count+14, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['c']}}", 20, question_count+15);
							doc.circle(15, question_count+19, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['d']}}", 20, question_count+20);

						@endforeach
						question_count += 30;
					@elseif($quizs['question_type'] == 'choose_many')
						doc.setFontSize(8);
						doc.setFontType("normal");
						doc.text(question_number+". {{$quizs['title']}} ({{$quizs['points']}} points)", data.settings.margin.left,question_count);
						
						@foreach($quizs['choices'] as  $choices)
							doc.circle(15, question_count+4, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['a']}}", 20, question_count+5);
							doc.circle(15, question_count+9, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['b']}}", 20, question_count+10);
							doc.circle(15, question_count+14, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['c']}}", 20, question_count+15);
							doc.circle(15, question_count+19, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("{{$choices['d']}}", 20, question_count+20);

						@endforeach

						question_count += 30;
						

					@elseif($quizs['question_type'] == 'true_false')
						doc.setFontSize(8);
						doc.setFontType("normal");
						doc.text(question_number+". {{$quizs['title']}} ({{$quizs['points']}} points)", data.settings.margin.left,question_count);
							
							doc.circle(15, question_count+4, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("True", 20, question_count+5);
							doc.circle(15, question_count+9, 1.5);
							doc.setFontSize(8);
							doc.setFontType("normal");
							doc.text("False", 20, question_count+10);
						question_count += 15;

					@elseif($quizs['question_type'] == 'fill_blank')
						doc.setFontSize(8);
						doc.setFontType("normal");
						doc.text(question_number+". {{$quizs['title']}} ({{$quizs['points']}} points)", data.settings.margin.left,question_count);
						doc.line(18, question_count+5, 70, question_count+5);
						question_count += 15;

					@elseif($quizs['question_type'] == 'essay')
						doc.setFontSize(8);
						doc.setFontType("normal");
						doc.text(question_number+". {{$quizs['title']}} ({{$quizs['points']}} points) - essay	", data.settings.margin.left,question_count);
						doc.line(18, question_count+5, 100, question_count+5);
						doc.line(18, question_count+10, 100, question_count+10);
						question_count += 25;

					@endif
				@endforeach
				
				

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
			
					top: 74,
					bottom: 30,
				
			}

        });
       
        doc.save('{{$quiz->title}} | Quiz Form {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf')
		
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}

		setCookie('redirect',0,1);
		
		history.go(-1);
		
        </script>
</body>