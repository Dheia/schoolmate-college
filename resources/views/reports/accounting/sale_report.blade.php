@extends('backpack::layout')

@section('header')
@endsection


@section('content')
    <!-- HEADER -->
    <div class="row" style="padding: 15px;">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
        <section class="content-header">
          <ol class="breadcrumb">
            <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
            <li><a class="text-capitalize active">Sales Report</a></li>
          </ol>
        </section>
        <h1 class="smo-content-title">
          <span class="text-capitalize">Sales Report</span>
          {{-- <small>All turnstile tap in and tap out</small> --}}
        </h1>
      </div>
    </div>
    <!-- END OF HEADER -->

    <div class="row">
      <form role="form" method="POST" action="{{ url('admin/sales-report/download') }}" >
        {!! csrf_field() !!}
        <div class="col-md-12">
          {{-- <form action="" method="GET" class="mb-3"> --}}
            <div class="box col-md-12 padding-10 p-t-20">
              <div class="form-group col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Payment Period</label>                    
                      <select name="report_name" id="reportPeriod" class="form-control">
                        <option value="today"    >Today</option>
                        <option value="this_week" >This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="custom"   >Custom</option>
                      </select>
                    </div>
                    <div class="col-md-4 start_date" >
                      <label for="start_date">Start Date</label>
                      <input id="date_from" type="date" class="form-control" name="date_from" >
                    </div>
                    <div class="col-md-4 end_date">
                      <label for="end_date">End Date</label>
                      <input id="date_to" type="date" class="form-control" name="date_to">
                    </div>
                    <div class="col-md-4">
                      <label for="">&nbsp;</label><br>
                      <a href="javascript:void(0)" id="btn-run" class="btn btn-primary w-100">Filter</a>
                    </div>
                    <div class="col-md-4">
                      <label for="">&nbsp;</label><br>
                      <button id="btn-download" type="submit" class="btn btn-block btn-success" style="display: none;">
                        Download
                    </button>
                    </div>
                 
                  
                  </div>
              </div>
            </div>
          
            
          {{-- </form> --}}
          
          <img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="display: none; margin: auto; padding-top: 20px; padding-bottom: 20px;">

          <div class="box col-md-12 padding-10 p-t-20" style="display: none;" id="payment-box">
            <div class="form-group col-md-12">
              <h5><b>Date Period:</b> <span id="date-period-text"></span></h5>
              <div class="table-responsive">
              <table id="payment-table" class="table table-striped table-bordered">
                <thead>
                  <th>STUDENT NO.</th>
                  <th>FULLNAME</th>
                  <th>CREATED BY</th>
                  <th>INVOICE NO.</th>
                  <th>DESCRIPTION</th>
                  <th>GRADE LEVEL</th>
                  <th>SCHOOL YEAR</th>
                  <th>PAYMENT FOR</th>
                  <th>PAYMENT METHOD</th>
                  <th>FEE</th>
                  <th>AMOUNT</th>
                </thead>
                <tbody></tbody>
              </table>
              </div>
            </div>
          </div>
          
            
        </div>
      </form>
    </div>

  
@endsection


