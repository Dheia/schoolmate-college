@extends('backpack::layout_parent')

@section('header')
    {{-- <section class="content-header">
      <h1>
        Enrollments<small>All enrollments list</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Enrollments</li>
      </ol>
    </section> --}}
@endsection

@section('after_styles')
  <style>
     @media only screen and (min-width: 768px) {
        #welcomeImage {
          float: right;
        }
        .profile-user-img{
          display: block;
        }
        .profile{
          margin-top:35px
        }
        
        .content-wrapper{
      border-top-left-radius: 60px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    .main-footer{
      border-bottom-left-radius: 60px;
    
    }
    
  </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
          <li><a class="text-capitalize active">Enrollments</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Enrollment List</span>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <section class="row">
      <!-- STUDENT INFO -->
      <div class="col-md-12 m-r-15">
        <div class="box">
          <div class="box-body">
            <div class="col-md-3 col-xs-5" style="padding-right: 0;">
              <h5><b>Student ID:</b></h5>
            </div>
            <div class="col-md-3 col-xs-7" style="padding-left: 0;">
              <h5>{{ $student->studentnumber }}</h5>
            </div>
            <div class="col-md-3 col-xs-5" style="padding-right: 0;">
              <h5><b>Fullname:</b></h5>
            </div>
            <div class="col-md-3 col-xs-7" style="padding-left: 0;">
              <h5>{{ $student->fullname }}</h5>
            </div>
          </div>
        </div>
      </div>
      <!-- END OF STUDENT INFO -->

      <!-- STUDENT ENROLLMENT LIST -->
      <div class="col-md-12 m-r-15">   
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered" id="enrollments_table">
                <thead>
                  <th>School Year</th>
                  <th>Department</th>
                  <th>Year Level</th>
                  <th>Track</th>
                  <th>Term</th>
                  <th>Tuition</th>
                  <th>Commitment Payment</th>
                  <th>Balance</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  @foreach($enrollments as $enrollment)
                    <tr>
                      <td>{{ $enrollment->schoolYear->schoolYear }}</td>
                      <td>{{ $enrollment->department->name }}</td>
                      <td>{{ $enrollment->level->year }}</td>
                      <td>{{ $enrollment->track->code ?? '-' }}</td>
                      <td>{{ $enrollment->term_type }}</td>
                      <td>{{ $enrollment->tuition->form_name }}</td>
                      <td>{{ $enrollment->commitmentPayment->name }}</td>
                      <td>
                        <b style="{{ $enrollment->remaining_balance > 0 ? 'color: red;' : '' }}">
                          <!-- Peso Sign (&#8369;) -->
                          &#8369; {{ number_format((float)$enrollment->remaining_balance, 2) }}
                         </b>
                      </td>
                      <td>
                        @if($enrollment->invoice_no)
                          <a href="{{ url('parent/online-payment/' . $enrollment->id) }}" class="btn btn-success btn-sm w-100" >
                            Pay Online
                          </a>
                          <br>
                        @endif
                        <a href="{{ url()->current() . '/tuition/' . $enrollment->id}}" class="btn btn-sm btn-primary w-100">
                          View Tuition
                        </a>
                        <br>
                        @if($enrollment->studentSectionAssignment)
                          <a href="{{ url()->current() . '/grade/' . $enrollment->id}}" class="btn btn-sm btn-primary w-100">
                            View Grades
                          </a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- END OF STUDENT ENROLLMENT LIST -->

      <!-- Payment Modal -->
      {{-- <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: .50rem;">
                <div class="modal-header text-center">
                  <h3 class="modal-title" id="paymentModalLabel" style="color: #0e6ea6;">Online Payment</h3>
                 <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button> -->
                </div>
                <div class="modal-body">
                  @if ($errors->count())
                      <div id="form-error" class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <form id="paymentForm" role="form" method="POST" action="{{ url('parent/online-payment') }}">
                    @csrf

                    <input type="hidden" id="enrollment_id" name="enrollment_id">
                    <input type="hidden" id="school_year_id" name="school_year_id">
                    <input type="hidden" id="studentnumber" name="studentnumber" value="{{ $student->studentnumber }}">

                    <!-- Amount -->
                  <div class="form-group required">
                    <label for="amount">Amount</label>
                  <input class="form-control" type="number" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Amount" autocomplete="off">
                  <span class="fee float-right">Fee: <span class="amount-fee">0</span></span>
                </div>

                <!-- Email -->
                <div class="form-group required">
                  <label for="email">Email</label>
                  <input class="form-control" type="text" name="email" value="{{ old('email') }}" placeholder="E-mail">
                </div>

                <!-- Description -->
                <div class="form-group required">
                  <label for="description">Description</label>
                  <textarea class="form-control" type="text" name="description" placeholder="Description">{{ old('description') }}</textarea>
                </div>

                <!-- Payment Method -->
                <div class="form-group required" style="margin-bottom: 0;">
                  <label for="payment_method_id">Payment Method</label>
                  <select class="payment-method form-control" style="outline: none;" name="payment_method_id" id="payment_method_id">
                    @if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics")
                      <option selected disabled>Select Payment Method</option>
                      @if(isset($paymentMethods))
                        @foreach($paymentMethods as $paymentMethod)
                          @if(strtolower($paymentMethod->name) === "cash")
                          @else
                            <option value="{{ $paymentMethod->id }}" 
                              fee="{{ $paymentMethod->fee }}" 
                              fixed-amount="{{ $paymentMethod->fixed_amount }}"
                              {{ $paymentMethod->id == old("payment_method_id") ? 'selected' : '' }}>
                              {{ $paymentMethod->name }}
                            </option>
                          @endif
                        @endforeach
                      @endif
                    @else
                      @foreach($paymentMethods as $paymentMethod)
                        @if(strtolower($paymentMethod->name) === "paypal")
                          <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                        @endif
                      @endforeach
                    @endif
                        </select>
                </div>
                @if(config('settings.paymentnotes') !== '')
                  <span style="color: orange; font-size: 11px;">NOTE: 
                    <span>{{ config('settings.paymentnotes') }}</span>
                  </span>
                @endif
                  </form>
                </div>
                <div class="modal-footer">
                  <button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button id="submitPayment" type="button" class="btn" style="color: #fff; background-color: #007bff; border-color: #007bff;">
                    Make Payment
                  </button>
                </div>
            </div>
          </div>
      </div> --}}

  </section>
</body>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@section('after_scripts')
  
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <script>
    $('#enrollments_table').DataTable({
      "processing": true,
      "paging": false,
      "searching": true,
    });
  </script>

  {{-- <script>
    var button          = '';
    var enrollment_id   = '';
    var school_year_id  = '';
    var amount          = '';

    @if($errors->count())
      $('#paymentModal').modal('show');
      $('#enrollment_id').val("{{ old('enrollment_id')  }}");
		  $('#school_year_id').val("{{ old('school_year_id')  }}");
      getFee();
    @endif

    $('#paymentModal').on('show.bs.modal', function (event) {
      button          = $(event.relatedTarget);
      enrollment_id   = button.data('id');
      school_year_id  = button.data('sy');
      amount          = button.data('amount');

      $('#enrollment_id').val(enrollment_id);
      $('#school_year_id').val(school_year_id);
      $('#amount').val(amount);
      var modal = $(this);
    });

    var paymentMethods        = {!! isset($paymentMethods) ? json_encode($paymentMethods) : null !!};
    var select_payment_method = $('select[name="payment_method_id"]');
    var payment_method_id     = select_payment_method.find('option:selected').val();

    select_payment_method.change(function () {
      getFee();
    });

    $('input[name="amount"]').keyup(function () { getFee(); });
    $('#submitPayment').click(function () { submitPayment(); });

    function submitPayment ()
    {
      $('#paymentForm').submit();
    }

    function getFee()
    {
      let amount        = $('input[name="amount"]');
      select_payment_method   = $('select[name="payment_method_id"]');

      let fee         = select_payment_method.find('option:selected').attr('fee');
      let fixedAmount = select_payment_method.find('option:selected').attr('fixed-amount');

      let fee_percent = parseFloat(fee) * (12/100); //.27
      let total_tax   = parseFloat(fee) + parseFloat(fee_percent); // 2.25 + .27 = 2.52

      let total_with_tax = -(parseFloat(total_tax) - 100);
      let total_fee   = ((parseFloat(amount.val()) / parseFloat(total_with_tax/100)) - parseFloat(amount.val()));

      total_fee = typeof total_fee === "undefined" ? 0 : parseFloat(total_fee);
      total_fee = total_fee > 30 ? total_fee : parseFloat(30); 

      // if(isNaN(amount.val())) {
      //   $('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(fixedAmount));
      //   return;
      // }

      $('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( total_fee ) );
    }
  </script> --}}
    
@endsection