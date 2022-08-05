'<div class="container-fluid" style="padding: 0 20px;" id="paymentContainer">' +
    '<form id="paymentForm" role="form" method="POST" \
    action="{{ route('online_payment.student.submit') }}">' +
        '@csrf' +
        '<input type="hidden" id="payment_method_id" name="payment_method_id" value="">' +
        '<input type="hidden" id="enrollment_id" name="enrollment_id" value="{{ $enrollment->id }}">' +
        '<input type="hidden" id="school_year_id" name="school_year_id" value="{{ $enrollment->school_year_id }}">' +
        '<input type="hidden" id="studentnumber" name="studentnumber" value="{{ $student->studentnumber }}">' +
        '<div id="email" class="form-group column one-second required" style="margin-bottom: 0;">' +
            '<label for="email">Email <span style="color: red;">*</span></label>' +
            '<input class="form-control" type="email" name="email" required placeholder="E-mail">' +
        '</div>' +
        '<div id="description" class="form-group column one required" style="margin-bottom: 0;">' +
            '<label for="address">Description</label>' +
            '<input class="form-control" type="text" name="description" placeholder="Description">' +
        '</div>' +
        '<div id="amount" class="form-group column one required" style="margin-bottom: 0;">' +
            '<label for="amount">Amount <span style="color: red;">*</span></label>' +
            '<input class="form-control" type="number" name="amount" required placeholder="Amount" onkeyup="getFee()">' +
        '</div>' +
        '<div id="fee" class="form-group column one required" style="margin-bottom: 0;">' +
            '<label for="fee">Fee</label>' +
            '<input readonly class="form-control" type="text" name="fee" \
              style="background-color: #e9ecef !important;" placeholder="₱0.00" value="">' +
        '</div>' +
        '<div id="total_amount" class="form-group column one required" style="margin-bottom: 0;">' +
            '<label for="total_amount">Total</label>' +
            '<input readonly class="form-control" type="text" name="total_amount" \
              style="background-color: #e9ecef !important;" placeholder="₱0.00" value="">' +
        '</div>' +
    '</form>' +
'</div>' +
'<div class="container-fluid" style="text-align: center; vertical-align: middle !important; display: none; height: 50px;" id="loading">' +
    '<i class="fa fa-spinner fa-spin fa-2x"></i>' +
'</div>'+
'<div class="container-fluid" style="text-align: center; vertical-align: middle !important; display: none; height: 50px;" id="processing">' +
    'Processing Payment' +
'</div>'