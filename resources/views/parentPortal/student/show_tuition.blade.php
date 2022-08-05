@extends('backpack::layout_parent')

@section('header')
@endsection

@section('content')
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('student/enrollments') }}">Enrollments</a></li>
          <li><a class="text-capitalize active">Tuition</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollments</span>
        <small>Tuition Fees</small>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <section class="row" id="tuitionTabe">

      <div class="col-md-12 col-lg-12">
        
        <!-- CONTENT INFORMATION -->
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="info-box shadow">
              <div class="box-body" style="padding-top:25px;">
                <div class="col-md-4 col-lg-4" style="overflow: hidden;text-overflow: ellipsis;">
                  <span class="info-box-text text-info">Tuition Form</span>
                  <span class="info-box-number" >  {{ $data['tuition']->form_name }}</span>
                </div>
                <div class="col-md-4 col-lg-4">
                  <span class="info-box-text text-info">Student ID</span>
                  <span class="info-box-number">{{ $data['student']->studentnumber }}</span>
                </div>
                <div class="col-md-4 col-lg-4">
                  <span class="info-box-text text-info">Full Name</span>
                  <span class="info-box-number">{{ $data['student']->fullname }}</span>
                </div>
                <div class="col-md-4 col-lg-4 p-t-10">
                  <span class="info-box-text text-info">Department:</span>
                  <span class="info-box-number" >{{ $data['enrollment']->department_name }}</span>
                </div>
                <div class="col-md-4 col-lg-4 p-t-10">
                  <span class="info-box-text text-info">Level:</span>
                  <span class="info-box-number">{{ $data['enrollment']->level_name }}</span>
                </div>
                @if($data['enrollment']->track_name)
                  <div class="col-md-4 col-lg-4 p-t-10">
                    <span class="info-box-text text-info">Track:</span>
                    <span class="info-box-number">{{ $data['enrollment']->track_name }}</span>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>  
        <!-- END OF CONTENT INFORMATION -->

        <!-- GENERAL TUITION INFORMATION -->  
        <div class="row">
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
                          @if(count($data['tuition']->tuition_fees)>0)
                            @foreach($data['tuition']->tuition_fees as $index => $tuition_fee)
                              @if($tuition_fee->payment_type == $data['commitment_payment']->id)
                                <td id="td-pymttype-{{$data['tuition']->payment_type}}">
                                    P{{ number_format($tuition_fee->tuition_fees, 2) }}
                                </td>
                              @endif
                            @endforeach
                          @endif
                        <td></td>
                      </tr>
                      <tr>
                        <td>Less : Early Bird Discount</td>
                        @if($data['tuition']->tuition_fees)
                          @if(count($data['tuition']->tuition_fees)>0)
                            @foreach($data['tuition']->tuition_fees as $index => $tuition_fee)

                              @if($tuition_fee->payment_type == $data['commitment_payment']->id)
                                <td id="td-pymttype-{{$data['tuition']->payment_type}}">
                                    P{{ number_format($tuition_fee->discount, 2) }}
                                </td>
                              @endif
                            @endforeach
                          @endif
                        @endif
                      </tr>
                      <tr class="tf-total">
                        <td ><b>Total Payable Upon Enrollment</b></td>
                        <td><b>P{{ number_format($data['total_payable_upon_enrollment'], 2) }}</b></td>
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
                      @if($data['tuition']->miscellaneous)
                        @if(count($data['tuition']->miscellaneous) > 0)
                          @foreach($data['tuition']->miscellaneous as $miscellaneous)
                            <tr>
                              <td>{{ $miscellaneous->description }}</td>
                              <td>P{{ number_format($miscellaneous->amount, 2) }}</td>
                            </tr>
                          @endforeach
                        @endif
                      @endif
                      <tr class="tf-total">
                        <td><b>Total Miscellaneous Fees</b></td>
                        <td><b>P{{ number_format($data['tuition']->total_miscellaneous, 2) }}</b></td>
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
                      @if($data['tuition']->activities_fee)
                        @if(count($data['tuition']->activities_fee) > 0)
                          @foreach($data['tuition']->activities_fee as $activity)
                            <tr>
                              <td>{{ $activity->description }}</td>
                              <td>P{{ number_format($activity->amount, 2) }}</td>
                            </tr>
                          @endforeach
                        @endif
                      @endif
                      <tr class="tf-total">
                        <td><b>Total Activity Fees</b></td>
                        <td><b>P{{ number_format($data['tuition']->total_activities, 2) }}</b></td>
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
                      @if($data['tuition']->other_fees)
                        @if(count($data['tuition']->other_fees) > 0)
                          @foreach($data['tuition']->other_fees as $other)
                            <tr v-for="other in tuition.other_fees">
                              <td>{{ $other->description }}</td>
                              <td><b>P{{ number_format($other->amount, 2) }}</b></td>
                            </tr>
                          @endforeach
                        @endif
                      @endif
                      <tr class="tf-total"> 
                        <td><b>Total Other Fees</b></td>
                        <td><b>P{{ number_format($data['tuition']->total_other_fees, 2) }}</b></td>
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
                      @if($data['tuition']->payment_scheme)
                        @if(count($data['tuition']->payment_scheme)>0)
                          @foreach($data['tuition']->payment_scheme as $pment_scheme)
                            <tr>
                                <td>{{ $pment_scheme->scheme_date }}</td>
                                @if($data['commitment_payment'])
                                  @if($data['commitment_payment']->name == 'Full')
                                    <td id="td-pymttype-1">P0.00</td>
                                  @elseif($data['commitment_payment']->name == 'Semi-Annual')
                                    <td id="td-pymttype-2">P{{ number_format($pment_scheme->semi_amount, 2) }}</td>
                                  @elseif($data['commitment_payment']->name == 'Quarterly')
                                    <td id="td-pymttype-3">P{{ number_format($pment_scheme->quarterly_amount, 2) }}</td>
                                  @elseif($data['commitment_payment']->name == 'Monthly')
                                    <td id="td-pymttype-4">P{{ number_format($pment_scheme->monthly_amount, 2) }}</td>
                                  @endif
                                @endif
                            </tr>
                          @endforeach
                        @endif
                      @endif
                      <!-- TOTAL INSTALLMENTS -->
                      <tr class="tf-total">
                          <td><b>Total Tuition Fees</b></td>
                          @if($data['commitment_payment']->name == 'Full')
                            <td><b>-</b></td>
                          @else
                            @if($data['tuition']->total_installment)
                              @if( count($data['tuition']->total_installment)>0 )
                                @foreach($data['tuition']->total_installment as $installment)
                                  @if($data['commitment_payment']->id == $installment['payment_type'])
                                    <td>
                                      <b>P{{ number_format($installment['amount'], 2) }}</b>
                                    </td>
                                  @endif
                                @endforeach
                              @endif
                            @endif
                          @endif
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
                        <td>{{ $data['enrollment']->school_year_name }}</td>
                      </tr>
                      <tr>
                        <td>Term Type</td>
                        <td id="gradeLevel">{{$data['enrollment']->term_type }}</td>
                      </tr>
                      <tr>
                        <td>Term</td>
                        <td v-if="enrollment !== null">{{ $data['enrollment']->term }}</td>
                      </tr>

                      <tr>
                        <td>Commitment Payment</td>
                        @if($data['commitment_payment']->name == 'Full')
                          <td class="tf-amount" id="td-pymttype-{{ $data['commitment_payment']->id }}">Cash</td>
                        @else
                          <td class="tf-amount" id="td-pymttype-{{ $data['commitment_payment']->id }}">{{ $data['commitment_payment']->name }}</td>
                        @endif
                      </tr>
                      <tr>
                        <td>Total Mandatory  Fees Upon Enrollment</td>
                        <td><b>P{{ number_format($data['total_mandatory_fees_upon_enrollment'], 2) }}</b></td> 
                      </tr>
                      <tr>
                          <td>Remaining Balance</td>
                          @if($data['commitment_payment']->id == 1)
                            <td><b>-</b></td>
                          @else
                            @if($data['tuition']->total_installment)
                              @if( count($data['tuition']->total_installment) > 0 )
                                @foreach($data['tuition']->total_installment as $installment)
                                  @if($data['commitment_payment']->id == $installment['payment_type'] && $data['commitment_payment']->id != 1)
                                    <td>
                                      <b>P{{ number_format($installment['amount'], 2) }}</b>
                                    </td>
                                  @endif
                                @endforeach
                              @endif
                            @endif
                          @endif
                      </tr>
                      <tr class="tf-total">
                        <td><b>Grand Total</b></td>
                          @if($data['tuition']->grand_total)
                            @if( count($data['tuition']->grand_total) > 0 )
                              @foreach($data['tuition']->grand_total as $index => $grandTotal)
                                @if($index + 1 == $grandTotal['payment_type'] && $grandTotal['payment_type'] == $data['commitment_payment']->id)
                                  <td>
                                      <b>P{{ number_format($grandTotal['amount'] + $data['total_selected_other_program'] + $data['total_additional_fee'], 2) }}</b>
                                  </td>
                                @endif
                              @endforeach
                            @endif
                          @endif
                        </tr>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
          <!-- TF INFORMATION -->
        </div>
        <!-- END OF GENERAL TUITION INFORMATION -->

        <!-- REMAINING FEES -->
        <div class="row">
          <div class="col-md-12 col-lg-12">

            <div class="info-box shadow tf-form" style="min-width:800px;">
              <!-- OTHER PROGRAMS BOX -->
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
              
                      @if( count($data['selected_other_programs']) > 0 )
                        @foreach($data['selected_other_programs'] as $selectedOtherProgram)
                          <tr>
                            <td>
                              <table class="tf-table-table table m-b-0">
                                <tbody>
                                  <tr>
                                    <td>
                                      <span class="text-lowercase">{{ $selectedOtherProgram->otherProgram->name }}</span>&nbsp;&nbsp;
                                    </td>
                                    <td>
                                      <small>{{ date("F d, Y | h:i a", strtotime($selectedOtherProgram->created_at)) }}</small>
                                    </td>
                                    <td>
                                      @if($selectedOtherProgram->user)
                                        <small><i class="fa fa-user"></i> {{ $selectedOtherProgram->user->name }}</small>
                                      @else
                                        <small><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td> 
                              P{{ number_format($selectedOtherProgram->otherProgram ? $selectedOtherProgram->otherProgram->amount : 0, 2) }}
                            </td>
                            <td>
                              @php
                                $totalPayment = $data['payment_histories']->where('payment_historable_id', $selectedOtherProgram->id)
                                                      ->where('payment_historable_type', 'App\\SelectedOtherProgram')
                                                      ->pluck('amount')
                                                      ->sum();

                                $remainingBalanceOfOtherProgram = $selectedOtherProgram->otherProgram->amount - $totalPayment;
                              @endphp
                              P{{ number_format($remainingBalanceOfOtherProgram, 2) }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                            <td colspan="3" class="text-center"><b><h4>No Other Programs</h4></b></td>
                        </tr>
                      @endif

                      <tr id="totalOtherPrograms" style="border-top: 2px solid #3c8dbc;">
                          <td><b>Total Other Programs</b></td>
                          <td><b>P{{ number_format($data['total_selected_other_program'], 2) }}</b></td>
                          <td></td>
                      </tr>
                      <!-- END OF OTHER PROGRAMS -->


                    </tbody>
                  </table>
                </div>
              </div>
              <!-- END OF OTHER PROGRAMS BOX -->

              <!-- OTHER SERVICES BOX -->
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
                      @if( count($data['selected_other_services']) > 0 )
                        @foreach($data['selected_other_services'] as $selectedOtherService)
                          <tr>
                            <td>
                              <table class="table m-b-0">
                                <tbody>
                                  <tr>
                                    <td style="vertical-align: middle; padding: 0; width: 35%">
                                      <span>{{ $selectedOtherService->otherService->name }}</span>&nbsp;&nbsp;
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      <small>{{ date("F d, Y | h:i a", strtotime($selectedOtherService->created_at)) }}</small>
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      @if($selectedOtherService->user)
                                        <small><i class="fa fa-user"></i> {{ $selectedOtherService->user->name }}</small>
                                      @else
                                        <small><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td> 
                              P{{ number_format($selectedOtherService->otherService->amount, 2) }} 
                            </td>
                            <td>
                              @php
                                $totalPayment = $data['payment_histories']->where('payment_historable_id', $selectedOtherService->id)
                                                      ->where('payment_historable_type', 'App\\SelectedOtherService')
                                                      ->pluck('amount')
                                                      ->sum();
                                $remainingBalanceOfOtherService = $selectedOtherService->otherService->amount - $totalPayment;
                              @endphp
                              P{{ number_format($remainingBalanceOfOtherService, 2) }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="3" class="text-center"><b><h4>No Other Services</h4></b></td>
                          <td></td>
                        </tr>
                      @endif

                      <tr id="totalOtherServices" style="border-top: 2px solid #3c8dbc;">
                          <td><b>Total Other Services</b></td>
                          <td><b>P{{ number_format($data['total_selected_other_service'], 2) }}</b></td>
                          <td></td>
                      </tr>
                      <!-- END OF OTHER SERVICES -->
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- END OF OTHER SERVICES BOX -->

              <!-- ADDITIONAL FEES BOX -->
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
                      @if( count($data['additional_fees']) > 0 )
                        @foreach($data['additional_fees'] as $additionalFee)
                          <tr>
                            <td>
                              <table class="table m-b-0">
                                <tbody>
                                  <tr>
                                    <td style="vertical-align: middle; padding: 0; width: 35%">
                                      <span>{{ $additionalFee->description }}</span>&nbsp;&nbsp;
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      <small>{{ date("F d, Y | h:i a", strtotime($additionalFee->created_at)) }}</small>
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      @if($additionalFee->user)
                                        <small><i class="fa fa-user"></i> {{ $additionalFee->user->name }}</small>
                                      @else
                                        <small><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td> 
                              P{{ number_format($additionalFee->amount, 2) }} 
                            </td>
                            <td>
                              @php
                                $totalPayment = $data['payment_histories']->where('payment_historable_id', $additionalFee->id)
                                                      ->where('payment_historable_type', 'App\\AdditionalFee')
                                                      ->pluck('amount')
                                                      ->sum();
                                $remainingBalanceOfAdditionalFee = $additionalFee->amount - $totalPayment;
                              @endphp
                              P{{ number_format($remainingBalanceOfAdditionalFee, 2) }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                            <td colspan="3" class="text-center"><b><h4>No Additional Fees</h4></b></td>
                            <td></td>
                        </tr>
                      @endif
                      <tr id="totalOtherServices" style="border-top: 2px solid #3c8dbc;">
                          <td><b>Total Additional Fee</b></td>
                          <td><b>P{{ number_format($data['total_additional_fee'], 2) }}</b></td>
                          <td></td>
                      </tr>
                      <!-- END OF ADDITIONAL FEES -->
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- END OF ADDITIONAL FEES BOX -->
            </div>


            <div class="info-box shadow tf-form" style="min-width:800px;">
              <!-- ADJUSTMENT BOX -->
              <div class="box-body" style="padding-top:25px;">  
                <div class="col-md-12 col-lg-12">
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
                      @if( count($data['discrepancies']) > 0 )
                        @foreach($data['discrepancies'] as $discrepancy)
                          <tr class="tr-mypayments">
                            <td>
                              <table class="table m-b-0">
                                <tbody>
                                  <tr>
                                    <td style="vertical-align: middle; padding: 0; width: 35%">
                                      <span class="text-lowercase">{{ $discrepancy->description }}</span>&nbsp;&nbsp;
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      <small>{{ date("F d, Y | h:i a", strtotime($discrepancy->created_at)) }}</small>
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      @if($discrepancy->user)
                                        <small><i class="fa fa-user"></i> {{ $discrepancy->user->name }}</small>
                                      @else
                                        <small><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td>P{{ number_format($discrepancy->amount, 2) }}</td>
                            <td></td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                            <td colspan="3" class="text-center"><b><h4>No Adjustment</h4></b></td>
                        </tr>
                      @endif
                      <tr id="totalDiscrepancy" style="border-top: 2px solid #3c8dbc;">
                        <td><b>Total Adjustment</b></td>
                        <td><b>P{{ number_format($data['total_discrepancy'],2) }}</b></td>
                        <td></td>
                      </tr>
                      <!-- END OF DISCREPANCY -->
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- END OF ADJUSTMENT BOX -->

              <!-- SPECIAL DISCOUNT BOX -->
              <div class="box-body" style="padding-top:25px;">  
                <div class="col-md-12 col-lg-12">
                  <!-- SPECIAL DISCOUNT TABLE -->
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
                      @if( count($data['special_discount_lists']) > 0 )
                        @foreach($data['special_discount_lists'] as $specialDiscount)
                          <tr class="tr-mypayments">
                            <td>
                              <table class="tf-table-table table m-b-0">
                                <tbody>
                                  <tr>
                                    <td style="vertical-align: middle; padding: 0; width: 35%">
                                      <span>{{ $specialDiscount->description }}</span>&nbsp;&nbsp;
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      <small>{{ date("F d, Y | h:i a", strtotime($specialDiscount->created_at)) }}</small>
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%">
                                      @if($specialDiscount->user)
                                        <small><i class="fa fa-user"></i> {{ $specialDiscount->user->name }}</small>
                                      @else
                                        <small v-else><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                            <td>P{{ number_format($specialDiscount->amount, 2) }}</td>
                            <td></td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                            <td colspan="3" class="text-center"><b><h4>No Special Discounts</h4></b></td>
                        </tr>
                      @endif
                      <tr id="totalSpecialDiscount" style="border-top: 2px solid #3c8dbc;">
                        <td><b>Total Special Discount</b></td>
                        <td><b>P{{ number_format($data['total_special_discount'], 2) }}</b></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- END OF SPECIAL DISCOUNT TABLE -->
                </div>
              </div>
              <!-- END OF SPECIAL DISCOUNT BOX -->

              <!-- PAYMENT HISTORY BOX -->
              <div class="box-body" style="padding-top:25px;">  
                <div class="col-md-12 col-lg-12">
                  <!-- PAYMENT HISTORY TABLE -->
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
                      @if( count($data['payment_histories']) > 0 )
                        @foreach($data['payment_histories'] as $payment)
                          <tr class="tr-mypayments">
                            <td> 
                              <table class="table m-b-0">
                                <tbody>
                                  <tr>
                                    <td style="vertical-align: middle; padding: 0; width: 35%">
                                      <div style="display: inline-block;">

                                        <b><i class="fa {{$payment->paymentMethod->icon}}"></i>&nbsp; {{ $payment->paymentMethod->name }}</b>
                                      </div>
                                      <div style="display: inline-block;">&nbsp; - &nbsp;{{ $payment->payment_for }}</div> 
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%;">
                                      <small>{{ date("F d, Y | h:i a", strtotime($payment->created_at)) }}</small>
                                    </td>
                                    <td style="vertical-align: middle; padding: 0; width: 20%;">
                                      @if($payment->user)
                                        <small><i class="fa fa-user"></i> {{ $payment->user->name }}</small>
                                      @else
                                        <small><i class="fa fa-question-circle"></i> Unknown</small>
                                      @endif
                                    </td>
                                  </tr>
                                </tbody>
                              </table>                        
                            </td>
                            <td>P{{ number_format($payment->amount, 2) }}</td>
                            <td></td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                            <td colspan="3" class="text-center"><b><h4>No Payment History</h4></b></td>
                        </tr>
                      @endif
                      <tr id="totalPaymentHistory" style="border-top: 2px solid #3c8dbc;">
                        <td><b>Total Payment History</b></td>
                        <td><b>P{{ number_format($data['total_payment_history'], 2) }}</b></td>
                        <td></td>
                      </tr>

                    </tbody>
                  </table>
                  <!-- END OF PAYMENT HISTORY TABLE -->

                  <!-- REMAINING BALANCE TABLE -->
                  <table class="pull-right">
                    <tbody>
                      <!-- REMAINING BALANCE -->
                      <tr id="remainingBalance" class="pull-right" >
                        <td><h4>Remaining Balance: </h4></td>
                        <td>
                            <h4><b>P{{ number_format($data['remaining_balance'], 2) }}</b></h4>
                        </td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- END OF REMAINING BALANCE TABLE -->
                </div>
              </div>
              <!-- END OF PAYMENT HISTORY BOX -->
            </div>

          </div>
        </div>

      </div>

  </section>
@endsection

@push('after_styles')
  <style type="text/css">
    #tuitionTabe tbody tr:not(.thead) {
      text-indent: 15px !important;
    }
  </style>
@endpush