@section('after_scripts')
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.start_date, .end_date').css('display', 'none');
      $('#loading-gif').css('display', 'none');
      $('#attendance').DataTable();

      $('#reportPeriod').on('change', function () {
          if(this.value == "custom") {
            $('.start_date, .end_date').css('display', 'block');
          } else {
            $('.start_date, .end_date').css('display', 'none');
          }
        });
      
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = '0' + dd;
      }

      if (mm < 10) {
        mm = '0' + mm;
      }

      today = yyyy + "-" + mm;
      $('#dateselection').val(today)
    });
     
  </script>

  <script>
    
    function busy () {
      $('#btn-run').text('...').attr('disabled', true);
      $('#loading-gif').css('display', 'block');
      $('#payment-box').css('display', 'none');
    }

    function done () {
      $('#loading-gif').css('display', 'none');
      $('#payment-box').css('display', 'block');
      $('#btn-run').text('Run').removeAttr('disabled');
      $('#btn-download').css('display', 'block');
    }

    $('#btn-run').click(function () {
    
      var date_from = $('#date_from').val();
      var date_to   = $('#date_to').val();
      var period    = $('#reportPeriod option:selected').val();
      var empty     = '';
      
      if(period == 'custom') {
        if(date_from == "" || date_to == "") {
          alert("Please Enter A Date");
          $('#payment-box').css('display', 'none');
          $('#btn-download').css('display', 'none');
          return;
        }
      }else{
        busy();
      }
        var today = moment();
       
      
        if(period =='today'){
          date_from = today.format("MMMM DD, YYYY");
          date_period = moment(date_from).format('MMMM DD, YYYY');
          $('#date-period-text').text(date_period);
          empty = 'for '+period;

        }else if(period == 'this_week'){
          date_from = today.format("MMMM DD, YYYY");
          date_to = today.add(7,'days').format("MMMM DD, YYYY");
          date_period = moment(date_from).format('MMMM DD, YYYY') + ' - ' + moment(date_to).format('MMMM DD, YYYY');
          $('#date-period-text').text(date_period);
          empty = 'for '+period;

        }else if(period == 'this_month'){
          date_from = today.clone().startOf('month').format("MMMM DD, YYYY");
          date_to = today.clone().endOf('month').format("MMMM DD, YYYY");
          date_period = moment(date_from).format('MMMM DD, YYYY') + ' - ' + moment(date_to).format('MMMM DD, YYYY');
          $('#date-period-text').text(date_period);
          empty = 'for '+period;
          
        }else{
          date_period = moment(date_from).format('MMMM DD, YYYY') + ' - ' + moment(date_to).format('MMMM DD, YYYY');
          $('#date-period-text').text(date_period);
          empty = 'for selected dates';
        }

      $.ajax({
        

				url: 'sales-report/' + period + '/payment',
				data: {
		          	date_from: date_from,
		          	date_to: date_to
		        },
				success: function (response) {
						var tableRow = '';
            var totalAmountRow = '';
            var total = 0;
						var no_data = '<tr><td colspan="11" class="text-center">No Sales '+empty+'</td></tr>';
						if(response.data != null) {
            
							response_data = response.data;
              				$.each(response.data, function (key, val) {
                   total += +val.amount;

                   var desc = val.description ? val.description : '-';
                   var fee = val.fee ? val.fee : '-';
									tableRow += '<tr>\
                           <td>{{Config::get('settings.schoolabbr')}}-' + val.enrollment.studentnumber  + '</td>\
                           <td>' + val.enrollment.full_name  + '</td>\
                           <td>' + val.user.email   + '</td>\
                           <td>' + val.invoice_no  + '</td>\
                           <td>' + desc  + '</td>\
                           <td>' + val.enrollment.level_name  + '</td>\
                           <td>' + val.enrollment.school_year_name  + '</td>\
                           <td>' + val.payment_for  + '</td>\
                           <td>' + val.payment_method.name  + '</td>\
                           <td>' + fee  + '</td>\
                           <td>' + parseFloat(val.amount).toLocaleString('en-US', {style: 'currency', currency: 'PHP'})+'</td>\
												</tr>'
                 totalAmountRow = '<tr>\
                           <td colspan="10" class="text-right" ><span style="font-weight:bold">Total Amount</span></td>\
                           <td colspan="11" ><span style="font-weight:bold">'+ total.toLocaleString('en-US', { style: 'currency', currency: 'PHP' }) +'</span></td>\
                          </tr>'
							});
						}

						$('#payment-table tbody').html(tableRow ? tableRow : no_data).append(totalAmountRow);
					
					done();
				},
		        
			});
		
    });
	// Convert 24hr Format to 12hr format
	    function convertTime (timeString) {
	      	// Check correct time format and split into components
	      	var timeString = timeString.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [timeString];

	      	if (timeString.length > 1) { // If time format correct
		        timeString = timeString.slice (1);  // Remove full string match value
		        timeString[5] = +timeString[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
		        timeString[0] = +timeString[0] % 12 || 12; // Adjust hours
	      	}
	      	return timeString.join (''); // return adjusted time or original string
	    }

		// Show Loading and Hide Student Table
	    function startLoading() {
	      	$('#payment-table').css("display", "none");
	      	$('#loading-gif').css("display", "block");
	    }

	    // Hide Loading and Show Student Table
	    function stopLoading() {
      		$('#loading-gif').css("display", "none");
	      	$('#payment-table').css("display", "block");
	    }

	     // PNotify Error
	    function pNotifyError(error = null) {
	      	$('#attendance-table tbody').html('<tr><td colspan="5" class="text-center">Something Went Wrong, Please Try To Reload The Page.</td></tr>');
	      	new PNotify({
		        title: "Error",
		        text: error ? error : 'Something Went Wrong, Please Try Again.',
		        type: 'error'
	      	});
	    }
    
  </script>
@endsection

@section('after_styles')

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
