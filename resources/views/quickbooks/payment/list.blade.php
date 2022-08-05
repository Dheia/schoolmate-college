@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Payments
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Payments</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
            <a href="{{ url()->current() }}/create" class="btn btn-primary">Add Payment</a>
          </div>
        
        <div class="box-body">
          {{-- {{ dd($customers) }} --}}

          <table class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Date</th>
              <th>No.</th>
              <th>Contact</th>
              <th>Amount</th>
              <th>Last Moified Date</th>
            </thead>  
            <tbody>
              @foreach($payments as $payment)
                @foreach($customers as $customer)
                  @if($payment->CustomerRef == $customer->Id)
                    <tr>
                      <td>{{ $payment->TxnDate }}</td>
                      <td>{{ $payment->PaymentRefNum }}</td>
                      <td>{{ $customer->DisplayName }}</td>
                      <td>{{ $payment->TotalAmt }} {{ $customer->CurrencyRef }}</td>
                      <td>{{ \Carbon\Carbon::parse($payment->MetaData->LastUpdatedTime)->format('m-d-y, H:i a') }}</td>
                    </tr>
                  @endif
                @endforeach
              @endforeach
            </tbody>
          </table>
            
        </div>

      </div>
      </div>
    </div>
@endsection

@push('after_scripts')

@endpush