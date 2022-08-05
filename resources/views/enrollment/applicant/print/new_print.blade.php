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

	            // APPLICANT FORM
	            doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.myText("APPLICANT FORM", {align: "center"},0,35);
	            
	            // AS OF {DATE}
	            doc.setFontSize(6);
	            doc.setFontType("normal");
	            doc.myText("(SYSTEM GENERATED)", {align: "center", },0,38);
	          


				// APPLICANT FOR:
	            doc.setFontSize(8);
	            doc.setFontType("normal");
                doc.setFontType("bold");
	            doc.text("Applicant For: ", data.settings.margin.left, 45);

                //SCHOOL YEAR
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("School Year: ", data.settings.margin.left, 50);

	            // SCHOOL YEAR: 2019 - 2020
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("{{ $enrollment->schoolYear ? $enrollment->schoolYear->schoolYear : '-' }}", data.settings.margin.left + 60, 50);
                
				// DEPARTMENT
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Department: ", data.settings.margin.left, 55);

	            // DEPARTMENT: Grade School, Senior High, etc..
	            doc.setFontType("bold");
	            doc.text("{{ $enrollment->department ? $enrollment->department->name : '-' }}", data.settings.margin.left + 60, 55);

	           // LEVEL
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Level: ", data.settings.margin.left, 60);

	            // LEVEL: Grade 1, Grade 2, Grade 3, etc...
	            doc.setFontType("bold");
	            doc.text("{{ $enrollment->level ? $enrollment->level->year : '-' }}", data.settings.margin.left + 60, 60);


				//2X2 PICTURE
				doc.setLineWidth(0.1);
				doc.rect(150, 40, 45, 45);
				doc.setFontType("normal");
	            doc.text("Attach 2x2 Picture", data.settings.margin.left + 147, 65);

                //LINE -----
                doc.setLineWidth(6.0); 
                doc.line(14, 65, 140, 65,60)
				// STUDENT INFORMATION
	            doc.setFontType("normal");
                doc.setFontType("bold");
	            doc.text("STUDENT INFORMATION ", data.settings.margin.left + 50, 66);


                 // LASTNAME:
                 doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Last Name: ", data.settings.margin.left, 72);

	            // LASTNAME..
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("{{ ($student->lastname) }}", data.settings.margin.left + 45, 72);
                
				// FIRSTNAME:
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("First Name: ", data.settings.margin.left, 77);

	            // FIRSTNAME..
	            doc.setFontType("bold");
	            doc.text("{{ ($student->firstname) }}", data.settings.margin.left + 45, 77);

	           // MIDDLENAME:
	            doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Middle Name: ", data.settings.margin.left, 82);

	            // MIDDLENAME....
	            doc.setFontType("bold");
	            doc.text("{{ ($student->middlename) }}", data.settings.margin.left + 45, 82);


                 //LINE -----
                doc.setLineWidth(6.0); 
                doc.line(14, 90, 195, 90,60)
                doc.setFontType("bold");
	            doc.myText("PERSONAL INFORMATION", {align: "center", },60,91);


                // AGE:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Age: ", data.settings.margin.left, 96);

	            // AGE..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->age }}", data.settings.margin.left + 45, 96);

                 // DATEBIRTH:
                 doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Date Of Birth: ", data.settings.margin.left, 101);

	            // DATEOFBIRTH..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}", data.settings.margin.left + 45, 101);

                // PLACEOFBIRTH:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Place Of Birth: ", data.settings.margin.left, 106);

	            // PLACEOFBIRTH..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->birthplace) }}", data.settings.margin.left + 45, 106);


                // SEX:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Sex: ", data.settings.margin.left+ 115, 96);

	            // SEX..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->gender }}", data.settings.margin.left + 150, 96);

                 // CITIZENSHIP:
                 doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Citizenship: ", data.settings.margin.left+ 115, 101);

	            // CITIZENSHIP..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->citizenship) }}", data.settings.margin.left + 150, 101);

				// RELIGION:
				doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Religion: ", data.settings.margin.left+ 115, 106);

	            // RELIGION..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->religion) }}", data.settings.margin.left + 150, 106);
                

                 // RESIDENTIAL ADDRESS:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Residential Address ", data.settings.margin.left, 112);

                
	            // ADDRESSLINE:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Address Line", data.settings.margin.left + 5, 117);
                // ADDRESSLINE..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->street_number ?? '-') }}", data.settings.margin.left + 45, 117);
                
                // BARANGAY:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Barangay", data.settings.margin.left + 5, 122);
                // BARANGAY..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->barangay ?? '-') }}", data.settings.margin.left + 45, 122);

                 // CITY/MUNICIPALITY:
                 doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("City / Municipality", data.settings.margin.left + 115, 117);
                // CITY/MUNICIPALITY..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->city_municipality ?? '-') }}", data.settings.margin.left + 150, 117);
                
                // PROVINCE:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Province", data.settings.margin.left + 115, 122);
                // PROVINCE..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->province ?? '-') }}", data.settings.margin.left + 150, 122);

                //LINE -----
                doc.setLineWidth(6.0); 
                doc.line(14, 129, 195, 129,60)
                doc.setFontType("bold");
	            doc.myText("FAMILY BACKGROUND", {align: "center", },60,130);

                // MOTHER'S INFORMATION:
	            doc.setFontType("bold");
	            doc.text("MOTHER'S INFORMATION", data.settings.margin.left + 0, 135);
                // FATHER'S INFORMATION:
	            doc.setFontType("bold");
	            doc.text("FATHER'S INFORMATION", data.settings.margin.left + 95, 135);

                // LASTNAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Last Name:", data.settings.margin.left + 0, 140);
                // LASTNAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->motherlastname) }}", data.settings.margin.left + 60, 140);
                // FIRSTNAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("First Name:", data.settings.margin.left + 0, 145);
                // FIRSTNAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->motherfirstname) }}", data.settings.margin.left + 60, 145);
                // MIDDLENAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Middle Name:", data.settings.margin.left + 0, 150);
                // MIDDLENAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->mothermiddlename) }}", data.settings.margin.left + 60, 150);
                // OCCUPATION:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Occupation:", data.settings.margin.left + 0, 155);
                // OCCUPATION..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->mother_occupation) ?? '-' }}", data.settings.margin.left + 60, 155);
                // NATIONALITY:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Nationality:", data.settings.margin.left + 0, 160);
                // NATIONALITY..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->mothercitizenship ?? '-') }}", data.settings.margin.left + 60, 160);
                // CONTACT:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Contact Number:", data.settings.margin.left + 0, 165);
                // CONTACT..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->mothernumber ?? '-' }}", data.settings.margin.left + 60, 165);
                // DECEASED:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Deceased?", data.settings.margin.left + 0, 170);
                // DECEASED..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->mother_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 60, 170);


                // LASTNAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Last Name:", data.settings.margin.left + 95, 140);
                // LASTNAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->fatherlastname) }}", data.settings.margin.left + 145, 140);
                // FIRSTNAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("First Name:", data.settings.margin.left + 95, 145);
                // FIRSTNAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->fatherfirstname) }}", data.settings.margin.left + 145, 145);
                // MIDDLENAME:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Middle Name:", data.settings.margin.left + 95, 150);
                // MIDDLENAME..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->fathermiddlename) ?? '-' }}", data.settings.margin.left + 145, 150);
                // OCCUPATION:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Occupation:", data.settings.margin.left + 95, 155);
                // OCCUPATION..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->father_occupation) ?? '-' }}", data.settings.margin.left + 145, 155);
                // NATIONALITY:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Nationality:", data.settings.margin.left + 95, 160);
                // NATIONALITY..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ucwords($student->fathercitizenship ?? '-') }}", data.settings.margin.left + 145, 160);
                // CONTACT:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Contact Number:", data.settings.margin.left + 95, 165);
                // CONTACT..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->fatherMobileNumber ?? '-' }}", data.settings.margin.left + 145, 165);
                // DECEASED:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Deceased?", data.settings.margin.left + 95, 170);
                // DECEASED..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->father_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 145, 170);

                //LINE -----
                doc.setLineWidth(6.0); 
                doc.line(14, 177, 195, 177,60)
                doc.setFontType("bold");
	            doc.myText("EMERGENCY CONTACT INFORMATION", {align: "center", },60,178);

                //LEGAL GUARDIAN INFORMATION:
	            doc.setFontType("bold");
	            doc.text("LEGAL GUARDIAN INFORMATION", data.settings.margin.left + 0, 183);
               

                // FullName:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Full Name:", data.settings.margin.left + 0, 188);
                // FullName..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ ($student->legal_guardian_fullname) }}", data.settings.margin.left + 50, 188);
                // OCCUPATION:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Occupation:", data.settings.margin.left + 0, 193);
                // OCCUPATION..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->father_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 50, 193);
                // MOBILENO:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Mobile No.:", data.settings.margin.left + 0, 198);
                // MOBILENO..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->emergency_contact_number_on_record }}", data.settings.margin.left + 50, 198);
                // TELEPHONENO:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Telephone No.:", data.settings.margin.left + 0, 203);
                // TELEPHONENO..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("{{ $student->emergencyhomephone }}", data.settings.margin.left + 50, 203);

				//PERSON TO CONTACT:
				doc.setFontType("bold");
	            doc.text("PERSON TO CONTACT", data.settings.margin.left + 95, 183);
                // FullName:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Full Name:", data.settings.margin.left + 95, 188);
                // FullName..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->father_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 140, 188);
                // Relationship to the Applicant:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Relationship to the Applicant::", data.settings.margin.left + 95, 193);
                // Relationship to the Applicant..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->father_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 140, 193);
                // CONTACTNUMBER:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Contact Number:", data.settings.margin.left + 95, 198);
                // CONTACTNUMBER..
                doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("@if($student->father_living_deceased == 'deceased') Yes @else No @endif", data.settings.margin.left + 140, 198);
                // ADDRESS:
                doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Address:", data.settings.margin.left + 95, 203);
                // ADDRESS..
                doc.setFontSize(8);
	            doc.setFontType("normal");
				var splitTitle = doc.splitTextToSize("{{ ucwords($student->emergency_contact_address_on_record) }}", 45);
				doc.text(splitTitle, data.settings.margin.left + 140, 203);

				 //LINE -----
				doc.setLineWidth(6.5); 
                doc.line(14, 226, 100, 226,60)
				doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("To be checked by the Admission Staff", data.settings.margin.left + 2, 227);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("The applicant presented the following documents:", data.settings.margin.left + 0, 232.5);

				doc.setLineWidth(0.1);
				doc.rect(14, 234, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Form 138", data.settings.margin.left + 4, 236);
				doc.setLineWidth(0.1);
				doc.rect(14, 238, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Certificate of Good Moral", data.settings.margin.left + 4, 240);
				doc.setLineWidth(0.1);
				doc.rect(14, 242, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("PSA Birth Certificate", data.settings.margin.left + 4, 244);
				doc.setLineWidth(0.1);
				doc.rect(14, 246, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("ESC Certificate", data.settings.margin.left + 4, 248);
				doc.setLineWidth(0.1);
				doc.rect(14, 250, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Recommendation Letter", data.settings.margin.left + 4, 252);
				doc.setLineWidth(0.1);
				doc.rect(14, 254, 2, 2);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Transcript of Records", data.settings.margin.left + 4, 256);

				//REMARKS
				doc.setLineWidth(0.1);
				doc.rect(14, 258, 85, 18);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Remarks:", data.settings.margin.left + 3, 263);
				
                doc.setFontSize(8);
	            doc.setFontType("normal");
				var splitTitle = doc.splitTextToSize("I hereby confirm that the information stated above are true and correct. "+
				"I understand that by signing this information/application form, I hereby give the"+
				"school and SchoolMATE to collect, record, organize, update, or modify,"+
				"retrieve, consult, utilize, consolidate, block, erase, or destruct my personal"+
				"data as part of my information for historical, statistical, research and"+
				"evaluation purposes pursuant to the provisions of the Republic Act No. 10173"+
				"of the Philippines, Data Privacy Act of 2012 and its corresponding"+
				"implementing Rules and Regulations. ",90);
				doc.text(splitTitle, data.settings.margin.left + 90, 225);


				//Signature Over Printed Name
				doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("{{ ($student->fullname) }}", data.settings.margin.left + 90, 260);
				doc.setFontSize(8);
	            doc.setFontType("normal");
	            doc.text("Signature Over Printed Name", data.settings.margin.left + 90, 263);

				//DATE
				doc.setFontSize(8);
	            doc.setFontType("bold");
	            doc.text("Date: ", data.settings.margin.left + 90, 270);


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
       
        doc.save('{{Config::get('settings.schoolname')}} | Student Form {{ Carbon\Carbon::now()->format('m-d-Y') }}.pdf')
		
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