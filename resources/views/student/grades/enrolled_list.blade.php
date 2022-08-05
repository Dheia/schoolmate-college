@extends("backpack::layout_student")

@section('header')
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
          <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('student/enrollments') }}">Enrollments</a></li>
          <li><a class="text-capitalize active">Grades</a></li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">Grades</span>
        <small>All grades</small>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <div class="row">

    <div class="col-xs-12 m-b-15">
      <div class="hidden-print with-border">
        {{-- <a href="{{ URL::to('admin/quickbooks/customer/create') }}" class="btn btn-primary">Add Customer</a> --}}
      </div>
    </div>

    <div class="col-md-12">
      
   
      <div class="box">
        <div class="box-body">

          
          <table class="table enrolled-list table-bordered table-striped">
            <thead>
              <th>School Year</th>
              <th>Grade/Level</th>
              <th>Term</th>
              <th>Section</th>
              <th>Date Enrolled</th>
              <th>Action</th>
            </thead>
            <tbody>
              @if(count($enrollments) > 0)
                @foreach($enrollments as $enrollment)
                  <tr>
                    <td>{{ $enrollment->schoolYear->schoolYear }}</td>
                    <td>{{ $enrollment->studentSectionAssignment ? $enrollment->studentSectionAssignment->section->level->year : 'Unknown Section' }}</td>
                    <td>{{ $enrollment->term_type }}</td>
                    <td>{{ $enrollment->studentSectionAssignment ? $enrollment->studentSectionAssignment->section->name : 'Unknow Section' }}</td>
                    <td>{{ Carbon\Carbon::parse($enrollment->created_at)->format('M. d, Y') }}</td>
                    <td>
                      @if($enrollment->studentSectionAssignment)
                      <a href="{{ url('student/grades/view?school_year_id=') . $enrollment->school_year_id . '&section_id=' . $enrollment->studentSectionAssignment->section->id . '&level_id=' . $enrollment->studentSectionAssignment->section->level_id }}" class="btn btn-xs btn-default">Open</a>
                      @endif
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
  

            {{-- {{ dd($grades) }} --}}
            
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
        $('.enrolled-list').DataTable({
          // "processing": true,
          // "serverSide": true,
          {{-- "ajax" : "{{ url('admin/quickbooks/customer/page/(:num)') }}" --}}
        });
    } );
  </script>


  <script>

  </script>

@endsection