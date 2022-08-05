@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Chart Of Accounts
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Chart Of Accounts</li>
        </ol>
    </section>
@endsection

@push('after_styles')
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">

    <div class="col-md-12">
      <div class="hidden-print with-border">
        <a href="{{ URL::to('admin/quickbooks/chart-of-accounts/create') }}" class="btn btn-primary">Add Account</a>
      </div>
    </div>
  <br><br>
    <div class="col-md-12">

      <div class="box">


        <div class="box-header with-border">
            {{-- <a href="#" class="btn btn-primary">Add Profit And Loss Statement</a> --}}
          </div>
        
        <div class="box-body">
          {{-- {{ dd($customers) }} --}}

          <table id="jstree_table" class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Name</th>
              <th>Type</th>
              <th>Detail Type</th>
              <th>Quickbooks Balance</th>
              <th>Bank Balance</th>
              <th>Action</th>
            </thead>  
            <tbody>
                @foreach($chartOfAccounts as $account)
                      <tr data-tt-id="{{ $account->Id }}" {{ $account->ParentRef !== null ? 'data-tt-parent-id=' . $account->ParentRef : '' }}>
                        <td>
                          <a href="chart-of-accounts/{{ $account->Id }}"> 
                            {{ $account->Name }}
                          </a>  
                        </td>
                        <td>{{ $account->AccountType }}</td>
                        <td>{{ trim(ucwords(implode(' ', preg_split('/(?=[A-Z])/', $account->AccountSubType)))) }}</td>
                        <td>{{ $account->CurrencyRef }} {{ number_format($account->CurrentBalance, 2) }}</td>
                        <td></td>
                        <td>
                          <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" type="button" data-toggle="dropdown">
                              Account History <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                              <li><a href="#">Connect Bank</a></li>
                              <li><a href="#">Edit</a></li>
                              <li><a href="#">Make Inactive</a></li>
                              <li><a href="#">Run Report</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    {{-- @endif --}}
                  {{-- @endif --}}
                @endforeach
              {{-- @endforeach --}}
            </tbody>
          </table>
            
        </div>

      </div>
      </div>
    </div>
@endsection

@push('after_scripts')

  <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="http://culmat.github.io/jsTreeTable/treeTable.js"></script>
  
  <script>
  com_github_culmat_jsTreeTable.register(this)
  
  treeTable($('#jstree_table'))
  </script>
@endpush