@extends('backpack::layout_student')

@section('header')
    <section class="content-header">
      <h1>
        Statement of Account<small>All enrollments list</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Statement of Account</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="row">

      <div class="col-md-12">
        
        <div class="box">
          <div class="box-body">
            <table class="table table-sm table-bordered">
              <thead>
                <th>School Year</th>
                <th>Department</th>
                <th>Year Level</th>
                <th>Track</th>
                <th>Term</th>
                <th>Tuition</th>
                <th>Commitment Payment</th>
                <th>Actions</th>
              </thead>
              <tbody>
                @foreach($enrollments as $enrollment)
                  <tr>
                    <td>{{ $enrollment->schoolYear->schoolYear }}</td>
                    <td>{{ $enrollment->department->name }}</td>
                    <td>{{ $enrollment->level->year }}</td>
                    <td>{{ $enrollment->track->code }}</td>
                    <td>{{ $enrollment->term_type }}</td>
                    <td>{{ $enrollment->tuition->form_name }}</td>
                    <td>{{ $enrollment->commitmentPayment->name }}</td>
                    <td>
                      <a href="{{ url()->current() . '/' . $enrollment->id }}" class="btn btn-sm btn-primary">Open</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
    
          </div>
        </div>
      </div>

  </section>
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
    $('.table').DataTable({
      "processing": true,
      "paging": false,
      "searching": true,
    });
  </script>
    
@endsection