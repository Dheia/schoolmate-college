onmessage = function(e) {

	var employee_ids = e.data[0];
	var date_from 	 = e.data[1];
	var date_to 	 = e.data[2];
	var period 	 	 = e.data[3];

	if(employee_ids.length > 0) {
		for(var i = 0 ; i < employee_ids.length; i++) {
			(function (employee_id) {
				const Http = new XMLHttpRequest();
				const url = '/admin/api/employee-attendance/' + employee_ids[i] + '/attendance-logs?date_from=' + date_from + '&date_to=' + date_to + '&period=' + period;
				Http.open("GET", url);
				Http.send();

				Http.onreadystatechange = function () {
					if (Http.readyState == 4 && Http.status == 200) {
						postMessage({
							data: JSON.parse(Http.responseText),
							employee_id: employee_id
						});
					}

				};
			} )(employee_ids[i])
		}
	}
}
