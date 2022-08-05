@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Customers
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Customer</li>
        </ol>
    </section>
@endsection

@section('after_styles')

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

@endsection

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">

    <div class="col-xs-12 m-b-15">
      <div class="hidden-print with-border">
        <a href="{{ URL::to('admin/quickbooks/customer/create') }}" class="btn btn-primary">Add Customer</a>
      </div>
    </div>

    <div class="col-md-12">
      
   
      <div class="box">

        {{-- <div class="box-header with-border">
        </div> --}}
        
        <div class="box-body">
          {{-- {{ dd($customers) }} --}}

          <table class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Name</th>
              <th>Phone</th>
              <th>Open Balance</th>
              <th>Action</th>
            </thead>  
            <tbody>
                @foreach($customers as $customer)
                      <tr>
                        <td>
                          <a href="customer/{{ $customer->Id }}"> 
                            {{ $customer->DisplayName }}
                          </a>  
                        </td>
                        @if(isset($customer->PrimaryPhone))
                          <td>{{ $customer->PrimaryPhone->FreeFormNumber }}</td>
                        @else
                          <td></td>
                        @endif
                        <td><b style="font-weight: 400;">{{ $customer->CurrencyRef }}</b>{{ number_format($customer->Balance, 2) }}</td>
                        <td>
                          
                          <div class="dropdown">
                            @if($customer->Balance > 0)
                              <a href="#">Receive Payment</a>
                            @else
                              <a href="#">Create Invoice</a>
                            @endif
                            <a href="javascript:void(0)" class="dropdown-toggle" type="button" data-toggle="dropdown">
                              <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                              <li><a href="#">Create Sales Receipt</a></li>
                              <li><a href="#">Create Estimate</a></li>
                              @if($customer->Balance > 0)
                                <li><a href="#">Create Invoice</a></li>
                              @endif
                              <li><a href="#">Create Charge</a></li>
                              <li><a href="#">Create Time Activity</a></li>
                              <li><a href="#">Create Statement</a></li>
                            </ul>
                          </div>

                        </td>
                      </tr>
                @endforeach
            </tbody>
          </table>
            
        </div>

      </div>
      </div>
    </div>
@endsection

@section('after_scripts')

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <script>
    $(document).ready( function () {
        $('.table').DataTable({
          // "processing": true,
          // "serverSide": true,
          {{-- "ajax" : "{{ url('admin/quickbooks/customer/page/(:num)') }}" --}}
        });
    } );
  </script>

@endsection