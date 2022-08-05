@extends('backpack::layout')

@section('header')

@endsection

@section('content')
  
  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>
  
  <div class="row p-l-20 p-r-20">
    <!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 10px;">
      <!-- START STUDENT LIST -->
        <div class="box shadow" style="border-radius: 10px;">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="row">
              <div style="padding: 10px 20px;">
                <h4 style="padding-bottom: 10px;">Class Recordings</h4>
                <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
                  <thead>
                    <th>Length</th>
                    <th>Size</th>
                    <th>Published</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                    @if(count($recordings) > 0)

                      {{-- {{ dd(gmdate("H:i:s", 116)) }} --}}
                      @foreach ($recordings as $record)
                        <tr>
                          <td>{{ gmdate("H:i:s", (int)$record->playback->format->processingTime) }}</td>
                          <td>{{ $record->size }}</td>
                          <td>{{ $record->published ? 'Yes' : 'No' }}</td>
                          <td>
                            <a href="{{ $record->playback->format->url }}" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-play"></i> Play</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Publish</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Update</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                          </td>
                        </tr>  
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">
                          No Recordings Found
                        </td>
                      </tr>
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Length</th>
                      <th>Size</th>
                      <th>State</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      <!-- END STUDENT LIST -->
    </div>

  </div>
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
@endsection

@section('after_scripts')
  <script>
      document.getElementById("nav-classes").classList.add("active");
  </script>

@endsection
