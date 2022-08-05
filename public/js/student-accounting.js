$('.box-footer').remove();
            // $('.box-footer').html('<a href=" ' + location.protocol + '//' + window.location.host + '/admin/student-accounts" class="btn btn-default">Back</a>')
            var stud_acc = '';
            window.paymentHistory = '';

            function initSearch () {
                window.stud = $("#search").getSelectedItemData();
                $('#student-account').html('');

                $.ajax({
                    url: window.location.protocol + '//' + location.host + "/admin/api/student-account?student_no=" + stud.studentnumber + "&school_year_id=" + stud.schoolyear + "&grade_level_id=" + stud.level_id,
                    success: function (data) {
                        if(data === undefined || data.length != 0) {
                            window.d = data;
                            getStudentAccount(data, stud);
                            // checkPaymentIsSet(data);
                            getPayment(data, stud);
                        } else {
                            $('#student-account').html('Student Account Not Found');
                        }
                    }
                });
            }

            var other_program_total = 0.00;
            var options = {
                url:  window.location.protocol + '//' + location.host + '/admin/api/tuitions-list',
                getValue: function (element) {
                    return element.studentnumber + ' ' + element.firstname + ' ' + element.lastname;
                },
                list: {
                    // maxNumberOfElements: 1,
                    onSelectItemEvent: function() {
                        other_program_total = 0.00;
                        var data = $("#search").getSelectedItemData();
                        $("#student_number").val(parseInt(data.studentnumber)).trigger("change");

                    },
                    onKeyEnterEvent: function () {
                        initSearch();
                    },
                    onClickEvent: function() {
                        initSearch();
                    },
                    match: {
                        enabled: true
                    }
                },
            };
            $("#search").easyAutocomplete(options); 

            $(document).ready(function () {
                $('#program_description').on('change', function () {
                    var amount = $('option:selected', this).attr('program-amount');
                    $('#program_amount').val(accounting.formatMoney(amount, 'P', 2, ', '));
                });
            })

            function getStudentAccount (student, stud_info) {
                var paymentType = '';
                var discount = '';
                var t_payable_fees = '';
                var t_payable_fees2 = '';
                var amount = '';

                var t_mandatory_fees = '';
                var t_amount = 0.00;

                  /****************************************/
                 /*************** MISC FEES **************/
                /****************************************/                
                var str = '';
                $.each(JSON.parse(student[0].miscellaneous), function(k, val) {
                    if(val.code.length > 40) {
                        str = val.code.substring(0, 40) + "...";
                    } else {
                        str = val.code;
                    }

                    amount += '<tr>\
                                    <td>' +  str + '</td>\
                                    <td id="td-pymttype-1">' + accounting.formatMoney(val.amount, 'P', 2, ', ') +'</td>\
                                    <td id="td-pymttype-2">' + accounting.formatMoney(val.amount, 'P', 2, ', ') +'</td>\
                                    <td id="td-pymttype-3">' + accounting.formatMoney(val.amount, 'P', 2, ', ') +'</td>\
                                    <td id="td-pymttype-4">' + accounting.formatMoney(val.amount, 'P', 2, ', ') +'</td>\
                                </tr>';

                        t_amount += parseFloat(val.amount);
                });

                  /****************************************/
                 /************* TUITION FEES *************/
                /****************************************/

                var t_payable_fees2_cash = 0;
                var t_payable_fees2_semi= 0;
                var t_payable_fees2_quarterly = 0;
                var t_payable_fees2_monthly = 0;

                $.each(JSON.parse(student[0].tuition_fees), function(k, val) {
                    paymentType += '<td id="td-pymttype-' + val.payment_type + '">' + accounting.formatMoney(val.tuition_fees, 'P', 2, ', ') + '</td>';
                    discount    += '<td id="td-pymttype-' + val.payment_type + '">' + accounting.formatMoney(val.discount, 'P', 2, ', ') + '</td>';

                    t_payable_fees += '<td id="td-pymttype-' + val.payment_type + '"><b>' + accounting.formatMoney(parseFloat(val.tuition_fees) - parseFloat(val.discount), 'P', 2, ', ') + '</b></td>';

                    t = parseFloat(val.tuition_fees) - parseFloat(val.discount) ;

                    t_payable_fees2 +=  '<td id="td-pymttype-' + val.payment_type + '"><b>' + accounting.formatMoney(t + parseFloat(t_amount), 'P', 2, ', ') + '</b></td>';

                    if(val.payment_type == 1) {
                        t_payable_fees2_cash = t + parseFloat(t_amount);

                    } else if (val.payment_type == 2) {
                        t_payable_fees2_semi = t + parseFloat(t_amount);
                    } else if (val. payment_type == 3) {
                        t_payable_fees2_quarterly = t + parseFloat(t_amount);
                    } else {
                        t_payable_fees2_monthly = t + parseFloat(t_amount);
                    }


                    if(student[0].selected_payment !== null) {
                        $('#set_payment_type').attr('disabled', true);
                        $('#setPaymentButton').attr('disabled', true);
                    }
                });


                  /******************************************/
                 /************* PAYMENT SCHEME *************/
                /******************************************/

                var p_scheme = '';
                var p_scheme_semi_amnt = 0;
                var p_scheme_quarterly_amnt = 0;
                var p_scheme_monthly_amnt = 0;

                $.each(JSON.parse(student[0].payment_scheme), function(k, val) {
                    p_scheme += '<tr>\
                                    <td>' + val.scheme_date + '</td>\
                                    <td id="td-pymttype-1">P0.00</td>\
                                    <td id="td-pymttype-2">' + accounting.formatMoney(val.semi_amount, 'P', 2, ', ') + '</td>\
                                    <td id="td-pymttype-3">' + accounting.formatMoney(val.quarterly_amount, 'P', 2, ', ') + '</td>\
                                    <td id="td-pymttype-4">' + accounting.formatMoney(val.monthly_amount, 'P', 2, ', ') + '</td>\
                                 </tr>';

                     p_scheme_semi_amnt += parseFloat(val.semi_amount);
                     p_scheme_quarterly_amnt += parseFloat(val.quarterly_amount);
                     p_scheme_monthly_amnt += parseFloat(val.monthly_amount);

                });

                var acc = '<div class="row"><div class="form-group col-md-10">\
                                <select id="set_payment_type" class="form-control" onchange="watchPayment()" required>\
                                    <option value="" selected disabled>Select Payment</option>\
                                    <option value="1">Cash</option>\
                                    <option value="2">Semi-Annual</option>\
                                    <option value="3">Quarterly</option>\
                                    <option value="4">Monthly</option>\
                                </select><br>\
                            </div>\
                            <div class="col-md-2">\
                                <a href="#" id="setPaymentButton" class="btn btn-info btn-block" onclick="setPayment()">Set Payment</a>\
                            </div>\
                            </div>\
                            <a href="#" class="btn btn-primary" id="addPayment" data-toggle="modal" disabled>\
                            Add Payment&nbsp;\
                            <i class="fa fa-money"></i>\
                            </a>\
                            <a href="#" class="btn btn-success" id="addPOtherProgram" data-toggle="modal" disabled>Add Other Program</a>\
                            <br><br>\
                            ' + studentBasicInfo(student, stud_info) + '\
                            <table id="tuition-table" class="table-striped" style="width: 100%">\
                                <thead style="background-color: #42286C;">\
                                    <th style="color: #FFF; padding: 5px;">Mandatory Fees</th>\
                                    <th style="color: #FFF; padding: 5px;" id="td-pymttype-1">Cash</th>\
                                    <th style="color: #FFF; padding: 5px;" id="td-pymttype-2">Semi-Annual</th>\
                                    <th style="color: #FFF; padding: 5px;" id="td-pymttype-3">Quarterly</th>\
                                    <th style="color: #FFF; padding: 5px;" id="td-pymttype-4">Monthly</th>\
                                </thead>\
                                <tbody>\
                                    <tr>\
                                        <td>Tuition Fees</td>\
                                        ' + paymentType + '\
                                    </tr>\
                                     <tr>\
                                        <td>Less : Early Bird Discount</td>\
                                        ' + discount + '\
                                    </tr>\
                                    <tr style="border-top: 2px solid #42286C; border-bottom: 2px solid #42286C;">\
                                        <td><b>Total Payable Upon Enrollment</b></td>\
                                        ' + t_payable_fees + '\
                                    </tr>\
                                    ' + amount + '\
                                    <tr style="border-top: 2px solid #42286C; border-bottom: 2px solid #42286C;">\
                                        <td><b>Total Mandatory Fees Upon Enrollment</b></td>\
                                        ' + t_payable_fees2 + '\
                                    </tr>\
                                    ' + p_scheme + '\
                                    <tr style="border-top: 2px solid #42286C; border-bottom: 2px solid #42286C;">\
                                        <td><b>Total Installments</b></td>\
                                        <td id="td-pymttype-1" ><b>-</b></td>\
                                        <td id="td-pymttype-2"><b>' + accounting.formatMoney(p_scheme_semi_amnt, 'P', 2, ', ') + '</b></td>\
                                        <td id="td-pymttype-3"><b>' + accounting.formatMoney(p_scheme_quarterly_amnt, 'P', 2, ', ') + '</b></td>\
                                        <td id="td-pymttype-4"><b>' + accounting.formatMoney(p_scheme_monthly_amnt, 'P', 2, ', ') + '</b></td>\
                                    </tr>\
                                    <tr id="grand-total">\
                                        <td><b>Grand Total</b></td>\
                                        <td id="td-pymttype-1" class="td-grand-cash" grand-total="' + t_payable_fees2_cash + '"><b>' + accounting.formatMoney(t_payable_fees2_cash, 'P', 2, ', ') + '</b></td>\
                                        <td id="td-pymttype-2" class="td-grand-semi" grand-total="' + (t_payable_fees2_semi + p_scheme_semi_amnt) + '"><b>' + accounting.formatMoney(t_payable_fees2_semi + p_scheme_semi_amnt, 'P', 2, ', ') + '</b></td>\
                                        <td id="td-pymttype-3" class="td-grand-quartely" grand-total="' + (t_payable_fees2_quarterly + p_scheme_quarterly_amnt) + '"><b>' + accounting.formatMoney(t_payable_fees2_quarterly + p_scheme_quarterly_amnt, 'P', 2, ', ') + '</b></td>\
                                        <td id="td-pymttype-4" class="td-grand-monthly" grand-total="' + (t_payable_fees2_monthly + p_scheme_monthly_amnt) + '"><b>' + accounting.formatMoney(t_payable_fees2_monthly + p_scheme_monthly_amnt, 'P', 2, ', ') + '</b></td>\
                                    </tr>\
                                </tbody>\
                            </table>';

                $('#student-account').html(acc);

            }


            function studentBasicInfo (acc, stud) {
                var profile = '';

                profile += '<table class="table table-bordered">\
                                <tbody>\
                                    <tr>\
                                        <td><b>Name:</b> ' + stud.firstname + ' ' + stud.lastname + '</td>\
                                        <td><b>Grade/Level:</b> ' + acc[0].year_management.year + '</td>\
                                        <td><b>Grade/Level:</b> ' + acc[0].school_year.schoolYear + '</td>\
                                    </tr>\
                                </tbody>\
                            </table>';

                return profile;
            }

            function showAll ()
            {
                $('#td-pymttype-1, #td-pymttype-2, #td-pymttype-3, #td-pymttype-4').css('display', 'block');
            }

            function watchPayment () {
                var payment_id = $('#set_payment_type').val();
                var hidePayment = [];
                for(var i = 1; i <= 4; i++) {
                    if(i != payment_id) {
                         hidePayment.push('#td-pymttype-' + i);   
                    }
                }
                showAll();
                $(hidePayment.join(', ')).css('display', 'none');
            };


            function getPayment(data, student) {
                $('#tr-paymentHistory').remove();
                $('#tr-remainingBalance').remove();
                $('.tr-mypayments').remove();
                $('#tr-otherPrograms').remove();
                $('.myOtherProgram').remove();
                $('#totalOtherPrograms').remove();

                var g_id = data[0].grade_level_id;  
                var sy_id = data[0].schoolyear_id;  
                var stud_no = student.studentnumber; 
                var pymntHstry = ''; 
                window.paymentHistory = '';

                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/get-payment?student_no=' + stud_no + '&school_year_id=' + sy_id + '&grade_level_id=' + g_id,
                    success: function (payments) {  
                        var pymt_id = payments.selected_payment_type.payment_type_id;
                        var remaining_balance = 0;

                        checkPaymentIsSet(pymt_id);

                        // OTHER PROGRAMS
                        var tr = '';
                        $("#program_description option").removeAttr('disabled');
                        $.each(payments.other_programs, function (key, val) {
                            if(payments.other_programs.length > 0) {

                                $("#program_description").children("option[value='" + val.id + "']").attr('disabled', true);

                                other_program_total += parseFloat(val.amount);

                                tr += '<tr class="myOtherProgram">\
                                        <td>' + val.name + '</td>\
                                        <td>' + accounting.formatMoney(val.amount, 'P', 2, ', ') + '</td>\
                                      </tr>';

                            }
                        });
                        $('#grand-total').after('<tr><td style="padding-top: 30px;"></td></tr><tr id="tr-otherPrograms" style="background-color: #42286C;">\
                                                    <th style="color: #FFF; padding: 5px;">Other Program(s)</th>\
                                                    <th style="color: #FFF; padding: 5px;">Amount</th>\
                                                </tr>' + tr + '\
                                                <tr id="totalOtherPrograms" style="border-top: 2px solid #42286C; border-bottom: 2px solid #42286C;">\
                                                    <td><b>Total Other Programs</b></td>\
                                                    <td><b>' + accounting.formatMoney(other_program_total, 'P', 2, ', ') + '</b></td>\
                                                </tr>');

                        // PAYMENT HISTORY
                        $.each(payments.payment_history, function(k, val) {
                            window.paymentHistory +=    '<tr class="tr-mypayments">\
                                                            <td> ' + moment(val.created_at).format("MMM DD YY") + '</td>\
                                                            <td>' + accounting.formatMoney(val.amount, 'P', 2, ', ') + '</td>\
                                                        </tr>';
                            remaining_balance += parseFloat(val.amount);
                        }); 

                        var grandTotal = $('#grand-total #td-pymttype-' + pymt_id).attr('grand-total');

                        $('#totalOtherPrograms').after(
                                '<tr><td style="padding-top: 30px;"></td></tr><tr id="tr-paymentHistory" style="background-color: #42286C;">\
                                    <th style="color: #FFF; padding: 5px;">Payment History</th>\
                                    <th style="color: #FFF; padding: 5px;">Amount</th>\
                                </tr>' 
                                + window.paymentHistory +
                                '<tr id="tr-remainingBalance" remaining-balance="' + ( (parseFloat(grandTotal) + other_program_total) - remaining_balance ) + '" style="border-top: 2px solid #42286C; border-bottom: 2px solid #42286C;">\
                                    <td><b>Remaining Balance</b></td>\
                                    <td><b>' + accounting.formatMoney((parseFloat(grandTotal) + other_program_total) - remaining_balance, 'P', 2, ', ') + '</b></td>\
                                </tr>'
                            );

                        if(payments.selected_payment_type !== null) {
                            $('#addPayment').removeAttr('disabled').attr("data-target", "#addPaymentModal");
                            $('#addPOtherProgram').removeAttr('disabled').attr("data-target", "#addOtherProgramModal");
                        }
                        
                    }
                });
            }

            function checkPaymentIsSet (pmt_id) {
                    $('#set_payment_type').val(pmt_id).attr('disabled', true);
                    $('#setPaymentButton').remove();
                    watchPayment();
            }



            function setPayment() {
                    if($('#set_payment_type').val() == null) {
                        $.alert({
                            title: 'Error!',
                            content: 'Please Select Payment Type.',
                        });
                        return false;
                    }
                    $.confirm({
                        title: 'Warning!',
                        content: 'Once you submit this payment type, this cannot be undone.',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    type: 'post',
                                    data: { 
                                        payment_type_id: $('#set_payment_type').val(),
                                        'student_id': stud.studentnumber,
                                        'grade_level_id': stud.level_id, 
                                        'school_year_id': stud.schoolyear, 
                                    },
                                    url: window.location.protocol + '//' + location.host + '/admin/api/selected-payment-type/save',
                                    success: function (data) {
                                        // var b = JSON.parse(data);
                                        if(data.status == "SUCCESS") {
                                            $('#set_payment_type').attr("disabled", true);
                                            $('#setPaymentButton').remove();

                                            $('#addPayment').attr('disabled', false).attr("data-target", "#addPaymentModal");
                                            $('#addPOtherProgram').attr('disabled', false).attr("data-target", "#addOtherProgramModal");
                                        } 
                                        $.confirm({
                                            title: data.status,
                                            content: data.message,
                                            buttons: {
                                                ok: function () {}
                                            }
                                        });
                                    }
                                });
                            },
                            cancel: function () {}
                        }
                    });
                    
            }



            function savePayment() {

                var remainingBalance = parseFloat($('#tr-remainingBalance').attr('remaining-balance'));

                if($('#payment_method').val() == null) {
                    $.confirm({
                        title: 'Error',
                        content: 'Please Choose a payment method',
                        buttons: {
                            confirm: function () {}
                        }
                    });
                    return false;
                }
                if($('#payment_amount').val() <= remainingBalance.toFixed(2)) {

                    $('button[onclick="savePayment()"').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: window.location.protocol + '//' + location.host + '/admin/api/add-payment/save',
                        data: {
                            'payment_type_id': $('#payment_method').val(),
                            'student_id': stud.studentnumber,
                            'grade_level_id': stud.level_id, 
                            'school_year_id': stud.schoolyear, 
                            'amount': $('#payment_amount').val(), 
                        },
                        success: function (data) {
                            $('#addPaymentModal').modal('toggle');

                            if(data.status == "SUCCESS") { getPayment(window.d, window.stud); }

                            $.confirm({
                                title: data.status,
                                content: data.message,
                                buttons: {
                                    confirm: function () {}
                                }
                            });

                            $('#payment_amount').val('');
                            $('button[onclick="savePayment()"').attr("disabled", false);
                        }
                    });
                } 
                else {
                    $.confirm({
                        title: "Error",
                        content: "Your amount is bigger than the grand total, please try again...",
                        buttons: {
                            ok: function () {}
                        }
                    });
                }
            }


            function saveOtherProgram () {
                $('button[onclick="saveOtherProgram()"').attr("disabled", true);
                $.ajax({
                    type: 'post',
                    url: window.location.protocol + '//' + location.host + '/admin/api/add-other-program/save',
                    data: {
                        'payment_type_id': $('#set_payment_type').val(),
                        'other_program_id': $('#program_description').val(),
                        'student_id': stud.studentnumber,
                        'grade_level_id': stud.level_id, 
                        'school_year_id': stud.schoolyear, 
                    },
                    success: function (data) {
                        $('#addOtherProgramModal').modal('toggle');

                        if(data.status == "SUCCESS") { 
                            getPayment(window.d, window.stud); 
                        }

                        $.confirm({
                            title: data.status,
                            content: data.message,
                            buttons: {
                                confirm: function () {}
                            }
                        });

                        $('button[onclick="saveOtherProgram()"').attr("disabled", false);
                    }
                });
            }