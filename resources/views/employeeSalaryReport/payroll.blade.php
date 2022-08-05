@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">Payroll <small>{{ Carbon\Carbon::parse($first_cutoff)->format('F d, Y') . ' - ' . Carbon\Carbon::parse($second_cutoff)->format('F d, Y') }}</small></span>
      {{-- <small id="datatable_info_stack">Salary<span>asd</span>sdd</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">Payroll</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<!-- Default box -->
				
{{-- 					<div class="">
						<img class="loader-spinner" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/82/loader-spinner.png" />
						<span>1</span> / {{ count($employee_ids) }}
					</div> --}}

			  	<div class="box">

			  		<div class="box-header" style="overflow: auto;">
			  			<table id="table_id" class="table table-striped table-bordered nowrap">
		        			<thead>
		        				<th><small>Employee #</small></th>
		        				<th><small>Full Name</small></th>
		        				<th><small>Monthly</small></th>
		        				<th><small>Basic Salary</small></th>
		        				<th><small>Absent/Late</small></th>
		        				<th><small>Admin Fee</small></th>
		        				<th><small>Allowances</small></th>
		        				<th><small>Gross Pay</small></th>
		        				@if($sss) <th><small>SSS</small></th> @endif
		        				@if($sss_loan) <th><small>SSS Loan</small></th> @endif
		        				@if($philhealth) <th><small>Philhealth</small></th> @endif
		        				@if($hdmf) <th><small>HDMF</small></th> @endif
		        				@if($hdmf_loan) <th><small>PagIbig Loan</small></th> @endif
		        				@if($tax) 
		        					<th><small>Total Taxable <br>Income</small></th>
		        					<th><small>W. Tax</small></th> 
	        					@endif
		        				<th><small>Total Adjustment</small></th>
		        				<th><small>Net Pay <br>After Tax</small></th>
		        			</thead>
							{{-- 16 cols --}}
							<tbody>
								@foreach($employees as $employee)
									<tr id="employee-{{ $employee->employee_id }}">
										<td>{{ $employee->employee_id }}</td>
										<td>{{ $employee->fullname }}</td>
										<td id="initial-loading" colspan="15" class="text-center">Loading...</td>
									</tr>
								@endforeach
							</tbody>
		        		</table>
				  	</div>
			  	</div><!-- /.box -->

		</div>
	</div>

	<div id="adjustmentModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Adjustments</h4>
				</div>
				<div class="modal-body" id="adjustment-body" style="padding: 0;">
					<p>Loading...</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
@endsection

@section('after_styles')
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<style>
		.circle {
			height: 50px;
			width: 50px;
			background: #FFF;
			border-radius: 50%;
		}
		.dataTables_scrollHeadInner {
			margin: auto;
		}

		.present-without-late-and-undertime { background-color: green !important; }
		.absent { background-color: red !important;  }
		.late { background-color: yellow !important; }
		.undertime { background-color: orange !important; }
		.text-red {
			color: red;
		}
		.loader-spinner {
		  /*position: absolute;*/
		  /*width: 100%;*/
		  max-width: 20px;
		  /*left: 50%;*/
		  /*top: 50%;*/
		  /*transform: translate( -50%, -50% ); /* center */*/
		  -webkit-animation: spinLoader 600ms steps(12, end) infinite;
		          animation: spinLoader 600ms steps(12, end) infinite;
		}

		@-webkit-keyframes spinLoader {
		  from { transform: translate( -50%, -50% ) rotate(0turn); }
		  to { transform: translate( -50%, -50% ) rotate(1turn); }
		}

		@keyframes spinLoader {
		  from { transform: translate( -50%, -50% ) rotate(0turn); }
		  to { transform: translate( -50%, -50% ) rotate(1turn); }
		}

	</style>
@endsection

@section('after_scripts')
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	{{-- <script src="https://code.jquery.com/jquery-1.12.4.min.js" ></script> --}}
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="{{ asset('js/dataTables.altEditor.free.js') }}"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js" ></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" ></script> --}}
	<script>
		
	</script>

	<script>
		Array.prototype.remove = function() {
		    var what, a = arguments, L = a.length, ax;
		    while (L && this.length) {
		        what = a[--L];
		        while ((ax = this.indexOf(what)) !== -1) {
		            this.splice(ax, 1);
		        }
		    }
		    return this;
		};

		function initDataTables () {
			$(document).ready(function () {
			    var payRollTable = $('#table_id').DataTable({
			    	paging: false,
			    	scrollY: 600,
			    	dom: 'Bfrtip',
			    	scrollX:        true,
			        scrollCollapse: true,
			        altEditor: true,
			        select: 'single',
			        fixedColumns:   {
			            leftColumns: 2
			        },
			        initComplete: function(){

				    	if('UNPUBLISH' === '{{ $payroll_status }}') {
					    	$(".dt-buttons").append('<button type="button" class="dt-button buttons-adjustment" id="button-adjustment"><span>Adjustment</span></button>');
					      	$(".dt-buttons").append('<button type="button" class="dt-button buttons-publish" id="button-publish"><span>Publish</span></button>');
				      	}
				      
				      	if('PUBLISHED' === '{{ $payroll_status }}') {
				      		$(".dt-buttons").append('<button type="button" class="dt-button buttons-published" id="button-published" disabled><span>Published</span></button>');
				      	}

				      	if('CANCELED' === '{{ $payroll_status }}') {
				      		$(".dt-buttons").append('<button type="button" class="dt-button buttons-canceled" id="button-canceled"><span>Canceled</span></button>');
				      	}
				   },
			        // altEditor: true,
			        // buttons: [
			        // 	{
				       //      extend: 'selected', // Bind to Selected row
				       //      text: 'Edit',
				       //      name: 'edit'        // do not change name
				       //  }
			        //     // { extend: 'create', editor: editor },
			        //     // { extend: 'edit',   editor: editor },
			        //     // { extend: 'remove', editor: editor }
			        // ],
			    	responsive: true
			    });
				// $('td', payRollTable.fn)
				// console.log('')
				$('#button-adjustment').click(function () {
					var selectedRow = payRollTable.rows('.selected').data();
					if(selectedRow.length > 0) {
						var employeeID = selectedRow[0][0];
						$.confirm({
						    title: 'Adjustment',
						    content: '' +
						    '<form action="" class="adjustment-form">' +
							    '<div class="form-group">' +
							    	'<input type="hidden" name="_token" value="{{csrf_token()}}" />' +
							    	'<label>Amount</label>' +
							    	'<input type="text" name="amount" placeholder="Enter Amount" class="adjustment-amount form-control" required />' +
							    '</div>' +
							    '<div class="form-group">' +
							    	'<label>Description</label>' +
							    	'<input type="text" name="description" placeholder="Enter Description" class="adjustment-description form-control" required />' +
							    '</div>' +
						    '</form>',
						    buttons: {
						        formSubmit: {
						            text: 'Submit',
						            btnClass: 'btn-blue',
						            action: function () {
						                var amount = this.$content.find('.adjustment-amount').val();
						                var description = this.$content.find('.adjustment-description').val();
						                if(!amount){
						                    $.alert('Please Enter Amount');
						                    return false;
						                }
						                if(!description){
						                    $.alert('Please Enter Description');
						                    return false;
						                }

						                // Submit Adjustment
						                $.ajax({
						                	headers: {
										        "X-CSRF-TOKEN": '{{ csrf_token() }}'  //for object property name, use quoted notation shown in second
										    },
						                	url: '/admin/api/employee-salary-report/adjustment',
						                	data: {
						                		payroll_run_id: {{ $payroll_run_id }},
						                		employee_id: employeeID, 
						                		amount: amount,
						                		description: description
						                	},
						                	type: 'POST',
						                	success: function (response) {

						                		if(response.error) {
						                			$.alert({
													    title: 'Error',
													    content: response.message,
													});
						                		}
						                		var tableRow 	 = $('#employee-' + employeeID);
						                		var trAdjustment = tableRow.find('td#total_adjustment > a');
						                		var trNetPay 	 = tableRow.find('td#net_pay');

						                		trAdjustment.text( numberWithCommas( parseFloat(trAdjustment.text().replace(/,/g, '')) + parseFloat(response.data.amount) ) );
						                		trNetPay.text( numberWithCommas( parseFloat(trNetPay.text().replace(/,/g, '')) + parseFloat(response.data.amount) ) );

						                		$.alert({
												    title: 'Success',
												    content: 'Successfully Added Adjustment To Employee ID: ' + employeeID,
												});

						                	}
						                })
						            }
						        },
						        cancel: function () {
						            //close
						        },
						    },
						    onContentReady: function () {
						        // bind to events
						        var jc = this;
						        this.$content.find('form').on('submit', function (e) {
						            // if the user submits the form by pressing enter in the field.
						            e.preventDefault();
						            jc.$$formSubmit.trigger('click'); // reference the button and click it
						        });
						    }
						});
					} else {
						new PNotify({
							title: 'No Selected Row',
							text: 'Please Select a Row',
						});
					}
				});

				$('#button-publish').click(function () {
					$.ajax({
						headers: {
					        "X-CSRF-TOKEN": '{{ csrf_token() }}'  //for object property name, use quoted notation shown in second
					    },
						url: '/admin/api/employee-salary-report/publish/' + {{ $payroll_run_id }},
						type: 'POST',
						success: function  (response) {
							if(response.error) {
								new PNotify({
									text: 'Error',
									message: response.message
								});
							} 
							new PNotify({
								text: 'Success',
								message: response.message
							});
							$('#button-publish').text('Published').attr('disabled', true);
							$('#button-adjustment').remove();
						}
					});
				})
			});
		}
	</script>

	<script>
		
		const first = 100;
		const second = 100;
		const employee_ids = {{ json_encode($employee_ids) }};
		const total_employees = {{ count($employee_ids) }};

		var incremental = 0;
		if (window.Worker) {
			const myWorker = new Worker("/js/employeeSalaryReportWebWorker.js");

			myWorker.postMessage([
				employee_ids,
				'{{ $first_cutoff }}', 
				'{{ $second_cutoff }}', 
				{{ $tax }}, 
				{{ $sss }}, 
				{{ $philhealth }}, 
				{{ $hdmf }}, 
				'{{ $tax_type }}', 
				'{{ $sss_type }}', 
				'{{ $philhealth_type }}', 
				'{{ $hdmf_type }}',
				{{ $sss_loan }},
				{{ $hdmf_loan }},
				'{{ $sss_loan_type }}',
				'{{ $hdmf_loan_type }}',
				'{{ $payroll_id }}',
				'{{ $payroll_run_id }}',
				'{{ csrf_token() }}'
			]),
		
			myWorker.onmessage = function(e) {

				// Remove To Array Of EmployeeID
				employee_ids.remove(e.data.employee_id);
				tdTag = "";
				if(e.data.data.items !== null) {
					item = e.data.data.items;
					employee = e.data.data.employee;

					tdTag += "<td id='monthly'>" + numberWithCommas( item.basic_salary.toFixed(2) ) + "</td>";
					tdTag += "<td id='basic_salary'>" + numberWithCommas( (item.basic_salary).toFixed(2) / 2 ) + "</td>";
					tdTag += "<td id='absent_late' class='text-red'>" + numberWithCommas( item.total_late_and_absent.toFixed(2) ) + "</td>";
					tdTag += "<td id='admin_fee'>" + numberWithCommas( item.admin_pay.toFixed(2) ) + "</td>";
					tdTag += "<td id='monthly_allowances'>" + numberWithCommas( item.other_pay.toFixed(2) ) + "</td>";
					tdTag += "<td id='gross_pay'>" + numberWithCommas( item.gross_pay.toFixed(2) ) + "</td>";

					@if($sss) tdTag += "<td id='sss' class='text-red'>" + numberWithCommas( item.government_services.sss ) + "</td>"; @endif
					@if($sss_loan) 
						if(item.hasOwnProperty('government_services')) {
							if(item.government_services.hasOwnProperty('loans')) {
								tdTag += "<td id='sss-loan' class='text-red'>" + numberWithCommas( item.government_services.loans.sss.toFixed(2) ) + "</td>"; 
							}
						}
					@endif

					@if($philhealth) tdTag += "<td id='philhealth' class='text-red'>" + numberWithCommas( item.government_services.philhealth.toFixed(2) ) + "</td>"; @endif
					
					@if($hdmf) tdTag += "<td id='hdmf' class='text-red'>" + numberWithCommas( item.government_services.hdmf.toFixed(2) ) + "</td>"; @endif
					@if($hdmf_loan) 
						if(item.hasOwnProperty('government_services')) {
							if(item.government_services.hasOwnProperty('loans')) {
								tdTag += "<td id='hdmf-loan' class='text-red'>" + numberWithCommas( item.government_services.loans.hdmf.toFixed(2) ) + "</td>"; 
							}
						}
					@endif


					@if($tax) 
						tdTag += "<td id='total_taxable_income'>" + numberWithCommas( item.government_services.taxable_income.toFixed(2) ) + "</td>";
						tdTag += "<td id='tax'>" + numberWithCommas( item.government_services.tax.toFixed(2) ) + "</td>"; 
					@endif
					tdTag += "<td id='total_adjustment'><a href='javascript:void(0)' onclick='viewAdjustment({{ $payroll_run_id }}, " + employee.employee_id + ")'>" + numberWithCommas( item.total_adjustment.toFixed(2) ) + "</a></td>";
					tdTag += "<td id='net_pay'>" + numberWithCommas( item.net_pay.toFixed(2) ) + "</td>";

					incremental += 1;
				} else {
					tdTag += "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
					@if($sss)  tdTag += "<td></td>"; @endif
					@if($philhealth)  tdTag += "<td></td>"; @endif
					@if($hdmf)  tdTag += "<td></td>"; @endif
					@if($tax)  tdTag += "<td></td><td></td>"; @endif

					@if($sss_loan)  tdTag += "<td></td>"; @endif
					@if($hdmf_loan)  tdTag += "<td></td>"; @endif
					incremental += 1;
				}

				$('#employee-' + e.data.employee_id).find('#initial-loading').remove();
				$('#employee-' + e.data.employee_id).append(tdTag);

				if(total_employees == incremental) {
					$("td[id='initial-loading']").closest('tr').append('<td class="text-center" colspan="11"><span style="color: red;">Failed To Load</span></td>');
					$("td[id='initial-loading']").remove()
					initDataTables();	
				}
			}
		} else {
			console.log('Your browser doesn\'t support web workers.')
		}

		function viewAdjustment (id, employee_id) {
			$.ajax({
				headers: {
			        "X-CSRF-TOKEN": '{{ csrf_token() }}'  //for object property name, use quoted notation shown in second
			    },
				type: 'POST',
				url: '/admin/api/employee-salary-report/get-adjustment/' + id +'/' + employee_id,
				success: function (response) {
					$('#adjustmentModal').modal('show');

					var tableRow = "";

					if(response.length < 1) {
						$('#adjustment-body').html('<span style="padding: 10px;">No Adjustment Found</span');
						return;
					}

					$.each(response, function (key, val) {
						tableRow += '<tr>\
										<td>' + val.amount + '</td>\
										<td>' + val.description + '</td>\
									</tr>'
					});

					var table = "<table class='table table-bordered' style='margin: 0;'>\
									<thead>\
										<th>Amount</th>\
										<th>Description</th>\
									</thead>\
									<tbody>\
										 " + tableRow + "\
									</tbody>\
								</table>";

					$('#adjustment-body').html(table);


				} 
			});
		}

		$('#adjustmentModal').on('hidden.bs.modal', function (e) {
		  	$('#adjustment-body').html('Loading...');
		})
	</script>
@endsection

