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
              <span class="info-box-number">{{ student ? student.studentnumber : '-' }}</span>
            </div>
            <div class="col-md-3 col-lg-3">
              <span class="info-box-text text-info">Full Name</span>
              <span class="info-box-number">{{ student ? student.fullname : '-' }}</span>
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
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div v-if="isLoading">
            <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
        </div>
      </div>
    </div>

    <!-- GENERAL TUITION INFORMaTION -->  
    <div v-if="!isLoading" class="row">
      <!-- TF INFORMATION -->
      <div class="col-md-12 col-lg-12">
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
                  <tr class="tf-total">
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

    </div>

    <!-- REMAINING FEES -->
    <div v-if="!isLoading" class="row">
      <div class="col-md-12 col-lg-12">
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
                      <table class="table m-b-0">
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
                      <table class="table m-b-0">
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
                      <table class="table m-b-0">
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
                      <table class="table m-b-0">
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
  props: ['school_name', 'id'],
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
      enrollment_id: this.id,
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
      isLoading: true,

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
    }
  },

  beforeCreate() {
      //  LOAD STUDENT
      let url = window.location.href;
      let lastPartUrl = url.split("/").pop();

      axios.get(location.protocol + '//' + location.host + '/student/student-accounts/api/all-tuition-fee-data/' + lastPartUrl)
        .then(response => {
            let tuition = response.data;
            // console.log(tuition.tuition.year_management);
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
            console.log(error);
            alert(error);
             alert('error');
        });

        axios.get('/student/api/payment-history/receipt-partials-layout')
          .then(res => {
            this.receiptLayouts = res.data;
          });

  },

  methods: {
    printReceipt(id) {
      window.open(this.baseUrl + '/student/student-account/receipt/' + id +'/print', "_blank"); 
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
      axios.get('/student/api/get/payment-method')
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
