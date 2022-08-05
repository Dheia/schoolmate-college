@extends('backpack::layout')

@section('header')
	
    <link rel="stylesheet" href="{{asset('jqwidgets/styles/jqx.base.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('jqwidgets/styles/jqx.energyblue.css')}}" type="text/css" />
   <style>
   		.total{
   			font-weight: bold;   			
   		}	
   		

   </style>
@endsection
@section('content')
	<div class="col-md-12" style="margin: auto">
		<form action="{{ url('admin/quickbooks/reporting')}}" method="post">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-md-3">  
					<div class="form-group">
						<label for="rtype">Select Report</label>
						<select name="rtype" id="rtype" class="form-control" onchange="reportFunction()">
							<option id="group" value="Account List">Account List</option>
							<option value="Aged Payable Detail">Aged Payable Detail</option>
							<option value="Aged Payables">Aged Payables</option>
							<option value="Aged Receivable Detail">Aged Receivable Detail</option>
							<option value="Aged Receivables">Aged Receivables</option>
					  		<option value="Balance Sheet">Balance Sheet</option>
					  		<option value="Cash Flow">Cash Flow</option>
					  		<option value="Customer Balance Detail">Customer Balance Detail</option>
					  		<option value="Customer Balance">Customer Balance</option>
					  		<option value="Customer Income">Customer Income</option>
					  		<option value="General Ledger">General Ledger</option>
					  		<option value="Inventory Valuation Summary">Inventory Valuation Summary</option>		  		
					  		<option value="Profit And Loss Detail">Profit And Loss Detail</option>
					  		<option value="Profit And Loss">Profit And Loss</option>
					  		<option value="Class Sales">Sales by Class Summary</option>
					  		<option value="Customer Sales">Sales by Customer</option>
					  		<option value="Customer Sales Detail">Sales by Customer Detail</option>			  		
					  		<option value="Department Sales">Sales by Department</option>
					  		<option value="Transaction List">Transaction List</option>						
					 		<option value="Trial Balance">Trial Balance</option>					
							<option value="Vendor Balance">Vendor Balance</option>
							<option value="Vendor Balance Detail">Vendor Balance Detail</option>
							<option value="Vendor Expenses">Vendor Expenses</option>					  		
						</select>
						<input type="text" name="index" class="form-control" id="index" style="display: none">												
					</div>			
				</div>

				<div class="col-md-3" id="colPeriod">  
					<div class="form-group">
						<label for="rtype">Period</label>
						<select name="rperiod" id="rperiod" class="form-control" onchange="dateFunction()">
							<option value="All Dates">All Dates</option>
							<option value="Custom">Custom</option>
							<option value="Today">Today</option>
							<option value="This Week">This Week</option>
							<option value="This Week-to-date">This Week-to-date</option>
					  		<option value="This Month">This Month</option>
					  		<option value="This Month-to-date">This Month-to-date</option>
					  		<option value="This Quarter">This Quarter</option>
					  		<option value="This Quarter-to-date">This Quarter-to-date</option>
					  		<option value="This Year">This Year</option>
					  		<option value="This Year-to-date">This Year-to-date</option>
					  		<option value="This Year-to-last-month">This Year-to-last-month</option>		  		
					  		<option value="Yesterday">Yesterday</option>					  		
					  		<option value="Last Week">Last Week</option>
					  		<option value="Last Week-to-date">Last Week-to-date</option>			  		
					  		<option value="Last Month">Last Month</option>
					  		<option value="Last Month-to-date">Last Month-to-date</option>						
					 		<option value="Last Year">Last Year</option>					
							<option value="Last Year-to-date">Last Year-to-date</option>												  		
						</select>
					</div>			
				</div>				
				<div class="col-md-2" id="colStartDate">  
					<div class="form-group">
						<label for="datefrom" id="datefromlabel">Start Date</label>
						<input type="date" name="datefrom" class="form-control" id="datefrom" onchange="startDate()">
						<input type="text" name="datefromtext" class="form-control" id="datefromtext" style="display: none">
					</div>
				</div>
				<div class="col-md-2" id="colEndDate">
					<div class="form-group">
						<label for="dateto" id="datetolabel">End Date</label>
						<input type="date" name="dateto" class="form-control" id="dateto" onchange="endDate()">
						<input type="text" name="datetotext" class="form-control" id="datetotext" style="display: none">
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col-md-3" id="coldisplayColumns">  
					<div class="form-group">
						<label for="rtype" id="displayColumnsLabel">Display Columns by</label>
						<select name="displaycolumns" id="displayColumns" class="form-control" onchange="">
							<option value="Total">Total Only</option>
							<option value="Days">Days</option>
							<option value="Week">Weeks</option>
							<option value="Month">Months</option>
							<option value="Quarter">Quarter</option>
					  		<option value="Year">Year</option>
					  		<option value="Customers">Customers</option>
					  		<option value="Vendors">Vendors</option>					  														  		
						</select>
					</div>			
				</div>
				<div class="col-md-3" id="colaccountingMethod">  
					<div class="form-group">
						<label id="accountingMethod">Accounting Method</label>
						<div class="row">
							<form>
								<fieldset id="group2">
									<div class="col-md-4">
										<div class="custom-control custom-radio">
											<input type="radio" name="accountingMethod" value="Cash" class="custom-control-input">
											<label class="custom-control-label" for="accountingMethod" id="cash">Cash</label>
										</div>										
									</div>
									<div class="col-md-4">
										<div class="custom-control custom-radio">
											<input type="radio" name="accountingMethod" value="Accrual" class="custom-control-input" checked>
											<label class="custom-control-label" for="accountingMethod" id="accrual">Accrual</label>					
										</div>
									</div>
								</fieldset>
							</form>							
						</div>
					</div>			
				</div>
				<div class="col-md-2">

						<br>
						<div class="col-md-6 pt-2">					
							<div class="form-group">
								<input type="submit" name="enter" value="Run Report" class="btn btn-success btn-block">						
							</div>
						</div>
						<div class="col-md-6 pt-2">
							<div class="form-group">
								<input type="button" id="btnprint" name="print" value="Print" class="btn btn-success btn-block">
							</div>
						</div>
					
				</div>
								
			</div>		
		</form>
		<div class="row">
			<div class="col-md-12">
				<h1>{{$title}}</h1>				
				<div id="balance_sheet" class="ml-1 mr-1"></div>					
			</div>					
		</div>
	</div>
	
