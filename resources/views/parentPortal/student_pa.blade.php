@extends('backpack::layout_parent')

@section('header')
<style>
    
    .tg  {border-collapse:collapse;border-spacing:0;width: 100%;}
    .tg td{border-color:black;border-style:solid;border-width:2px;font-family:"Carlito",sans-serif;font-size:11.5px;
      overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:2px;font-family:"Carlito",sans-serif;font-size:11.5px;
      font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-ywcg{background-color:#ffe5c7;border-color:inherit;text-align:right;vertical-align:top}
    .tg .tg-btz9{background-color:#fcff2f;border-color:inherit;font-weight:bold;text-align:center;vertical-align:top}
    .tg .tg-1ksz{background-color:#fcff2f;border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
    .tg .tg-up1x{background-color:#ffe5c7;border-color:inherit;text-align:left;vertical-align:top}
    .tg .tg-jg6n{background-color:#fcff2f;border-color:#000000;color:#333333;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-xb5m{background-color:#ffe5c7;border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
    .tg .tg-dvpl{border-color:inherit;text-align:right;vertical-align:top}
    .tg .tg-7btt{border-color:inherit;font-weight:bold;text-align:center;vertical-align:top}
    .tg .tg-fqbu{background-color:#fcff2f;border-color:inherit;color:#333;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-fymr{border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}

	 @media only screen and (min-width: 768px) {
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
</style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
	<div class="container">

		<!-- HEADER -->
		<div class="row" style="padding: 15px;">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
				<section class="content-header">
					<ol class="breadcrumb">
						<li><a href="{{ url('parent/dashboard') }}">Dashboard</a></li>
						<li><a class="text-capitalize active">Insurance</a></li>
					</ol>
				</section>
				<h1 class="smo-content-title">
					<span class="text-capitalize"><i class="fas fa-legal"></i> Insurance Policy</span>
				</h1>
			</div>
		</div>
		<!-- END OF HEADER -->

		<div class="row">

			<div class="col-md-12 box p-b-40">
			
        <img src="https://trademarks.justia.com/media/image.php?serial=79062957" alt="" style=" width:250px; padding-bottom:2 0px;">
        <img src="https://www.gibco.com.ph/images/principals/charterpingan.png" alt="" style=" width:250px;">
        <br>

        <b style="font-family:Carlito,sans-serif;font-size:13px; ">Student PA Packages</b>
        <table class="tg" style="margin-top:20px; margin-bottom:20px; margin-left:auto; margin-right:auto;">
        <colgroup>
        <col style="width: 414px">
        <col style="width: 188px">
        </colgroup>
        <thead>
          <tr>
            <th class="tg-btz9 text-center">Basic</th>
            <th class="tg-btz9">Plan 4</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tg-btz9 text-center"><span style="font-weight:bold">BENEFITS</td>
            <td class="tg-7btt" colspan="1">MAXIMUM LIMITS PER STUDENT</td>
          </tr>
          <tr>
            <td class="tg-0pky">Accidental Death and Disablement</td>
            <td class="tg-0pky">50,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">Medical Reimbursement</td>
            <td class="tg-0pky">10,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">Murder and Assault</td>
            <td class="tg-0pky">50,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">Accident Burial Expense</td>
            <td class="tg-0pky">7,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">Bereavement Assistance due to Natural Death</td>
            <td class="tg-0pky">5,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">Daily In Hospital Benefit up to 60 days, due to accident</td>
            <td class="tg-0pky">275.00/ day</td>
          </tr>
          <tr>
            <td class="tg-0pky">100% Motorcycle Riding Cover</td>
            <td class="tg-0pky"></td>
          </tr>
        </tbody>
        </table>



        <b style="font-family:Carlito,sans-serif;font-size:13px;"><u>Annual Premium per student, tax inclusive</u></b>
        <table class="tg " style="undefined;table-layout: fixed; margin-top:10px; margin-left:auto; margin-right:auto;">
        <colgroup>
            <col style="width: 414px">
            <col style="width: 188px">
        </colgroup>
        <thead>
          <tr>
            <th class="tg-0pky" ><span style="font-weight:bold">No. of Students:</span></th>
            <th class="tg text-left" colspan="1" ></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tg-zv36"><span style="font-weight:bold">I. 50 - 250</td>
            <td class="tg-dvpl">50.00</td>
          </tr>
          <tr>
            <td class="tg-0pky"><span style="font-weight:bold">II. 251-500</span></td>
            <td class="tg-dvpl">42.50</td>
          </tr>
          <tr>
            <td class="tg-0pky"><span style="font-weight:bold">III. 501 - 1,000</span></td>
            <td class="tg-dvpl">31.25</td>
          </tr>
          <tr>
            <td class="tg-0pky"><span style="font-weight:bold">IV. 1,001 - up</span></td>
            <td class="tg-dvpl">27.50</td>
          </tr>
        </tbody>
        </table>

        <table class="tg" style="undefined;table-layout: fixed;  margin-top:30px; margin-left:auto; margin-right:auto; margin-bottom:20px;">
        <colgroup>
            <col style="width: 414px">
            <col style="width: 188px">
        </colgroup>
        <thead>
          <tr>
            <th class="tg-jg6n">Automatic Extensions of cover:</th>
            <th class="tg-btz9"><span style="font-weight:bold">Plan 4</span></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tg-xb5m">I. 50 - 250 Students</td>
            <td class="tg-ywcg" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-0pky"> 1. Tutorial Fees (Reimbursement)</td>
            <td class="tg-dvpl">1,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky" rowspan="2"> 2. Ambulance Service Fee - with Official Receipt (Covers injuries inside   the school premises and school related sports activities outside premises)</td>
            <td class="tg-dvpl" rowspan="2">1,000.00</td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="tg-0pky"> 3. Financial Assistance due to dengue (Medical Reimbursement)</td>
            <td class="tg-dvpl">2,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky" rowspan="2"><br><br><span style="font-weight:bold">Policy Aggregate Limit</span></td>
            <td class="tg-7btt" colspan="1" rowspan="2"><br><br>PHP15,000.00 per extension for Plan 1 to 4</td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="tg-fqbu">Automatic Extensions of cover:</td>
            <td class="tg-btz9">Plan 4</td>
          </tr>
          <tr>
            <td class="tg-xb5m">II. 251 - 500 Students</td>
            <td class="tg-ywcg">1,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky" rowspan="2"> 2. Ambulance Service Fee - with Official Receipt (Covers injuries inside   the school premises and school related sports activities outside premises)</td>
            <td class="tg-dvpl" rowspan="2">1,500.00</td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="tg-0pky"> 3. Financial Assistance due to dengue (Medical Reimbursement)</td>
            <td class="tg-dvpl">2,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky" rowspan="2"><br><br><span style="font-weight:bold">Policy Aggregate Limit</span></td>
            <td class="tg-7btt" colspan="1" rowspan="2"><br><br>PHP20,000.00 per extension for Plan 1 to 4</td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="tg-0pky">4. Cash Assistance ( Death due to insect bites, animal bites and sexual assault)</td>
            <td class="tg-dvpl">5,000.00</td>
          </tr>
          <tr>
            <td class="tg-7btt"><span style="font-weight:bold">Policy Aggregate Limit : Up to 10 qualified claimants only</td>
            <td class="tg-0pky" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-1ksz">Automatic Extensions of cover:</td>
            <td class="tg-btz9">Plan 4</td>
          </tr>
          <tr>
            <td class="tg-xb5m">III. 501 - 1,000 Students</td>
            <td class="tg-up1x"></td>
          </tr>
          <tr>
            <td class="tg-0pky"> 1. Tutorial Fees (Reimbursement)</td>
            <td class="tg-dvpl">1,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky"> 2. Ambulance Service Fee - with Official Receipt (Covers injuries inside the school premises and   school related sports activities ) outside premises</td>
            <td class="tg-dvpl">1,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky"> 3. Financial Assistance due to dengue (Medical Reimbursement)</td>
            <td class="tg-dvpl">2,500.00</td>
          </tr>
          <tr>
            <td class="tg-fymr" rowspan="2"><br><br><br>Policy Aggregate Limit</td>
            <td class="tg-7btt" colspan="1" rowspan="2"><br><br><br>PHP25,000.00 per extension for Plan 1 to 4   </td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="tg-0pky">4. Cash Assistance ( Death due to insect bites, animal bites and sexual assault)</td>
            <td class="tg-dvpl">5,000.00</td>
          </tr>
          <tr>
            <td class="tg-7btt"><span style="font-weight:bold">Policy Aggregate Limit : Up to 10 qualified claimants only</td>
            <td class="tg-0pky" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-0pky">5. Fire Assistance - per student</td>
            <td class="tg-dvpl">2,500.00</td>
          </tr>
          <tr>
            <td class="tg-c3ow">Maximum limit per dwelling - PHP 10,000.00</td>
            <td class="tg-0pky" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-c3ow"><span style="font-weight:bold">Policy aggregate limit - PHP 50,000.00</td>
            <td class="tg-0pky" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-1ksz">Automatic Extensions of cover:</td>
            <td class="tg-btz9">Plan 4</td>
          </tr>
          <tr>
            <td class="tg-xb5m">IV. 1,001 - up Students</td>
            <td class="tg-up1x"></td>
          </tr>
          <tr>
            <td class="tg-0pky">1. Tutorial Fees (Reimbursement)</td>
            <td class="tg-dvpl">1,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">2. Ambulance Service Fee - with Official Receipt (Covers injuries inside the school premises and school related sports  activities outside premises)</td>
            <td class="tg-dvpl">1,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">3. Financial Assistance due to dengue (Medical Reimbursement)</td>
            <td class="tg-dvpl">2,500.00</td>
          </tr>
          <tr>
            <td class="tg-0pky"></td>
            <td class="tg-7btt" colspan="1" rowspan="2"><br><br><br>PHP30,000.00 per extension for Plan 1 to 4   <br></td>
          </tr>
          <tr>
            <td class="tg-fymr"><span style="font-weight:bold">Policy Aggregate Limit</td>
          </tr>
          <tr>
            <td class="tg-0pky">4. Cash Assistance ( Death due to insect bites, animal bites and sexual assault)</td>
            <td class="tg-dvpl">5,000.00</td>
          </tr>
          <tr>
            <td class="tg-7btt"><span style="font-weight:bold">Policy Aggregate Limit : Up to 10 qualified claimants only</td>
            <td class="tg-0pky" colspan="1"></td>
          </tr>
          <tr>
            <td class="tg-0pky">5. Fire Assistance - per student</td>
            <td class="tg-dvpl">5,000.00</td>
          </tr>
          <tr>
            <td class="tg-c3ow">Maximum limit per dwelling - PHP 10,000.00</td>
            <td class="tg-0pky" colspan="1" rowspan="2"></td>
          </tr>
          <tr>
            <td class="tg-c3ow">Policy aggregate limit - PHP 50,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">6. Hospital Cash Assistance - Daily In Hospital Benefit due to sickness, maximum of 15 days - excluding pre-existing conditions</td>
            <td class="tg-dvpl">275.00/ day</td>
          </tr>
          <tr>
            <td class="tg-xb5m"><span style="font-weight:bold">PLUS: For 10,000 and up students</td>
            <td class="tg-dvpl"></td>
          </tr>
          <tr>
            <td class="tg-0pky">7. Comprehensive General / Personal Liability - PHP10,000 per student (within school premises only subject to a policy aggregate limit of PHP 100,000)</td>
            <td class="tg-dvpl">10,000.00</td>
          </tr>
          <tr>
            <td class="tg-0pky">8. Double Indemnity Benefit for Accidental Death - Additional Limit (While within school premises only)</td>
            <td class="tg-dvpl">50,000.00</td>
          </tr>
        </tbody>
        </table>
        <table style="border: none;border-collapse:collapse;">
            <tbody>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 9.2pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:8.25pt;'><strong><span style="font-size:11px;">Additional Extensions of Cover:</span></strong></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:9.3pt;'><span style="font-size:11px;">1. a.) For 50 to 500 no. of students - Free coverage for the school&apos;s teaching and non-teaching staffs (at 50% coverage of the students basic benefits).</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:33.35pt;line-height:9.3pt;'><span style="font-size:11px;">The no. of covered schools staffs should not be more than 10% of to total number of insured students.</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:19.0pt;line-height:9.3pt;'><span style="font-size:11px;">b.) For 501 and above no. of students - Free coverage for the school&apos;s teaching and non-teaching staffs (at 100% coverage of the students basic benefits).</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:33.35pt;line-height:9.3pt;'><span style="font-size:11px;">The no. of covered schools staffs should not be more than 10% of the total number of insured students.</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:9.3pt;'><span style="font-size:11px;">2. Students over 23 y/o and taking up Masteral Degrees or vocational courses are included.</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 11.9pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:9.35pt;'><span style="font-size:11px;">3. Accidental food poisoning is covered.</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 11.95pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-top:1.2pt;margin-right:0cm;margin-left:10.0pt;line-height:9.75pt;'><strong><span style="font-size:11px;">Conditions:</span></strong></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 10.3pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:9.35pt;'><span style="font-size:11px;">1. Qualification - The total no. of Insured students should not be less than 80% of the schools&apos; total student population.</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 537.15pt;padding: 0cm;height: 9.2pt;vertical-align: top;">
                        <p style='margin:0cm;margin-bottom:.0001pt;font-size:15px;font-family:"Carlito",sans-serif;margin-left:10.0pt;line-height:8.25pt;'><span style="font-size:11px;">2. Above plans are subject to a loss limit per occurrence equivalent to the TSI or PHP50,000,000.00 whichever is lower.</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
			</div>

		</div>
		
	</div>
</body>
  
@endsection


@section('after_scripts')
	<script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  	<script>
	    $(document).ready(function() {

	    });
     
  	</script>
@endsection

@section('after_styles')
  	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
