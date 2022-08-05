@extends('backpack::layout')

@section('header')
  <section class="content-header">
    <h1>
        <span class="text-capitalize">{{ $entry->name }}</span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
      <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
      {{-- <li class="active"></li> --}}
    </ol>
  </section>
@endsection

@section('content')
  
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">
        @if ($crud->hasAccess('list'))
          <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
        @endif
        {{-- Backpack List Filters --}}


        <div class="overflow-hidden">
          {{-- @if(count($departments)>0)
            <div class="btn-group pull-left m-t-20" role="group" aria-label="...">
              @foreach($departments as $dept)
                <a href="{{ url($crud->route . '?school_year_id=' . request()->school_year_id . '&department=' . $dept->id) }}" class="btn btn-primary {{($dept->id == request()->department) ? 'active' : '' }}">{{ $dept->name }}</a>
              @endforeach
            </div>
          @endif --}}
      
          <table id="crudTable" class="box table table-striped table-hover display responsive nowrap m-t-0" cellspacing="0">
          
            <thead>
              <th>Start Date and Time</th>
              <th>Size</th>
              <th>Published</th>
              <th>Participants</th>
              <th>Size</th>
              <th>Processing Time</th>
              <th>Action</th>
            </thead>
            <tbody>
              @if(count($recordings) > 0)

                {{-- {{ dd(gmdate("H:i:s", 116)) }} --}}
                @foreach ($recordings as $record)
                  <tr>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($record->startTime)->toDateTimeString() }}</td>
                    <td>{{ $record->size }}</td>
                    <td>{{ $record->published ? 'Yes' : 'No' }}</td>
                    <td>{{ $record->participants }}</td>
                    <td>
                      <a href="{{ $record->playback->format->url }}" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-play"></i> Play</a>
                      {{-- <a href="javascript:void(0)" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Publish</a> --}}
                      {{-- <a href="javascript:void(0)" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Update</a> --}}
                      {{-- <a href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a> --}}
                    </td>
                    <td>
                      {{$record->playback->format->length}}
                    </td>
                    <td>
                      {{$record->playback->format->size}}
                    </td>
                    <td>
                      {{$record->playback->format->processingTime}}
                    </td>
                  </tr>  
                @endforeach
              @else
                <tr>
                  <td colspan="7" class="text-center">
                    No Recordings Found
                  </td>
                </tr>
              @endif
            </tbody>
            <tfoot>
              <tr>
                <th>Start Date and Time</th>
                <th>Size</th>
                <th>Published</th>
                <th>Participants</th>
                <th>Size</th>
                <th>Processing Time</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>

          @if ( $crud->buttons->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="hidden-print">
            @include('crud::inc.button_stack', ['stack' => 'bottom'])

            <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
          </div>
          @endif

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>


@endsection
