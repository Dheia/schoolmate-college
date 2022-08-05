onmessage = function(e) {

	var employee_ids = e.data[0];
	var from = e.data[1];
	var to = e.data[2];

	var tax = e.data[3];
	var sss = e.data[4];
	var philhealth = e.data[5];
	var hdmf = e.data[6];

	var tax_type = e.data[7];
	var sss_type = e.data[8];
	var philhealth_type = e.data[9];
	var hdmf_type = e.data[10];

	var sss_loan = e.data[11];
	var hdmf_loan = e.data[12];
	var sss_loan_type = e.data[13];
	var hdmf_loan_type = e.data[14];
	var payroll_id = e.data[15];
	var payroll_run_id = e.data[16];
	var token = e.data[17];

	if(employee_ids.length > 0) {
		for(var i = 0 ; i < employee_ids.length; i++) {
			(function (employee_id) {
				const Http = new XMLHttpRequest();
				var data = new FormData();
					data.append('from', from);
					data.append('to', to);
					data.append('tax', tax);
					data.append('sss', sss);
					data.append('philhealth', philhealth);
					data.append('hdmf', hdmf);
					data.append('tax_type', tax_type);
					data.append('sss_type', sss_type);
					data.append('philhealth_type', philhealth_type);
					data.append('hdmf_type', hdmf_type);
					data.append('sss_loan', sss_loan);
					data.append('hdmf_loan', hdmf_loan);
					data.append('sss_loan_type', sss_loan_type);
					data.append('hdmf_loan_type', hdmf_loan_type);
					data.append('payroll_id', payroll_id);
					data.append('payroll_run_id', payroll_run_id);

				const url = '/admin/api/employee-salary-report/run/' + employee_id;
				Http.open("POST", url, true);
				Http.setRequestHeader("X-CSRF-TOKEN", token);
				Http.send(data);
				
				Http.onreadystatechange = function () {
					if (Http.readyState == 4 && Http.status == 200) {
						postMessage({
							_token: token,
							data: JSON.parse(Http.responseText),
							employee_id: employee_id
						});
					}
				};
			})(employee_ids[i])
		}
	}
}
