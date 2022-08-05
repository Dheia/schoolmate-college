@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Reports
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Reports</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')

  {{-- Business Overview --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
            <h4>Business Overview</h4>
        </div>
        
        <div class="box-body">

            <div class="col-md-6">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link active" href="reports/audit-log">Audit Log</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/balance-sheet-comparison">Balance Sheet Comparison</a>
                </li>
                  <li class="nav-item">
                <a class="nav-link" href="reports/balance-sheet-detail">Balance Sheet Detail</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/balance-sheet">Balance Sheet</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-and-loss-percent-of-total-income">Profit And Loss % Of Total Income</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-loss-and-comparison">Profit Loss And Comparison</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-and-loss-year-to-date-comparison">Profit And Loss Year-To-Date Comparison</a>
                </li>
              </ul>
            </div>

            <div class="col-md-6">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-and-loss-by-customer">Profit And Loss By Customer</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-and-loss-by-month">Profit And Loss By Month</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/profit-and-loss">Profit And Loss</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/quarterly-profit-and-loss-summary">Quarterly Profit And Loss Summary</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/statement-of-cash-flows">Statement Of Cash Flows</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/statement-of-changes-in-equity">Statement Of Changes In Equity</a>
                </li>
              </ul>
            </div>
        </div>
      
      </div>
    </div>
  </div>  

  {{-- Who Owes You --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>Who Owes You</h4>
        </div>
        
        <div class="box-body">

          <div class="col-md-6">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="reports/accounts-receivable-ageing-summary">Accounts Receivable Ageing Summary</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/collections-report">Collections Report</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/customer-balance-summary">Customer Balance Summary</a>
                </li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="reports/invoice-list">Invoice List</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/open-invoices">Open Invoices</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reports/statement-list">Statement List</a>
                </li>
              </ul>
            </div>

        </div>
      
      </div>
    </div>
  </div>

  {{-- Sales And Customers --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>Sales And Customers</h4>
        </div>
        
        <div class="box-body">

          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/customer-contact-list">Customer Contact List</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/deposit-details">Deposit Details</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/estimates-by-customer">Estimates By Customer</a>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/product-service-list">Product/Service List</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/sales-by-customer-summary">Sales By Customer Summary</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/sales-by-product-service-summary">Sales By Product/Service Summary</a>
              </li>
            </ul>
          </div>

        </div>
      
      </div>
    </div>
  </div>

  {{-- Expenses And Suppliers --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>Expenses And Suppliers</h4>
        </div>
        
        <div class="box-body">
          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/check-detail">Check Detail</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/supplier-contact-list">Supplier Contact List</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/transaction-list-by-supplier">Transaction List By Supplier</a>
              </li>
            </ul>
          </div>            
        </div>
      
      </div>
    </div>
  </div>

  {{-- Employees --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>Employees</h4>
        </div>
        
        <div class="box-body">
          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/employee-contact-list">Employee Contact List</a>
              </li>
            </ul>
          </div>            
        </div>
      
      </div>
    </div>
  </div>

  {{-- For My Account --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>For My Account</h4>
        </div>
        
        <div class="box-body">

          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/account-list">Account List</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/balance-sheet-comparison">Balance Sheet Comparison</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/balance-sheet">Balance Sheet</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/general-ledger">General Ledger</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/journal">Journal</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/profit-and-loss-comparison">Profit And Loss Comparison</a>
              </li>
            </ul>
          </div>

          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/profit-and-loss">Profit And Loss</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/recent-transactions">Recent Transactions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/reconciliation-reports">Reconciliation Reports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/statement-of-cash-flows">Statement Of Cash Flows</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/transaction-list-by-date">Transaction List By Date</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reports/trial-balance">Trial Balance</a>
              </li>
            </ul>
          </div>

        </div>
      
      </div>
    </div>
  </div>

  {{-- Payroll --}}
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
          <h4>Payroll</h4>
        </div>
        
        <div class="box-body">
          <div class="col-md-6">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="reports/employee-contact-list">Employee Contact List</a>
              </li>
            </ul>
          </div>
        </div>
      
      </div>
    </div>
  </div>

@endsection

@push('after_scripts')

@endpush