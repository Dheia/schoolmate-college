@extends("backpack::layout")

@section('header')
    {{-- <section class="content-header">
        <h1>
          Terms --}}
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        {{-- </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Terms</li>
        </ol>
    </section> --}}
@endsection

@section('after_styles')

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

@endsection

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group">
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Terms</li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Terms</span>
        {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
      </h1>
    </div>
  </div>

  <div class="row">

    <div class="col-xs-12 m-b-15">
      <div class="hidden-print with-border">
        <a href="{{ URL::to('admin/quickbooks/term/create') }}" class="btn btn-primary">Add Term</a>
      </div>
    </div>

    <div class="col-md-12">
      
   
      <div class="box">

        {{-- <div class="box-header with-border">
        </div> --}}
        
        <div class="box-body">
          {{-- {{ dd($terms) }} --}}

          <table class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Name</th>
              <th>Due Days</th>
              <th>Action</th>
            </thead>  
            <tbody>
                @foreach($terms as $term)
                  <tr>
                    <td>
                      {{ $term->Name }}
                    </td>
                    <td>
                      {{ $term->DueDays }}
                    </td>
                    <td>
                      <a href="{{ url('admin/quickbooks/term/' . $term->Id . '/edit') }}" class="btn btn-xs btn-default">
                        <i class="fa fa-edit"></i>
                         Edit
                      </a>
                      <a href="{{ url('admin/quickbooks/term/' . $term->Id . '/delete') }}" class="btn btn-xs btn-danger">
                        <i class="fa fa-trash"></i>
                         Delete
                      </a>
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
          {{-- "ajax" : "{{ url('admin/quickbooks/term/page/(:num)') }}" --}}
        });
    } );
  </script>

@endsection