@endsection

@section('after_scripts')

    <script type="text/javascript" src="{{asset('jqwidgets/jqxcore.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxdata.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxbuttons.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxscrollbar.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxlistbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxdropdownlist.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxdatatable.js')}}"></script>
    <script type="text/javascript" src="{{asset('jqwidgets/jqxtreegrid.js')}}"></script>
    
    <script>
    	var index = "{!! $x !!}";
    	if(index == null){

    	}
    	else{
    		document.getElementById("rtype").selectedIndex = index;
    	}
    	document.onload = reportFunction();
    	dateFunction();
        var check = {!! $data !!};

        if(check == "false"){
        	window.location.assign("https://www.w3schools.com");
        }
        else{
        	var getLocalization = function () {
			     var localizationobj = {};
			     
			     localizationobj.currencysymbol = "Php ";
			     localizationobj.currencysymbolposition = "before";
			     localizationobj.decimalseparator = ".";
			     localizationobj.thousandsseparator = ",";

			     return localizationobj;
			}
	        var source =
	        {
	            dataType: "json",
	            dataFields: {!! $datafield !!},
	            hierarchy:
	            {
	                root: "children"
	            },
	            
	            localData: {!! $data !!}
	        };

	        var dataAdapter = new $.jqx.dataAdapter(source);
	        
	        var cellClass = function (row, dataField, cellText, rowData) {        		
	                var cellValue = rowData[Object.keys(rowData)[0]];
	                var res = cellValue.toString();
	                var test = res.substr(0,5);
	                var test2 = test.toLowerCase();
	                if (test2.localeCompare("total")==0){                
	                    return 'total';
	                }                
	            }
	        $("#balance_sheet").jqxTreeGrid(
	            {
	               // width:  getWidth("TreeGrid"),
	                source: dataAdapter,
	                theme: 'energyblue',
	                pageable: false,
	                localization: getLocalization(),
	                columnsResize: true,
	                width: '100%',
	                ready: function()
	                {
	                    // expand row with 'EmployeeKey = 32'
	                    $("#balance_sheet").jqxTreeGrid('expandRow', 32);
	                },
	                columns: {!! $column !!}
	            });
    	}    	 
    	function startDate(){
    		document.getElementById("datefromtext").value = document.getElementById("datefrom").value;
    		document.getElementById("dateto").setAttribute("min", document.getElementById("datefrom").value);
    	}  	
    	function endDate(){
    		document.getElementById("datetotext").value = document.getElementById("dateto").value;
    		document.getElementById("datefrom").setAttribute("max", document.getElementById("dateto").value);
    	}  	
        function dateFunction(){
        	var option = document.getElementById("rperiod");
        	document.getElementById("datefrom").style.display = 'block';
    		document.getElementById("datefromlabel").style.display = 'block';
    		document.getElementById("dateto").style.display = 'block';        		
    		document.getElementById("datetolabel").style.display = 'block';
    		document.getElementById("dateto").disabled = true;
    		document.getElementById("datefrom").disabled = true;
        	if(option.value.localeCompare("All Dates") == 0){          		
        		document.getElementById("datefrom").style.display = 'none';
        		document.getElementById("datefromlabel").style.display = 'none';
        		document.getElementById("dateto").style.display = 'none';
        		document.getElementById("datetolabel").style.display = 'none';    				
        	}
        	else if(option.value.localeCompare("Custom") == 0){
        		document.getElementById("dateto").disabled = false;
    			document.getElementById("datefrom").disabled = false;
        	}
        	else if(option.value.localeCompare("Today") == 0){
        		var date = new Date();
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("This Week") == 0){
        		var date = new Date();
        		var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 0);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + diff;
        		var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 6);
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + diff;	
        	}
        	else if(option.value.localeCompare("This Week-to-date") == 0){
        		var date = new Date();
        		var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 0);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + diff;
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("This Month") == 0){
        		var date = new Date();
        		var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-01'
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + lastDay.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("This Month-to-date") == 0){
        		var date = new Date();
        		
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-01'
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("This Quarter") == 0){
        		var date = new Date();
        		var quarter = Math.floor((date.getMonth() + 3) / 3);
				var nextq;
				if (quarter == 4) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '10-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + '12-31';
				} 
				else if (quarter == 3) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '07-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + '09-31';
				}
				else if (quarter == 2) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '04-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + '06-31';
				}
				else{
					document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + '03-31';
				}
        		
        	}
        	else if(option.value.localeCompare("This Quarter-to-date") == 0){
        		var date = new Date();
        		var quarter = Math.floor((date.getMonth() + 3) / 3);
				var nextq;
				if (quarter == 4) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '10-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
				} 
				else if (quarter == 3) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '07-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
				}
				else if (quarter == 2) {
				    document.getElementById("datefrom").value = date.getFullYear() + '-' + '04-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
				}
				else{
					document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01'
				    document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
				}
        		
        	}
        	else if(option.value.localeCompare("This Year") == 0){
        		var date = new Date();
        		
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01';
        		document.getElementById("dateto").value = date.getFullYear() + '-' + '12-31';
        	}
        	else if(option.value.localeCompare("This Year-to-date") == 0){
        		var date = new Date();
        		
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01';
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("This Year-to-last-month") == 0){
        		var date = new Date();
        		var lastDay = new Date(date.getFullYear(), date.getMonth(), 0);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01';
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth())).slice(-2) + '-' + ('0' + lastDay.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("Yesterday") == 0){
        		var date = new Date();        		
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate() - 1)).slice(-2);
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + (date.getDate() - 1)).slice(-2);
        	}        	
        	else if(option.value.localeCompare("Last Week") == 0){        		
        		var weekBefore= new Date(new Date().getTime() - 60 * 60 * 24 * 7 * 1000);
        		var diff = weekBefore.getDate() - weekBefore.getDay() + (weekBefore.getDay() === 0 ? -6 : 0);
        		document.getElementById("datefrom").value = weekBefore.getFullYear() + '-' + ('0' + (weekBefore.getMonth() + 1)).slice(-2) + '-' + diff;
        		var diff = weekBefore.getDate() - weekBefore.getDay() + (weekBefore.getDay() === 0 ? -6 : 6);
        		document.getElementById("dateto").value = weekBefore.getFullYear() + '-' + ('0' + (weekBefore.getMonth() + 1)).slice(-2) + '-' + diff;	
        	}
        	else if(option.value.localeCompare("Last Week-to-date") == 0){        		
        		var weekBefore= new Date(new Date().getTime() - 60 * 60 * 24 * 7 * 1000);
        		var diff = weekBefore.getDate() - weekBefore.getDay() + (weekBefore.getDay() === 0 ? -6 : 0);
        		document.getElementById("datefrom").value = weekBefore.getFullYear() + '-' + ('0' + (weekBefore.getMonth() + 1)).slice(-2) + '-' + diff;
        		var date = new Date();
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);	
        	}
        	else if(option.value.localeCompare("Last Month") == 0){
        		var date = new Date();
        		date.setMonth(date.getMonth() - 1);
        		var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-01'
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + lastDay.getDate()).slice(-2);
        	}
        	else if(option.value.localeCompare("Last Month-to-date") == 0){
        		var date = new Date();  
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);    
        		date.setMonth(date.getMonth() - 1);  		
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-01'
        		
        	} 
        	else if(option.value.localeCompare("Last Year") == 0){
        		var date = new Date();
        		date.setYear(date.getFullYear() - 1);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01';
        		document.getElementById("dateto").value = date.getFullYear() + '-' + '12-31';
        	}
        	else if(option.value.localeCompare("Last Year-to-date") == 0){
        		var date = new Date();
        		document.getElementById("dateto").value = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        		date.setYear(date.getFullYear() - 1);
        		document.getElementById("datefrom").value = date.getFullYear() + '-' + '01-01';
        		
        	}    
        	document.getElementById("datefromtext").value = document.getElementById("datefrom").value;  
        	document.getElementById("datetotext").value = document.getElementById("dateto").value;  	
        }
        function reportFunction(){
        	var option = document.getElementById("rtype");
        	document.getElementById("coldisplayColumns").style.display = 'none';
    		document.getElementById("colaccountingMethod").style.display = 'none';
    		document.getElementById("colPeriod").style.display = 'none';
    		document.getElementById("colStartDate").style.display = 'none';
    		document.getElementById("colEndDate").style.display = 'none';
    		document.getElementById("accountingMethod").innerHTML   = 'Accounting Method';
    		document.getElementById("cash").innerHTML   = 'Cash';
    		document.getElementById("accrual").innerHTML   = 'Accrual';
    		document.getElementById("datetolabel").innerHTML   = 'End Date';
    		
        	
    		var x = document.getElementById("rtype").selectedIndex;
    		document.getElementById("index").value = x;
        	if(x == 0){        		
        		 				
        	}
        	else if(x == 5 || x == 12 || x == 13 || x == 22){
        		document.getElementById("coldisplayColumns").style.display = 'block';
	    		document.getElementById("colaccountingMethod").style.display = 'block';
	    		document.getElementById("colPeriod").style.display = 'block';
	    		document.getElementById("colStartDate").style.display = 'block';
	    		document.getElementById("colEndDate").style.display = 'block';        		      		
        	}
        	else if( x < 5 || x == 7 || x == 8 || x == 20 || x == 21){
        		document.getElementById("colPeriod").style.display = 'block';
        		document.getElementById("colEndDate").style.display = 'block';
        		document.getElementById("colaccountingMethod").style.display = 'block';
        		document.getElementById("accountingMethod").innerHTML   = 'Aging Method';
        		document.getElementById("cash").innerHTML   = 'Current';
        		document.getElementById("accrual").innerHTML   = 'Report Date';
        		document.getElementById("datetolabel").innerHTML   = 'As of';
        	}
        	else if( x == 9 || (x > 13 && x < 20)){
        		document.getElementById("colPeriod").style.display = 'block';
        		document.getElementById("colEndDate").style.display = 'block';
        		document.getElementById("colStartDate").style.display = 'block';
        		if(x != 18){
        			document.getElementById("colaccountingMethod").style.display = 'block';
        		}
        		
        	}
        	else if(x == 6){
        		document.getElementById("colPeriod").style.display = 'block';
        		document.getElementById("colEndDate").style.display = 'block';
        		document.getElementById("colStartDate").style.display = 'block';
        		document.getElementById("coldisplayColumns").style.display = 'block';
        	}
        	else if(x == 11){
        		document.getElementById("colPeriod").style.display = 'block';
        		document.getElementById("colEndDate").style.display = 'block';
        		document.getElementById("colStartDate").style.display = 'block';
        	}        	
        	document.getElementById("rperiod").selectedIndex = 10;
        	dateFunction();
        	
        }
        // $("#btnprint").jqxButton();

        // $("#btnprint").click(function () {
       
        //         var gridContent = $("#balance_sheet").jqxTreeGrid('exportData', 'html');
        //         var newWindow = window.open('', '', 'width=800, height=500'),
        //         document = newWindow.document.open(),
        //         pageContent =
        //             '<!DOCTYPE html>\n' +
        //             '<html>\n' +
        //             '<head>\n' +
        //             '<meta charset="utf-8" />\n' +
        //             '<title>jQWidgets Grid</title>\n' +
        //             '</head>\n' +
        //             '<body>\n' + gridContent + '\n</body>\n</html>';
        //         document.write(pageContent);
        //         document.close();
        //         newWindow.print();
        //  });
    </script>

@endsection

