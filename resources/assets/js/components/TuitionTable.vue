<template>
  <div class="col-md-12" id="tuitionTabe">
    <!-- CONTENT INFORMATION -->
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="info-box shadow">
          <div class="box-body" style="padding-top:25px;">
            <div class="col-md-1 col-lg-1" style="overflow: hidden;text-overflow: ellipsis;">
              <span class="info-box-text text-info">Tuition Form</span>
              <span class="info-box-number" >  {{ tuition_name ? tuition_name : '-' }}</span>
            </div>
            <div class="col-md-1 col-lg-1">
              <span class="info-box-text text-info">Student ID</span>
              <span class="info-box-number">{{ student.studentnumber ? student.studentnumber : '-' }}</span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Full Name</span>
              <span class="info-box-number">{{ student.fullname ? student.fullname : '-' }}</span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Department:</span>
              <span class="info-box-number" >{{ enrollment ? enrollment.department_name : '-' }}</span>
            </div>
            <div class="col-md-2 col-lg-2">
              <span class="info-box-text text-info">Level:</span>
              <span class="info-box-number">{{ enrollment ? enrollment.level_name : '-' }}</span>
            </div>
            <div class="col-md-2 col-lg-2">
              <span class="info-box-text text-info">Track:</span>
              <span class="info-box-number">{{ enrollment ? enrollment.track_name : '-' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>  
    <!-- END OF CONTENT INFORMATION -->
    
    <!-- Loading GIF -->
    <div class="row" v-if="isLoading">
      <div class="col-md-12 col-lg-12">
          <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
      </div>
    </div>

    <!-- GENERAL TUITION INFORMaTION -->  
    <div class="row" v-if="!isLoading">
      <!-- BUTTON GROUP-->
      <div class="col-md-3 col-lg-3" style="float: right;">
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button v-if="balance !== 0" :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addPayment">
              <span>Make Payment</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button v-if="balance !== 0" :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addSpecialDiscount">
              <span>Apply Special Discount</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addOtherProgram">
              <span>Add Other Program</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addOtherServices">
              <span>Add Other Service</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addAdditionalFee">
              <span>Add Additional Fee</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button :disabled="btnDisabled" type="button" class="btn btn-success small-box shadow w-100" data-toggle="modal" data-target="#addDiscrepancy">
              <span>Make Adjustment</span>
            </button>
        </div>
        <div class="sa-btn col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-success small-box shadow w-100" :id="'btnSendSoa'" data-toggle="modal" data-target="#sendSOA" data-backdrop="static" data-keyboard="false">
              <span>Send SOA</span>
            </button>
        </div>
      </div> 
      <!-- END OF BUTTON GROUP -->

      <!-- TF INFORMATION -->
      <div class="col-md-9 col-lg-9">
        <div class="info-box shadow tf-form">
          <div class="box-body" style="padding-top:25px;"> 
            <!-- TF COL 1 -->
            <div class="col-md-6 col-lg-6">

              <!-- MANDATORY FEES UPON ENROLLMENT -->
              <table id="tuition-table" class="tf-table table-striped">
                <thead class="thead">
                  <tr>
                      <th >Mandatory Fees Upon Enrollment</th>
                      <th class="tf-amount">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                      <td>Tuition Fees</td>
                      <td :id="'td-pymttype-' + tuition.payment_type"
                          v-for="(tuition, index) in tuition.tuition_fees" 
                          v-show="commitment_payment_id == tuition.payment_type"
                          v-if="tuition.payment_type == index + 1">
                            P{{ tuition.tuition_fees | formatNumber }}
                          </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td>Less : Early Bird Discount</td>
                      <td :id="'td-pymttype-' + tuition.payment_type" 
                            v-for="(tuition, index) in tuition.tuition_fees"
                            v-show="commitment_payment_id == tuition.payment_type"
                            v-if="tuition.payment_type == index + 1">
                              P{{ tuition.discount | formatNumber }}
                      </td>
                      <!-- <td></td> -->
                  </tr>
                  <tr class="tf-total">
                      <td ><b>Total Payable Upon Enrollment</b></td>
                      <td><b>P{{ getTotalPayableUponEnrollment | formatNumber }}</b></td>
                  </tr>
                </tbody>
              </table>

              <!-- MISCELLANEOUS FEES -->
              <table class="tf-table table-striped">
                <thead class="thead">
                  <tr id="tr-miscellaneousFees" class="thead">
                      <th>Miscellaneous Fees</th>
                      <th class="tf-amount">Amount</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="activity in tuition.miscellaneous">
                    <td>{{ activity.description }}</td>
                    <td>P{{ activity.amount | formatNumber }}</td>
                  </tr>
                  <tr class="tf-total">
                    <td><b>Total Miscellaneous Fees</b></td>
                    <td><b>P{{ tuition.total_miscellaneous | formatNumber }}</b></td>
                  </tr>
                </tbody>
              </table>

              <!-- ACTIVITY FEES -->
              <table class="tf-table table-striped">
                <thead class="thead">
                  <tr id="tr-activities-fee" class="thead">
                      <th>
                        Activity Fees
                      </th>
                      <th class="tf-amount">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="activity in tuition.activities_fee">
                    <td>{{ activity.description }}</td>
                    <td>P{{ activity.amount | formatNumber }}</td>
                  </tr>
                  <tr v-if="!tuition.activities_fee">
                      <td colspan="3" class="text-center"><b><h4>No Activity Fees</h4></b></td>
                  </tr>
                  <tr class="tf-total">
                    <td><b>Total Activity Fees</b></td>
                    <td><b>P{{ tuition.total_activities | formatNumber }}</b></td>
                  </tr>
                </tbody>
              </table>

              <!-- OTHER FEES -->
              <table class="tf-table table-striped">
                <thead class="thead">    
                  <tr id="tr-activities-fee"  class="thead">
                      <th>Other Fees</th>
                      <th class="tf-amount">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="other in tuition.other_fees">
                    <td>{{ other.description }}</td>
                    <td>P{{ other.amount | formatNumber }}</td>
                  </tr>
                  <tr v-if="!tuition.other_fees">
                      <td colspan="3" class="text-center"><b><h4>No Other Fees</h4></b></td>
                  </tr>
                  <tr class="tf-total"> 
                    <td><b>Total Other Fees</b></td>
                    <td><b>P{{ tuition.total_other_fees | formatNumber }}</b></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- END OF TF COL 1 -->

            <!-- TF COL 2 -->            
            <div class="col-md-6 col-lg-6">
             
              <table class="tf-table table-striped">
                <thead>
                  <!-- PAYMENT SCHEME -->
                  <tr id="tr-payment-scheme" class="thead">
                      <th>
                        Tuition Fee (Payment Scheme)
                      </th>
                      <th class="tf-amount">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="pment_scheme in tuition.payment_scheme">
                      <td>{{ pment_scheme.scheme_date }}</td>
                      <td>P{{pment_scheme[commitment_payment_snake] | formatNumber}}</td>
                      <!-- <td id="td-pymttype-1" v-show="commitment_payment_id == 1">P0.00</td>
                      <td id="td-pymttype-2" v-show="commitment_payment_id == 2">P{{ pment_scheme.semi_amount | formatNumber }}</td>
                      <td id="td-pymttype-3" v-show="commitment_payment_id == 3">P{{ pment_scheme.quarterly_amount | formatNumber }}</td>
                      <td id="td-pymttype-4" v-show="commitment_payment_id == 4">P{{ pment_scheme.monthly_amount | formatNumber }}</td> -->
                  </tr>
                  <!-- TOTAL INSTALLMENTS -->
                  <tr class="tf-total">
                      <td><b>Total Tuition Fees</b></td>
                      <td v-show="commitment_payment_id == 1" ><b>-</b></td>
                      <td v-for="installment in tuition.total_installment"
                          v-show="commitment_payment_id == installment.payment_type" 
                          v-if="installment.payment_type !== 1">
                        <b>P{{ installment.amount | formatNumber }}</b>
                      </td>
                  </tr>

                  <!-- END OF TOTAL INSTALLMENTS -->
                  </tbody>
              </table>

               <!-- ACCOUNT SUMAMRY -->
              <table class="tf-table table-striped">
                <thead class="thead">
                  <tr class="thead">
                    <th><b>Account Summary</b></th>
                    <th class="tf-amount"></th>
                 </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>School Year</td>
                    <td>{{ schoolYear }}</td>
                  </tr>
                  <tr>
                    <td>Term Type</td>
                    <td id="gradeLevel" v-if="enrollment !== null">{{ enrollment.term_type }}</td>
                  </tr>
                  <tr>
                    <td>Term</td>
                    <td v-if="enrollment !== null">{{ enrollment.term }}</td>
                  </tr>

                  <tr>
                    <td>Commitment Payment</td>
                    <td class="tf-amount" id="td-pymttype-1" v-if="commitment_payment_id == 1">Cash</td>
                      <td class="tf-amount" id="td-pymttype-2" v-else-if="commitment_payment_id == 2">Semi-Annual</td>
                      <td class="tf-amount" id="td-pymttype-3" v-else-if="commitment_payment_id == 3">Quarterly</td>
                      <td class="tf-amount" id="td-pymttype-4" v-else-if="commitment_payment_id == 4">Monthly</td>
                      <td class="tf-amount" id="td-pymttype-4" v-else></td>
                  </tr>
                  <tr>
                    <td>Total Mandatory  Fees Upon Enrollment</td>
                    <td><b>P{{ getTotalMandatoryFeesUponEnrollment | formatNumber }}</b></td> 
                  </tr>
                  <tr>
                      <td>Remaining Balance</td>
                      <td v-show="commitment_payment_id == 1" ><b>-</b></td>
                      <td v-for="installment in tuition.total_installment"
                          v-show="commitment_payment_id == installment.payment_type" 
                          v-if="installment.payment_type !== 1">
                        <b>P{{ installment.amount | formatNumber }}</b>
                      </td>
                  </tr>
                  <tr>
                    <td><b>Grand Total</b></td>
                      <td v-for="(grandTotal, index) in tuition.grand_total"
                          v-if="grandTotal.payment_type == index + 1"
                          v-show="commitment_payment_id == grandTotal.payment_type"
                          :id="'td-pymttype-' + grandTotal.payment_type">
                          <b>P{{ grandTotal.amount + totalSelectedOtherProgram + totalAdditionalFee | formatNumber }}</b>
                      </td>
                    </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
      <!-- END OF TF INFORMATION -->

    </div>

    <!-- REMAINING FEES -->
    <div class="row" v-if="!isLoading">
      <div class="col-md-9 col-lg-9">
        <div class="info-box shadow tf-form">
          <div class="box-body" style="padding-top:25px;">  
            <div class="col-md-12 col-lg-12">
              <table class="tf-table table-striped">
                <!-- OTHER PROGRAMS -->
                <thead id="tr-otherPrograms" class="thead">
                  <tr>
                    <th>Other Program(s)</th>
                    <th>Amount</th>
                    <th>Remaining Balance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="selected_other_programs.length > 0" 
                      v-for="selectedOtherProgram in selected_other_programs">
                    <td>
                      <table class="tf-table-table table m-b-0">
                        <tbody>
                          <tr>
                            <td>
                              <span class="text-lowercase">{{ selectedOtherProgram.other_program.name }}</span>&nbsp;&nbsp;
                            </td>
                            <td>
                              <small>{{ selectedOtherProgram.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td>
                              <small v-if="selectedOtherProgram.user !== null"><i class="fa fa-user"></i> {{ selectedOtherProgram.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td>
                              <a v-if="selectedOtherProgram.invoice_no == null" v-on:click="addInvoiceOtherProgram(selectedOtherProgram.id)" href="javascript:void(0)" class="btn btn-xs btn-primary tf-btn"><small><i class="fa fa-plus"></i> &nbsp; Add to QB </small></a>
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success tf-btn" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>
                              
                              <a v-if="selectedOtherProgram.invoice_no == null" v-on:click="deleteInvoiceOtherProgram(selectedOtherProgram.id)" href="javascript:void(0)" class="btn btn-xs btn-default tf-btn"><small><i class="fa fa-trash"></i>&nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td v-if="selected_other_programs.length > 0"> 
                      P{{ selectedOtherProgram.other_program.amount | formatNumber }} 
                    </td>
                    <td>
                      P{{ remainingBalanceOfOtherProgram(selectedOtherProgram.id, selectedOtherProgram.other_program.amount) | formatNumber }}
                    </td>
                  </tr>

                  <tr v-if="selected_other_programs.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Other Programs</h4></b></td>
                  </tr>

                  <tr id="totalOtherPrograms" style="border-top: 2px solid #3c8dbc;">
                      <td><b>Total Other Programs</b></td>
                      <td><b>P{{ totalSelectedOtherProgram | formatNumber }}</b></td>
                      <td></td>
                  </tr>
                  <!-- END OF OTHER PROGRAMS -->
                </tbody>
              </table>
            </div>
          </div>

          <div class="box-body" style="padding-top:25px;">  
            <div class="col-md-12 col-lg-12">
              <table class="tf-table table-striped">
                  <!-- OTHER SERVICES -->
                <thead class="thead">
                  <tr id="tr-otherServices" class="thead">
                      <th>Other Service(s) </th>
                      <th>Amount</th>
                      <th>Remaining Balance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="selected_other_services.length > 0" 
                      v-for="selectedOtherService in selected_other_services">
                    <td>
                      <table class="tf-table-table m-b-0">
                        <tbody>
                          <tr>
                            <td style="vertical-align: middle; padding: 0; width: 35%">
                              <span class="text-lowercase">{{ selectedOtherService.other_service.name }}</span>&nbsp;&nbsp;
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small>{{ selectedOtherService.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small v-if="selectedOtherService.user !== null"><i class="fa fa-user"></i> {{ selectedOtherService.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td style="vertical-align: middle; width: 25%;" class="py-3">
                              <a v-if="selectedOtherService.invoice_no == null" v-on:click="addInvoiceOtherService(selectedOtherService.id)" href="javascript:void(0)" class="btn btn-xs btn-primary"><small><i class="fa fa-plus"></i> &nbsp; Make Invoice</small></a> 
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>
                              
                              <a v-if="selectedOtherService.invoice_no == null" v-on:click="deleteInvoiceOtherService(selectedOtherService.id)" href="javascript:void(0)" class="btn btn-xs btn-danger"><small><i class="fa fa-trash"></i> &nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td> 
                      P{{ selectedOtherService.other_service.amount | formatNumber }} 
                    </td>
                    <td>
                      P{{ remainingBalanceOfOtherService(selectedOtherService.id, selectedOtherService.other_service.amount) | formatNumber }}
                    </td>
                  </tr>

                  <tr v-if="selected_other_services.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Other Services</h4></b></td>
                      <td></td>
                  </tr>

                  <tr id="totalOtherServices" style="border-top: 2px solid #3c8dbc;">
                      <td><b>Total Other Services</b></td>
                      <td><b>P{{ totalSelectedOtherService | formatNumber }}</b></td>
                      <td></td>
                  </tr>
                  <!-- END OF OTHER SERVICES -->
                </tbody>
              </table>
            </div>
          </div>

          <div class="box-body" style="padding-top:25px;">  
            <div class="col-md-12 col-lg-12">

              <table class="tf-table table-striped">
                <thead class="thead">
                  <!-- ADDITIONAL FEES -->
                  <tr id="tr-additionalFees" class="thead">
                      <th>Additional Fee(s) </th>
                      <th>Amount</th>
                      <th>Remaining Balance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="additional_fees.length > 0" 
                      v-for="additionalFee in additional_fees">
                    <td>
                      <table class="tf-table-table m-b-0">
                        <tbody>
                          <tr>
                            <td style="vertical-align: middle; padding: 0; width: 35%">
                              <span class="text-lowercase">{{ additionalFee.description }}</span>&nbsp;&nbsp;
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small>{{ additionalFee.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small v-if="additionalFee.user !== null"><i class="fa fa-user"></i> {{ additionalFee.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td style="vertical-align: middle; width: 25%;" class="py-3">
                              <a v-if="additionalFee.invoice_no == null" v-on:click="addInvoiceAdditionalFee(additionalFee.id)" href="javascript:void(0)" class="btn btn-xs btn-primary">
                                <small><i class="fa fa-plus"></i> &nbsp; Make Invoice</small>
                              </a> 
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>
                              
                              <a v-if="additionalFee.invoice_no == null" v-on:click="deleteInvoiceAdditionalFee(additionalFee.id)" href="javascript:void(0)" class="btn btn-xs btn-danger"><small><i class="fa fa-trash"></i> &nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td> 
                      P{{ additionalFee.amount | formatNumber }} 
                    </td>
                    <td>
                      P{{ remainingBalanceOfAdditionalFee(additionalFee.id, additionalFee.amount) | formatNumber }}
                    </td>
                  </tr>
                  <tr v-if="additional_fees.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Additional Fees</h4></b></td>
                      <td></td>
                  </tr>
                  <tr id="totalOtherServices" style="border-top: 2px solid #3c8dbc;">
                      <td><b>Total Additional Fee</b></td>
                      <td><b>P{{ totalAdditionalFee | formatNumber }}</b></td>
                      <td></td>
                  </tr>
                  <!-- END OF ADDITIONAL FEES -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="info-box shadow tf-form">
          <div class="box-body" style="padding-top:25px;">  
            <div class="col-md-12 col-lg-12">

              
             <!--  <table class="tf-table table-striped">
                <thead class="thead">

                  <tr id="grand-total">
                      <td><b>Grand Total</b></td>
                      <td v-for="(grandTotal, index) in tuition.grand_total"
                          v-if="grandTotal.payment_type == index + 1"
                          v-show="commitment_payment_id == grandTotal.payment_type"
                          :id="'td-pymttype-' + grandTotal.payment_type">
                          <b>P{{ grandTotal.amount + totalSelectedOtherProgram + totalAdditionalFee | formatNumber }}</b>
                      </td>
                      <td></td> -->
                      <!--                         <td id="td-pymttype-1" class="td-grand-cash" grand-total="98791.58"><b>P98, 791.58</b></td>
                      <td id="td-pymttype-2" class="td-grand-semi" grand-total="107753.86"><b>P107, 753.86</b></td>
                      <td id="td-pymttype-3" class="td-grand-quartely" grand-total="112235"><b>P112, 235.00</b></td>
                      <td id="td-pymttype-4" class="td-grand-monthly" grand-total="116716.11"><b>P116, 716.11</b></td> -->
                 <!--  </tr>

                  <tr>
                      <td colspan="3" style="padding-top: 30px;"></td>
                  </tr>
                </thead>
              </table> -->

              <table class="tf-table table-striped">
                <thead class="thead">

                  <!-- DISCREPANCY --> 
                  <tr id="tr-adjustment"  class="thead">
                      <th >Adjustment (Less)</th>
                      <th >Amount</th>
                      <th ></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="tr-mypayments"
                      v-if="discrepancies.length > 0"
                      v-for="discrepancy in discrepancies">
                    <td>
                      <table class="tf-table-table m-b-0">
                        <tbody>
                          <tr>
                            <td style="vertical-align: middle; padding: 0; width: 35%">
                              <span class="text-lowercase">{{ discrepancy.description }}</span>&nbsp;&nbsp;
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small>{{ discrepancy.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small v-if="discrepancy.user !== null"><i class="fa fa-user"></i> {{ discrepancy.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td style="vertical-align: middle; width: 25%;" class="py-3">
                              <a v-if="discrepancy.invoice_no == null" v-on:click="addInvoiceDiscrepancy(discrepancy.id)" href="javascript:void(0)" class="btn btn-xs btn-primary"><small><i class="fa fa-plus"></i>&nbsp; Make Invoice</small></a>
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>

                              <a v-if="discrepancy.invoice_no == null" v-on:click="deleteInvoiceDiscrepancy(discrepancy.id)" href="javascript:void(0)" class="btn btn-xs btn-danger"><small><i class="fa fa-trash"></i>&nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td>P{{ discrepancy.amount | formatNumber }}</td>
                    <td></td>
                  </tr>
                  <tr v-if="discrepancies.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Adjustment</h4></b></td>
                  </tr>
                  <tr id="totalDiscrepancy" style="border-top: 2px solid #3c8dbc;">
                    <td><b>Total Adjustment</b></td>
                    <td><b>P{{ totalDiscrepancy | formatNumber }}</b></td>
                    <td></td>
                  </tr>
                  <!-- END OF DISCREPANCY -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="box-body" style="padding-top:25px;">  
            <div class="col-md-12 col-lg-12">


              <table class="tf-table table-striped">
                <thead class="thead">
                  <!-- SPECIAL DISCOUNT -->
                  <tr id="tr-specialDiscount" class="thead">
                      <th >Special Discount (Less)</th>
                      <th >Amount</th>
                      <th ></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="tr-mypayments"
                      v-if="special_discount_lists.length > 0"
                      v-for="specialDiscount in special_discount_lists">
                    <td>
                      <table class="tf-table-table table m-b-0">
                        <tbody>
                          <tr>
                            <td style="vertical-align: middle; padding: 0; width: 35%">
                              <span class="text-lowercase">{{ specialDiscount.description }}</span>&nbsp;&nbsp;
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small>{{ specialDiscount.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%">
                              <small v-if="specialDiscount.user !== null"><i class="fa fa-user"></i> {{ specialDiscount.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td style="vertical-align: middle; width: 25%;" class="py-3">
                              <a v-if="specialDiscount.invoice_no == null" v-on:click="addInvoiceSpecialDiscount(specialDiscount.id)" href="javascript:void(0)" class="btn btn-xs btn-primary"><small><i class="fa fa-plus"></i>&nbsp; Make Invoice</small></a>
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>

                              <a v-if="specialDiscount.invoice_no == null" v-on:click="deleteInvoiceSpecialDiscount(specialDiscount.id)" href="javascript:void(0)" class="btn btn-xs btn-danger"><small><i class="fa fa-trash"></i>&nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td>P{{ specialDiscount.amount | formatNumber }}</td>
                    <td></td>
                  </tr>
                  <tr v-if="special_discount_lists.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Special Discounts</h4></b></td>
                  </tr>
                  <tr id="totalSpecialDiscount" style="border-top: 2px solid #3c8dbc;">
                    <td><b>Total Special Discount</b></td>
                    <td><b>P{{ totalSpecialDiscount | formatNumber }}</b></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>

              <table class="tf-table table-striped">
                <thead class="thead">
                  <!-- PAYMENT HISTORY -->
                  <tr id="tr-paymentHistory"  class="thead">
                      <th >Payment History (Less)</th>
                      <th >Amount</th>
                      <th ></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="tr-mypayments"
                      v-if="payment_history.length > 0"
                      v-for="payment in payment_history">
                    <td> 
                      <table class="tf-table-table m-b-0">
                        <tbody>
                          <tr>
                            <td style="vertical-align: middle; padding: 0; width: 35%">
                              <div style="display: inline-block;">
                                <b><i :class="'fa ' + payment.payment_method.icon"></i>&nbsp; {{ payment.payment_method.name }}</b>
                              </div>
                              <div style="display: inline-block;">&nbsp; - &nbsp;{{ payment.payment_for }}</div> 
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%;">
                              <small>{{ payment.created_at | moment("ddd, MMM. DD, YYYY | hh:mm a") }}</small>
                            </td>
                            <td style="vertical-align: middle; padding: 0; width: 20%;">
                              <small v-if="payment.user !== null"><i class="fa fa-user"></i> {{ payment.user.name }}</small>
                              <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                            </td>
                            <td style="vertical-align: middle; width: 25%;" class="py-3">
                              <a v-if="payment.invoice_no == null" v-on:click="addInvoicePayment(payment.id)" href="javascript:void(0)" class="btn btn-xs btn-primary"><small><i class="fa fa-plus"></i> &nbsp; Make Invoice</small></a>
                              <a v-else href="javascript:void(0)" class="btn btn-xs btn-success" disabled><small><i class="fa fa-plus"></i> &nbsp; Invoiced</small></a>

                              <a v-if="payment.invoice_no == null" v-on:click="deleteInvoicePayment(payment.id)" href="javascript:void(0)" class="btn btn-xs btn-danger"><small><i class="fa fa-trash"></i>&nbsp; Delete</small></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>                        
                    </td>
                    <td>P{{ payment.amount | formatNumber }}</td>
                    <td></td>
                  </tr>
                  <tr v-if="payment_history.length == 0">
                      <td colspan="3" class="text-center"><b><h4>No Payment History</h4></b></td>
                  </tr>
                  <tr id="totalPaymentHistory" style="border-top: 2px solid #3c8dbc;">
                    <td><b>Total Payment History</b></td>
                    <td><b>P{{ totalPaymentHistory | formatNumber }}</b></td>
                    <td></td>
                  </tr>

                </tbody>
              </table>
                  <!-- REMAINING BALANCE -->
                  <tr id="remainingBalance" class="pull-right" >
                      <td><h4>Remaining Balance:   </h4></td>
                      <td v-for="grandTotal in tuition.grand_total"
                          v-show="commitment_payment_id == grandTotal.payment_type">
                          <h3><b>P{{ balance | formatNumber }}</b></h3>
                      </td>
                      <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- END OF BOX-HEADER DIV -->

 

      <!-- MODAL ADD PAYMENT -->
      <div id="addPayment" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Payment</h5>

            </div>
            <div class="modal-body">

                <!-- PAYMENT METHOD TYPE -->
                <div class="form-group">
                  <label for="paymentMethod">Select Payment Method</label>
                  <v-select v-model="payment_method_selected" label="name" :options="payment_method_lists" :reduce="paymentMethod => paymentMethod.id" placeholder="Choose a payment method"></v-select>
                </div>
                <!-- PAYMENT METHOD TYPE -->

                <!-- CASH -->
                <!-- v-if="payment_method_selected == 1" -->
                <!-- <div  class="cash"> -->
                  <div class="form-group">
                    <label for="paymentFor">Payment For</label>
                    <select @change="paymentFor($event)" v-model="paymentCash.paymentFor" id="paymentFor" class="form-control">
                      <optgroup label="Enrollment">
                        <option value selected>Tuition And Other Fee</option>
                      </optgroup>
                      <optgroup label="Other Programs">
                        <!-- <option v-if="otherProgram.invoice_no !== null && remainingBalanceOfOtherProgram(otherProgram.id, otherProgram.other_program.amount) > 0"  -->
                        <option v-if="remainingBalanceOfOtherProgram(otherProgram.id, otherProgram.other_program.amount) > 0" 
                              :value="otherProgram.id + '|OtherProgram'" 
                               v-for="otherProgram in selected_other_programs">
                          {{ otherProgram.other_program.name }} (PHP {{ remainingBalanceOfOtherProgram(otherProgram.id, otherProgram.other_program.amount) }} )
                        </option>
                      </optgroup>
                      <optgroup label="Other Services">
                        <!-- <option v-if="otherService.invoice_no !== null && remainingBalanceOfOtherService(otherService.id, otherService.other_service.amount) > 0"  -->
                        <option v-if="remainingBalanceOfOtherService(otherService.id, otherService.other_service.amount) > 0" 
                              :value="otherService.id + '|OtherService'" 
                               v-for="otherService in selected_other_services">
                          {{ otherService.other_service.name }} (PHP {{ remainingBalanceOfOtherService(otherService.id, otherService.other_service.amount) }} )
                        </option>
                      </optgroup>
                      <optgroup label="Additional Fees">
                        <!-- <option v-if="additionalFee.invoice_no !== null && remainingBalanceOfOtherService(additionalFee.id, additionalFee.additional_fee.amount) > 0"  -->
                        <option v-if="remainingBalanceOfAdditionalFee(additionalFee.id, additionalFee.amount) > 0" 
                              :value="additionalFee.id + '|AdditionalFee'" 
                               v-for="additionalFee in additional_fees">
                          {{ additionalFee.description }} (PHP {{ remainingBalanceOfAdditionalFee(additionalFee.id, additionalFee.amount) }} )
                        </option>
                      </optgroup>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="amountInput">Amount</label>
                    <input type="number" min="1" id="amountInput" v-model="paymentCash.amountInput" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="cashDescription">Description</label>
                    <textarea id="cashDescription" v-model="paymentCash.description" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="cashDate">Date Received</label>
                    <input id="cashDate" type="date" v-model="paymentCash.dateReceived" class="form-control">
                  </div>
                <!-- </div> -->
            </div>
            <div class="modal-footer" v-if="payment_method_selected !== null">
              <button :disabled="btnDisabled" type="button" v-on:click="pay" class="btn btn-primary">Submit Payment</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div> 


      <!-- MODAL ADD OTHER PROGRAM -->
      <div id="addOtherProgram" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Other Program</h5>
            </div>
            <div class="modal-body">
              <form action="">

                <div class="form-group">
                  <label for="paymentMethod">Select Other Program</label>
                  <v-select v-if="other_program_lists.length > 0" v-model="selected_other_program_id" label="name" :items="other_program_lists" :options="other_program_lists" :reduce="otherProgram => otherProgram.id" placeholder="Choose a other program">
                    <template slot="option" slot-scope="option">
                        {{ option.name }} (P{{ option.amount }})
                    </template>
                  </v-select>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button :disabled="btnDisabled" type="button" v-on:click="addOtherProgram" class="btn btn-primary">Add</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL ADD OTHER SERVICES -->
      <div id="addOtherServices" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Other Service</h5>
            </div>
            <div class="modal-body">
              <form action="">

                <div class="form-group">
                  <label for="paymentMethod">Select Other Service</label>
                  <select v-model="selected_other_service_id" id="selectOtherService" class="form-control">
                    <option  selected disabled>Please Select Other Service</option>
                    <option :value="otherService.id" v-for="otherService in other_service_lists">
                      {{ otherService.name }} <b>(P{{ otherService.amount | formatNumber }})</b>
                    </option>
                  </select>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button :disabled="btnDisabled" type="button" v-on:click="addOtherService" class="btn btn-primary">Add</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL SPECIAL DISCOUNT -->
      <div id="addSpecialDiscount" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Special Discount</h5>
            </div>
            <div class="modal-body">
              <form action="">
                <div class="form-group">
                  <label for="discountCategory">Category</label>
                  <select v-model="specialDiscount.discountCategory" class="form-control">
                    <option value="Discount">Discount</option>
                    <option value="Grant">Grant</option>
                  </select> 
                  <!-- <input type="number" id="amountInput" min="1"  v-model="specialDiscount.discountType" class="form-control"> -->
                </div>
                
                <div class="form-group">
                  <label for="sp-description">Description</label>
                  <textarea id="sp-description" v-model="specialDiscount.description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                  <label for="sp-applyTo">Apply To</label>
                  <select id="sp-applyTo" v-model="specialDiscount.applyTo" class="form-control">
                    <option v-for="option in specialDiscount.applyToOptions" v-bind:value="option.value">{{ option.text }}</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="sp-discountType">Type</label>
                  <select id="sp-discountType" v-model="specialDiscount.discountType" class="form-control" required>
                    <option value="Amount">Amount</option>
                    <option value="Percentage">Percentage</option>
                  </select> 
                  <!-- <input type="number" id="amountInput" min="1"  v-model="specialDiscount.discountType" class="form-control"> -->
                </div>
                
                <div class="form-group">
                  <label for="sp-amountInput">Amount Value / Discount Value</label>
                  <input type="number" id="sp-amountInput" min="1"  v-model="specialDiscount.amountInput" class="form-control" required>
                </div>

                <div class="form-group">
                  <label for="sp-discountType">Discount QB Map</label>
                  <v-select v-model="specialDiscount.qbo_id" :options="qbo_discount_items" :reduce="item => item.Id" label="Name" />
                 <!--  <select id="sp-discountType" v-model="specialDiscount.qbo_id" class="form-control" required>
                    <option selected disabled>Please Select QB Type</option>
                    <option :value="item.Id" :key="item.Id" v-for="item in qbo_discount_items">{{ item.Name }}</option>
                  </select>  -->
                </div>
                
              </form>
            </div>
            <div class="modal-footer">
              <button :disabled="btnDisabled" type="button" v-on:click="addSpecialDiscount" class="btn btn-primary">Add</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL ADDITIONAL FEE -->
      <div id="addAdditionalFee" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Additional Fee</h5>
            </div>
            <div class="modal-body">
              <form action="">    

                <div class="form-group">
                  <label for="af-qbMap">QB Map</label>
                  <v-select id="af-qbMap" :options="qbo_items" v-model="additionalFee.qbo_id" :reduce="item => item.Id" label="Name" />
                  <!-- <select id="af-qbMap" v-model="additionalFee.qbo_id" class="form-control" required>
                    <option selected disabled>Please Select QB Type</option>
                    <option :value="item.Id" :key="item.Id" v-for="item in qbo_discount_items">{{ item.Name }}</option>
                  </select>  -->
                </div>

                <div class="form-group">
                  <label for="af-amountInput">Amount</label>
                  <input type="number" id="af-amountInput" min="1"  v-model="additionalFee.amountInput" class="form-control" required>
                </div>

                <div class="form-group">
                  <label for="af-description">Description</label>
                  <textarea id="af-description" v-model="additionalFee.description" class="form-control"></textarea>
                </div>
                
              </form>
            </div>
            <div class="modal-footer">
              <button :disabled="btnDisabled" type="button" v-on:click="addAdditionalFee" class="btn btn-primary">Add</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL ADD DISCREPANCY -->
      <div id="addDiscrepancy" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Adjustment</h5>
            </div>
            <div class="modal-body">
              <form action="">    

                <div class="form-group">
                  <label for="discrepancy-qbMap">QB Map</label>
                  <v-select id="discrepancy-qbMap" :options="qbo_items" v-model="discrepancy.qbo_id" :reduce="item => item.Id" label="Name" />
                  <!-- <select id="discrepancy-qbMap" v-model="additionalFee.qbo_id" class="form-control" required>
                    <option selected disabled>Please Select QB Type</option>
                    <option :value="item.Id" :key="item.Id" v-for="item in qbo_discount_items">{{ item.Name }}</option>
                  </select>  -->
                </div>

                <div class="form-group">
                  <label for="discrepancy-amountInput">Amount</label>
                  <input type="number" id="discrepancy-amountInput" min="1"  v-model="discrepancy.amountInput" class="form-control" required>
                </div>

                <div class="form-group">
                  <label for="discrepancy-description">Description</label>
                  <textarea id="discrepancy-description" v-model="discrepancy.description" class="form-control"></textarea>
                </div>
                
              </form>
            </div>
            <div class="modal-footer">
              <button :disabled="btnDisabled" type="button" v-on:click="addDiscrepancy" class="btn btn-primary">Add</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL SEND SOA -->
      <div id="sendSOA" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Send Statement of Accounts</b></h4>
            </div>
            <div class="modal-body">
              <!-- Loading GIF -->
              <div class="row" v-if="sendingEmail">
                <div class="col-md-12 col-lg-12">
                    <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
                </div>
              </div>

              <div class="row" v-else>
                <form action="">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="discrepancy-amountInput">Enter Email</label>
                      <input type="email" id="email" name="email" @keyup="validateEmail" min="1" v-model="email" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-6" v-if="student.father_email">
                    <label> Father Email:</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" :value="student.father_email" id="father_email" name="father_email" v-model="father_email">
                      <label class="form-check-label" for="father_email">
                        {{ student.father_email }}
                      </label>
                    </div>
                  </div>
                  
                  <div class="col-md-6" v-if="student.mother_email">
                    <label> Mother Email:</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" :value="student.mother_email" id="mother_email" name="mother_email" v-model="mother_email">
                      <label class="form-check-label" for="mother_email">
                        {{ student.mother_email }}
                      </label>
                    </div>
                  </div>
                  
                  <div class="col-md-6" v-if="student.legal_guardian_email">
                    <label> Legal Guardian Email:</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" :value="student.legal_guardian_email" id="legal_guardian_email" name="legal_guardian_email" v-model="legal_guardian_email">
                      <label class="form-check-label" for="legal_guardian_email">
                        {{ student.legal_guardian_email }}
                      </label>
                    </div>
                  </div>
                 
                  <div class="col-md-6" v-if="student.emergency_email">
                    <label> Emergency Contact Email:</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" :value="student.emergency_email" id="emergency_email" name="emergency_email" v-model="emergency_email">
                      <label class="form-check-label" for="emergency_email">
                        {{ student.emergency_email }}
                      </label>
                    </div>
                  </div>
                  
                  
                </form>
              </div>
              
            </div>
            <div class="modal-footer" v-if="!sendingEmail">
              <button :disabled="btnDisabled" type="button" v-if="isEmailValid || father_email || mother_email || legal_guardian_email || emergency_email" v-on:click="sendSoa()" class="btn btn-primary">Send</button>
              <button :disabled="btnDisabled" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- MODAL SEND SOA -->

    </div>
  </div>
</template>

<style type="text/css">
  #tuitionTabe tbody tr:not(.thead) {
    text-indent: 15px;
  }
</style>

<script>

import vSelect from 'vue-select';

export default {
  components: {
    'v-select': vSelect
  },
  props: ['schoolName'],
  data() {
    return {
      options: [
      'foo',
      'bar',
      'baz'
    ],
      baseUrl: location.protocol + '//' + location.host,
      commitment_payment_id: null,
      commitment_payment_snake: null,
      gradeLevel: '',
      enrollment_id: null,
      enrollment: null,
      schoolYear: '',
      other_program_lists: [],
      other_service_lists: [],
      qbo_discount_items: [],
      qbo_items: [],
      payment_history: [],
      selected_other_programs: [],
      selected_other_services: [],
      additional_fees: [],
      special_discount_lists: [],
      discrepancies: [],
      student: {},
      totalPaymentHistory: 0,
      totalSelectedOtherProgram: 0,
      totalSelectedOtherService: 0,
      totalAdditionalFee: 0,
      totalSpecialDiscount: 0,
      totalDiscrepancy: 0,
      tuition_name: '',
      tuition: {},

      payment_method_lists: this.getPaymentMethodList(),
      totalPayableUponEnrollment: 0,
      
      payment_method_selected: null,
      selected_other_program_id: null,
      selected_other_service_id: null,

      paymentCash: {
        amountInput: null,
        description: null,
        paymentFor: "",
        dateReceived: null,
      },
      paymentCreditCard: {
        amountInput: null,
        description: null,
      },
      additionalFee: {
        qbo_id: null,
        amountInput: null,
        description: null,
      },
      discrepancy: {
        qbo_id: null,
        amountInput: null,
        description: null,
      },
      specialDiscount: {
        discountCategory: 'Discount',
        description: null,
        applyTo: 'TuitionFeeOnly',
        applyToOptions: [
          { text: 'Tuition Fee Only', value: 'TuitionFeeOnly' },
          { text: 'Tuition Fee & Misc Fee', value: 'TuitionFeeAndMiscFee' }
        ],
        discountType: 'Amount',
        amountInput: null,
        qbo_id: null,
      },

      gTotal: 0,  

      paymentMethod: null,
      balance: 0,

      btnDisabled: false,

      receiptLayouts: null,

      email: null,
      isEmailValid: false,
      sendingEmail: false,

      father_email: false,
      mother_email: false,
      legal_guardian_email: false,
      emergency_email: false,

      isLoading: true,
    }
  },

  beforeCreate() {
      //  LOAD STUDENT
      let url = window.location.href;
      let lastPartUrl = url.split("/").pop();

      axios.get(location.protocol + '//' + location.host + '/admin/student-accounts/api/all-tuition-fee-data/' + lastPartUrl)
        .then(response => {
            let tuition = response.data;

            this.other_program_lists        = tuition.other_program_lists;
            this.other_service_lists        = tuition.other_service_lists;
            this.payment_history            = tuition.payment_histories;
            this.gradeLevel                 = tuition.tuition.year_management.year;
            this.tuition_name               = tuition.tuition.form_name;
            this.selected_other_programs    = tuition.selected_other_programs;
            this.selected_other_services    = tuition.selected_other_services;
            this.additional_fees            = tuition.additional_fees;
            this.discrepancies              = tuition.discrepancies;
            this.special_discount_lists     = tuition.special_discount_lists;
            this.student                    = tuition.student;
            this.totalPaymentHistory        = tuition.total_payment_history;
            this.totalSelectedOtherProgram  = tuition.total_selected_other_program;
            this.totalSelectedOtherService  = tuition.total_selected_other_service;
            this.totalAdditionalFee         = tuition.total_additional_fee;
            this.totalDiscrepancy           = tuition.total_discrepancy;
            this.totalSpecialDiscount       = tuition.total_special_discount;
            this.commitment_payment_id      = tuition.commitment_payment.id;
            this.commitment_payment_snake   = tuition.commitment_payment.snake + '_amount';
            this.enrollment_id              = tuition.enrollment_id;
            this.enrollment                 = tuition.enrollment;
            this.tuition                    = tuition.tuition;
            this.schoolYear                 = this.tuition.school_year.schoolYear;
            this.balance                    = this.remainingBalance();
            this.qbo_discount_items         = tuition.qbo_discount_items;
            this.qbo_items                  = tuition.qbo_items;
            this.isLoading                  = false;
        }).catch(function (error) {
            window.location.href = location.protocol + '//' + location.host + '/admin/quickbooks/authorize';
            console.log(error);
            alert(error);
        });

        axios.get('/admin/api/payment-history/receipt-partials-layout')
          .then(res => {
            this.receiptLayouts = res.data;
          });

  },

  methods: {
    printReceipt(id) {
      window.open(this.baseUrl + '/admin/student-account/receipt/' + id +'/print', "_blank"); 
    },
    remainingBalanceOfOtherProgram (id, total)
    {
      var totalPayment = 0;
      $.each(this.payment_history, function (key, val) {
        if(val.payment_historable_id == id && val.payment_historable_type === 'App\\SelectedOtherProgram'){
          totalPayment += parseFloat(val.amount);
        }
      });

      return parseFloat(total) - totalPayment;
    },

    remainingBalanceOfOtherService (id, total)
    {
      var totalPayment = 0;
      $.each(this.payment_history, function (key, val) {
        if(val.payment_historable_id == id && val.payment_historable_type === 'App\\SelectedOtherService'){
          totalPayment += parseFloat(val.amount);
        }
      });

      return parseFloat(total) - totalPayment;
    },

    remainingBalanceOfAdditionalFee (id, total)
    {
      var totalPayment = 0;
      $.each(this.payment_history, function (key, val) {
        if(val.payment_historable_id == id && val.payment_historable_type === 'App\\AdditionalFee'){
          totalPayment += parseFloat(val.amount);
        }
      });

      return parseFloat(total) - totalPayment;
    },

    paymentFor(event) {
      if(event.target.value != "") {
        var data = event.target.value.split("|");
        // console.log(data);
        if(data[1] == 'OtherProgram') {
          var obj  =  _.find(this.selected_other_programs, function (o) { return o.id == data[0]; });
          this.paymentCash.amountInput = obj.remaining_balance;
        } 
        else if(data[1] == 'AdditionalFee') {
          var obj  =  _.find(this.additional_fees, function (o) { return o.id == data[0]; });
          // console.log(obj);
          return;
          this.paymentCash.amountInput = obj.remaining_balance;
        } 
        else {
          var obj = _.find(this.selected_other_services, function (o) { return o.id == data[0]; });
          this.paymentCash.amountInput = obj.remaining_balance;
        }
      }
    },

    getPaymentMethodList() {
      axios.get('/admin/api/get/payment-method')
        .then(response => {
          this.payment_method_lists = response.data;
        });
    },

    remainingBalance() {
      var payment_commit_id         = this.commitment_payment_id;
      var totalPaymentHistory       = this.totalPaymentHistory;
      var totalSelectedOtherProgram = this.totalSelectedOtherProgram;
      var totalSelectedOtherService = this.totalSelectedOtherService;
      var totalAdditionalFee        = this.totalAdditionalFee;
      var totalDiscrepancy          = this.totalDiscrepancy;
      var totalSpecialDiscount      = this.totalSpecialDiscount;
      var total                     = 0;
      
      $.each(this.tuition.grand_total, function (key, value) {
        if(value.payment_type === parseInt(payment_commit_id)) {
          total = parseFloat(value.amount) - 
                  parseFloat(totalPaymentHistory) + 
                  parseFloat(totalSelectedOtherProgram) + 
                  parseFloat(totalSelectedOtherService) - 
                  parseFloat(totalSpecialDiscount) + 
                  parseFloat(totalAdditionalFee) - 
                  parseFloat(totalDiscrepancy);
        }
      });
      return parseFloat(total.toFixed(2)); 
      // return Math.round(total * 100) / 100;
    },

    convertNumberToPercentage (x) {
      return (x / 100) / 1;
    },

    addSpecialDiscount() {
      (function ($this) {
        $.confirm({
            title: 'Confirmation',
            content: 'Are you sure do you want to add this special discount?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function() {
                      if($this.specialDiscount.amountInput !== null || $this.specialDiscount.amountInput !== '' || $this.specialDiscount.amountInput == 0) {
                        
                        if($this.specialDiscount.amountInput > $this.balance) {
                          $.confirm({
                              title: 'Error',
                              content: 'Exceeded Remaining Balance!',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }

                        if($this.specialDiscount.amountInput == null || $this.specialDiscount.amountInput == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Enter Amount',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }

                        if($this.specialDiscount.description == null || $this.specialDiscount.description == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Add Description',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }

                        if($this.specialDiscount.discountCategory == null || $this.specialDiscount.discountCategory == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Select Discount Category Type',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }

                        if($this.specialDiscount.applyTo == null || $this.specialDiscount.applyTo == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Select Apply To',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }
                        
                        if($this.specialDiscount.discountType == null || $this.specialDiscount.discountType == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Select Discount Type',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }
                        
                        if($this.specialDiscount.qbo_id == null || $this.specialDiscount.qbo_id == '') {
                          $.confirm({
                              title: 'Error',
                              content: 'Please Select QB Map Type',
                              autoClose: 'ok|5000',
                              buttons: { ok: { text: 'OK', } }
                          });
                          return false;
                        }


                        var amount = 0;

                        // Tuition Fee Only & Discount Type Is Percentage (TFO-Percentage)
                        if($this.specialDiscount.applyTo === "TuitionFeeOnly" && $this.specialDiscount.discountType === "Percentage") {
                            var payment_type = '';
                            var payment_scheme = 0;
                            var tuition_fees = parseFloat( _.find($this.tuition.tuition_fees, {'payment_type': String($this.commitment_payment_id)}, ).total );
                            payment_scheme = _.sumBy($this.tuition.payment_scheme, $this.commitment_payment_snake);
                            // if($this.commitment_payment_id == 2) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'semi_amount');
                            // } else if ($this.commitment_payment_id == 3) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'quarterly_amount');
                            // } else if ($this.commitment_payment_id == 4) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'monthly_amount');
                            // } else {
                            //   alert("Error, Something Went Wrong. Please Try Again.");
                            //   return;
                            // }
                            var sum = tuition_fees + parseFloat(payment_scheme);
                            var amountInput = $this.convertNumberToPercentage(parseFloat($this.specialDiscount.amountInput));
                            amount = sum * amountInput;
                        } 
                        // Tuition Fee, Payment Scheme & Miscellaneous
                        else if ($this.specialDiscount.applyTo === "TuitionFeeAndMiscFee" && $this.specialDiscount.discountType === "Percentage") {
                            var payment_type = '';
                            var payment_scheme = 0;
                            var tuition_fees = parseFloat( _.find($this.tuition.tuition_fees, {'payment_type': String($this.commitment_payment_id)}, ).total );
                            payment_scheme = _.sumBy($this.tuition.payment_scheme, $this.commitment_payment_snake);
                            // if($this.commitment_payment_id == 2) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'semi_amount');
                            // } else if ($this.commitment_payment_id == 3) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'quarterly_amount');
                            // } else if ($this.commitment_payment_id == 4) {
                            //   payment_scheme = _.sumBy($this.tuition.payment_scheme, 'monthly_amount');
                            // } else {
                            //   alert("Error, Something Went Wrong. Please Try Again.");
                            //   return;
                            // }
                            var sum = tuition_fees + parseFloat(payment_scheme) + $this.tution.total_miscellaneous;
                            var amountInput = $this.convertNumberToPercentage(parseFloat($this.specialDiscount.amountInput));
                            amount = sum * amountInput;
                        } else {
                          amount = $this.specialDiscount.amountInput;
                        }

                        $this.btnDisabled = true;
                        axios.post('/admin/api/special-discount/add', 
                        {
                          enrollment_id     : $this.enrollment_id,
                          amount            : amount,
                          apply_to          : $this.specialDiscount.applyTo,
                          description       : $this.specialDiscount.description,
                          discount_category : $this.specialDiscount.discountCategory,
                          discount_type     : $this.specialDiscount.discountType,
                          qbo_id            : $this.specialDiscount.qbo_id,

                        }).then(res => {
                            if(res.data.status == 'SUCCESS') {
                              $this.special_discount_lists.push(res.data.data);
                              $this.totalSpecialDiscount += parseFloat(res.data.data.amount);
                              // $this.balance -= parseFloat(res.data.data.amount);
                              $this.balance = $this.remainingBalance();                            
                              $.confirm({
                                  title: 'Success',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });
                              $this.specialDiscount.amountInput = null;
                              $this.specialDiscount.description = null;

                            }
                            $this.btnDisabled = false;
                        }).catch(function (error) {
                            alert(error);
                            $this.btnDisabled = false;
                        });

                      } // END OF CASH

                    } // END OF ACTION
                },
                cancel: function(){}
            }
        });

      })(this);
    },

    addOtherProgram() {
      (function ($this) {
        $.confirm({
            title: 'Cofirmation',
            content: 'Are you sure to add this program?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){

                      if($this.selected_other_program_id !== null) {
                        $this.btnDisabled = true;
                        axios.post('/admin/api/selected-other-program/add', 
                        {

                          selected_other_program_id      : $this.selected_other_program_id,
                          enrollment_id                  : $this.enrollment_id,

                        }).then(res => {
                            if(res.data.status == 'SUCCESS') {
                              $this.selected_other_programs.push(res.data.data);
                              $this.totalSelectedOtherProgram += parseFloat(res.data.data.other_program.amount);
                              $this.balance += parseFloat(res.data.data.other_program.amount);
                              $this.balance = $this.remainingBalance();
                              $.confirm({
                                  title: 'Success',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });

                            } else {
                              $.confirm({
                                  title: 'Error',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });
                            }
                            $this.btnDisabled = false;
                        }).catch(function (error) {
                            alert(error);
                            $this.btnDisabled = false;
                        });

                      } // END OF CASH

                    } // END OF ACTION
                },
                cancel: function(){}
            }
        });

      })(this);
    },

    addOtherService() {
      (function ($this) {
        $.confirm({
            title: 'Cofirmation',
            content: 'Are you sure to add this service?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){

                      if($this.selected_other_service_id !== null) {
                        $this.btnDisabled = true;
                        axios.post('/admin/api/selected-other-service/add', 
                        {

                          selected_other_service_id : $this.selected_other_service_id, 
                          enrollment_id             : $this.enrollment_id, 

                        }).then(res => {
                            if(res.data.status == 'SUCCESS') {
                              $this.selected_other_services.push(res.data.data);
                              $this.totalSelectedOtherService += parseFloat(res.data.data.other_service.amount);
                              $this.balance += parseFloat(res.data.data.other_service.amount);
                              $this.balance = $this.remainingBalance();
                              $.confirm({
                                  title: 'Success',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });

                            } else {
                              $.confirm({
                                  title: 'Error',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });
                            }
                            $this.btnDisabled = false;
                        }).catch(function (error) {
                            alert(error);
                            $this.btnDisabled = false;
                        });

                      } // END OF CASH

                    } // END OF ACTION
                },
                cancel: function(){}
            }
        });

      })(this);
    },

    addAdditionalFee() {
      (function ($this) {
        if($this.additionalFee.qbo_id == null) {
          alert("Please Select QB Map");
          return;
        }

        if($this.additionalFee.amountInput == null) {
          alert("Please Enter Amount");
          return;
        }

        if($this.additionalFee.description == null) {
          alert("Please Enter Description");
          return;
        }
        $.confirm({
            title: 'Cofirmation',
            content: 'Are you sure you want to add this additional fee?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){

                        $this.btnDisabled = true;
                        axios.post('/admin/api/additional-fee/add', 
                        {
                          enrollment_id: $this.enrollment_id,
                          qbo_id: $this.additionalFee.qbo_id,
                          amountInput: $this.additionalFee.amountInput,
                          description: $this.additionalFee.description,

                        }).then(res => {
                            if(res.data.status == 'SUCCESS') {
                              // console.log('af success');
                              // console.log(res);
                              $this.additional_fees.push(res.data.data);
                              $this.totalAdditionalFee += parseFloat(res.data.data.amount);
                              $this.balance += parseFloat(res.data.data.amount);
                              $this.balance = $this.remainingBalance();
                              $.confirm({
                                  title: 'Success',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });

                            } else {
                              $.confirm({
                                  title: 'Error',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });
                            }
                            $this.btnDisabled = false;
                        }).catch(function (error) {
                            alert(error);
                            $this.btnDisabled = false;
                        });

                    } // END OF ACTION
                },
                cancel: function(){}
            }
        });

      })(this);
    },

    addDiscrepancy() {
      (function ($this) {
        if($this.discrepancy.qbo_id == null) {
          alert("Please Select QB Map");
          return;
        }

        if($this.discrepancy.amountInput == null) {
          alert("Please Enter Amount");
          return;
        }

        if($this.discrepancy.description == null) {
          alert("Please Enter Description");
          return;
        }
        $.confirm({
            title: 'Cofirmation',
            content: 'Are you sure you want to add this Discrepancy?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function() {

                        $this.btnDisabled = true;
                        axios.post('/admin/api/discrepancy/add', 
                        {
                          enrollment_id: $this.enrollment_id,
                          qbo_id: $this.discrepancy.qbo_id,
                          amountInput: $this.discrepancy.amountInput,
                          description: $this.discrepancy.description,

                        }).then(res => {
                            if(res.data.status == 'SUCCESS') {
                              // console.log('discrepancy success');
                              // console.log(res);
                              $this.discrepancies.push(res.data.data);
                              $this.totalDiscrepancy += parseFloat(res.data.data.amount);
                              $this.balance += parseFloat(res.data.data.amount);
                              $this.balance = $this.remainingBalance();
                              $.confirm({
                                  title: 'Success',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });

                            } else {
                              $.confirm({
                                  title: 'Error',
                                  content: res.data.message,
                                  autoClose: 'ok|5000',
                                  buttons: { ok: { text: 'OK', } }
                              });
                            }
                            $this.btnDisabled = false;
                        }).catch(function (error) {
                            alert(error);
                            $this.btnDisabled = false;
                        });

                    } // END OF ACTION
                },
                cancel: function(){}
            }
        });

      })(this);
    },

    jConfirmTableContent($name, $amount, $fee, $total, $description) {
      let table = '';

      table = '<table class="table table-striped">\
                  <tr>\
                    <td><small><b>Payment Method</b></small></td>\
                    <td>' + $name + '</td>\
                  </tr>\
                  <tr>\
                    <td><b><small>Amount</small></b></td>\
                    <td><b>P' + $amount + '</b></td>\
                  </tr>\
                  <tr>\
                    <td><small><b>Fee</b></small></td>\
                    <td>' + $fee + '%</td>\
                  </tr>\
                  <tr style="border-top: 2px solid rgba(0, 0, 0, 0.3);">\
                    <td><small><b>Total</b></small></td>\
                    <td>' + $total.toFixed(2)  + '</td>\
                  </tr>\
                  <tr><td colspan="2" class="text-center"><small><b>Description</b></small></td></tr>\
                  <tr><td colspan="2">' + $description + '</td></tr>\
                </table>';

      return table;
    },

    jConfirmMessage($title, $message) {
      $.confirm({
          title: $title,
          content: $message,
          autoClose: 'ok|5000',
          buttons: { ok: { text: 'OK', } }
      });
    },

    receiptBody (data)
    {
      var paymentMethod =  _.find(this.payment_method_lists, {  'id' : parseInt(data.payment_method_id) }).name;

      var receiptBody = '<div class="col-md-12 p-0">\
                              <div class="row info-text pt-4 pb-2">\
                                <div class="col-4"></div>\
                                <div class="col-4">\
                                  <p class="text-center mb-0"><b>OFFICIAL RECEIPT</b></p>\
                                </div>\
                                <div class="col-4">\
                                  <p class="text-center mb-0">No. ' + data.id + '</p>\
                                </div>\
                              </div>\
                            </div>\
                            <div class="col-12 mx-auto">\
                              <table class="table">\
                                <tbody>\
                                  <tr><td><p class="mb-0"><b>' + this.schoolName + '</b></p></td>\
                                  <td></td>\
                                  <td></td>\
                                  </tr>\
                                  <tr>\
                                    <td>21</td>\
                                    <td></td>\
                                    <td></td>\
                                  </tr>\
                                  <tr class="pt-2">\
                                    <td class="pt-2">\
                                      <p class="mb-0"><b>Received From:</b></p>\
                                    </td>\
                                    <td></td>\
                                    <td></td>\
                                  </tr>\
                                  <tr>\
                                    <td>\
                                      <p class="mb-0">' + this.student.fullname + ' ' + this.tuition.year_management.year + ' '+ this.schoolYear + '</p>\
                                      <p class="mb-0">' + this.student.fullname + '</p>\
                                      <p class="mb-0">' + this.tuition.year_management.year + '</p>\
                                    </td>\
                                    <td></td>\
                                    <td></td>\
                                  </tr>\
                                  <tr>\
                                    <td class="pt-5" colspan="4">\
                                      <div class="row">\
                                        <div class="col-6">\
                                          <div class="row">\
                                            <div class="col-6 text-right"><b>Date Received</b></div>\
                                            <div class="col-6">' + data.created_at + '</div>\
                                          </div>\
                                          <div class="row">\
                                            <div class="col-6 text-right"><b>Payment Method</b></div>\
                                            <div class="col-6">' + paymentMethod + '</div>\
                                          </div>\
                                          <div class="row">\
                                            <div class="col-6 text-right"><b>Check/Ref No.</b></div>\
                                            <div class="col-6">' + data.id + '</div>\
                                          </div>\
                                        </div>\
                                        <div class="col-6">\
                                          <div class="row">\
                                            <div class="col-6 text-right"><b>Payment Amount</b></div>\
                                            <div class="col-6">PHP ' + data.amount + '</div>\
                                          </div>\
                                        </div>\
                                      </div>\
                                    </td>\
                                  </tr>\
                                </tbody>\
                              </table>\
                            </div>\
                            </div>\
                          </section>';

          return receiptBody;
    },

    pay() {
      (function ($this) {

        let paymentName = null, fee = 0, interest = 0;

        $.each($this.payment_method_lists, function (key, val) {
          if(val.id == $this.payment_method_selected) {
            paymentName = val.name;
            fee         = val.fee || 0;
            interest    = ( parseFloat(fee) * 1 ) / 100;
          }
        });

        // CHECK THE CLIENT WHAT CLIENT IS PAYING FOR
        if($this.paymentCash.paymentFor !== "")
        {
          var data = $this.paymentCash.paymentFor.split("|");
          if(data[1] == 'OtherProgram') {
            
            // GET THE OTHER PROGRAM DATA AND GET THE TOTAL PRICE
            var otherProgram = _.find($this.selected_other_programs, function (o) { return o.id == data[0]; });

            // GET THE REMAINING BALANCE
            var remainingBalance = $this.remainingBalanceOfOtherProgram(otherProgram.id, otherProgram.other_program.amount);

            // CHECK IF AMOUNT ENTERED IS GREATER THAN TO THE REMAINING BALANCE RETURN ERROR
            if(parseFloat($this.paymentCash.amountInput) > remainingBalance) {
              alert("Exceeded Remainig Balance! Your Remaning Balance Is : " + remainingBalance);
              return;
            }

          }  
          else if(data[1] == 'AdditionalFee') {
            
            // GET THE OTHER PROGRAM DATA AND GET THE TOTAL PRICE
            var additionalFee = _.find($this.additional_fees, function (o) { return o.id == data[0]; });

            // GET THE REMAINING BALANCE
            var remainingBalance = $this.remainingBalanceOfAdditionalFee(additionalFee.id, additionalFee.amount);

            // CHECK IF AMOUNT ENTERED IS GREATER THAN TO THE REMAINING BALANCE RETURN ERROR
            if(parseFloat($this.paymentCash.amountInput) > remainingBalance) {
              alert("Exceeded Remainig Balance! Your Remaning Balance Is : " + remainingBalance);
              return;
            }

          } 
          else {

            var data = $this.paymentCash.paymentFor.split("|");
                
              // GET THE OTHER PROGRAM DATA AND GET THE TOTAL PRICE
              var otherService = _.find($this.selected_other_services, function (o) { return o.id == data[0]; });

              // GET THE REMAINING BALANCE
              var remainingBalance = $this.remainingBalanceOfOtherService(otherService.id, otherService.other_service.amount);

              // CHECK IF AMOUNT ENTERED IS GREATER THAN TO THE REMAINING BALANCE RETURN ERROR
              if(parseFloat($this.paymentCash.amountInput) > remainingBalance) {
                alert("Exceeded Remainig Balance! Your Remaning Balance Is : " + remainingBalance);
                return;
              }

          }
        }

        // CASH 
        if($this.paymentCash.amountInput == null || $this.paymentCash.amountInput !== '') {

              let amountInput = parseFloat($this.paymentCash.amountInput);
              let total       = (amountInput * interest) + amountInput;

              if(amountInput > $this.balance) {
                $.confirm({
                    title: 'Error',
                    content: 'Exceeded Remaining Balance!',
                    autoClose: 'ok|5000',
                    buttons: { ok: { text: 'OK', } }
                });
                return false;
              }

              if(amountInput == null || amountInput == '') {
                $.confirm({
                    title: 'Error',
                    content: 'Please Enter Amount',
                    autoClose: 'ok|5000',
                    buttons: { ok: { text: 'OK', } }
                });
                return false;
              }
                

              $.confirm({
                  title: 'Confirmation',
                  content: $this.jConfirmTableContent(paymentName, $this.paymentCash.amountInput, fee, total, $this.paymentCash.description, $this.paymentCash.dateReceived),
                  type: 'green',
                  buttons: {   
                      ok: {
                          text: "OK",
                          btnClass: 'btn-primary',
                          keys: ['enter'],
                          action: function(){

                              $this.btnDisabled = true;
                              axios.post('/admin/api/add-payment/add', 
                              {
                                enrollment_id         : $this.enrollment_id, 
                                payment_method_id     : $this.payment_method_selected,
                                amount                : $this.paymentCash.amountInput, 
                                fee                   : fee, 
                                payment_for           : $this.paymentCash.paymentFor, 
                                description           : $this.paymentCash.description, 
                                date_received         : $this.paymentCash.dateReceived,
                              }).then(res => {


                                  if(res.data.status == 'SUCCESS') {

                                    $this.payment_history.push(res.data.data);
                                    $this.balance             = $this.balance - parseFloat(res.data.data.amount);
                                    $this.totalPaymentHistory += parseFloat(res.data.data.amount);
                                    $this.balance             = $this.remainingBalance();  
                                    $this.jConfirmMessage(res.data.status, res.data.message);
                                    // console.log(res.data.data);
                                    $this.paymentCash.amountInput = null;
                                    $this.paymentMethod = null;


                                    //  OPEN PRINT RECEIPT
                                    // var myWindow = window.open("", "_blank");
                                    // myWindow.document.write('<html><head>' + $this.receiptLayouts.style  + '</head><body onload="window.print()">' + $this.receiptLayouts.header + $this.receiptBody(res.data.data) + '</body></html>');
                                   
                                    // setTimeout(function(){
                                    //     myWindow.focus();
                                    //     myWindow.print();
                                    //     myWindow.close();
                                    // },10);
                                    var id = res.data.data.id;
                                    window.open('receipt/'+id+'/print', "_blank");
                                  }
                                  $this.btnDisabled = false;
                              }).catch(function (error) {
                                  alert(error);
                                  $this.btnDisabled = false;
                              });

                          } // END OF ACTION
                      },
                      cancel: function(){}
                  }
              });
        } // END OF CASH


      })(this);
    },

    // Add Invoice To QBO
    addInvoicePayment (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/payment?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoicePayment (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/payment/delete?enrollment_id=' + this.enrollment_id;
    },

    addInvoiceSpecialDiscount (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/special-discount?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoiceSpecialDiscount (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/special-discount/delete?enrollment_id=' + this.enrollment_id;
    },

    addInvoiceDiscrepancy (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/discrepancy?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoiceDiscrepancy (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/discrepancy/delete?enrollment_id=' + this.enrollment_id;
    },

    addInvoiceOtherProgram (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/other-program?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoiceOtherProgram (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/other-program/delete?enrollment_id=' + this.enrollment_id;
    },

    addInvoiceOtherService (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/other-service?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoiceOtherService (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/other-service/delete?enrollment_id=' + this.enrollment_id;
    },

    addInvoiceAdditionalFee (id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/additional-fee?enrollment_id=' + this.enrollment_id;
    },

    deleteInvoiceAdditionalFee(id) {
      location.href = this.baseUrl + '/admin/api/student-account/invoice/' + id + '/additional-fee/delete?enrollment_id=' + this.enrollment_id;
    },

    sendSoa() {
      this.sendingEmail = true;
      axios.post('/admin/student-account/send-soa', {
          enrollment_id   : this.enrollment_id,
          email           : this.email,
          father_email    : this.father_email,
          mother_email    : this.mother_email,
          legal_guardian_email    : this.legal_guardian_email,
          emergency_email    : this.emergency_email,
      }).then(res => {
          new PNotify({
              title: res.data.title,
              text:  res.data.message,
              type:  res.data.error ? "error" : "success"
          });
          this.email = null;
          this.isEmailValid = false;
          this.sendingEmail = false;

          $("#btnSendSoa").click(); 
      }).catch(error => {
          this.sendingEmail = false;
          new PNotify({
              title: 'Error',
              text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
              type: "error"
          });
      });
    },

    validateEmail () {
      var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      
      if(this.email !== null) {
          if(this.email.match(mailformat)) {
              this.isEmailValid = true;
          }
          else {
              this.isEmailValid = false;
          }
      }
    },
  },

  computed: {
    getTotalPayableUponEnrollment () {
      var self = this;
      if(self.tuition.tuition_fees !== undefined) {
        return parseFloat( _.find(self.tuition.tuition_fees, {'payment_type': String(self.commitment_payment_id)}, ).total );
      }
    },
    getTotalMandatoryFeesUponEnrollment () {
      var self = this;
      if(self.tuition.total_mandatory_fees_upon_enrollment !== undefined) {
        return parseFloat( _.find(self.tuition.total_mandatory_fees_upon_enrollment, {'payment_type': self.commitment_payment_id}, ).amount );
      }
    }
  }
}
</script>
