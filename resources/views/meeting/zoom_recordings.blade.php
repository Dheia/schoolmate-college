@extends('backpack::layout')

@section('header')
@endsection

@section('content')
    <!-- HEADER START -->
    <div class="row" style="padding: 15px;">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
            <section class="content-header">
                <ol class="breadcrumb">
                <li>
                    <a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
                </li>
                <li>
                    <a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a>
                </li>
                <li>
                    {{ $entry->name }}
                </li>
                <li class="active">Recordings</li>
                </ol>
            </section>
            <h1 class="smo-content-title">
                <span class="text-capitalize">{!! $crud->getHeading() ?? $entry->name . ' Recordings' !!}</span>
            </h1>
            <div class="col-xs-6">
                <div id="datatable_search_stack" class="pull-left" placeholder="Search Student"></div>
            </div>
        </div>
    </div>
    <!-- HEADER END -->

  
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
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Password</th>
                        <th>Audio</th>
                        <th>Share Screen</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @if(count($recordings) > 0)
                        @foreach ($recordings as $record)
                            @php
                                $meeting      = $record->meetingable;
                                $zoom_meeting = $record->zoomMeeting;
                            @endphp
                            <tr>
                                <td>{{ date("F d, Y", strtotime($zoom_meeting->created_at)) }}</td>
                                <td>
                                    {{ date("h:i:s A", strtotime($zoom_meeting->start_time)) }}
                                </td>
                                <td>
                                    {{ date("h:i:s A", strtotime($zoom_meeting->end_time)) }}
                                </td>
                                <td>{{ $record->password }}</td>
                                <td>
                                    @if($record->audio_only)
                                        <a href="{{ $record->audio_only->play_url }}" target="_blank" class="btn btn-xs btn-info" title="Play">
                                            <i class="fa fa-play"></i>
                                        </a>
                                        <a href="{{ $record->audio_only->download_url }}" target="_blank" class="btn btn-xs btn-info" title="Download">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($record->shared_screen_with_speaker_view)
                                        <a href="{{ $record->shared_screen_with_speaker_view->play_url }}" target="_blank" class="btn btn-xs btn-info" title="Play">
                                            <i class="fa fa-play"></i>
                                        </a>
                                        <a href="{{ $record->shared_screen_with_speaker_view->download_url }}" target="_blank" class="btn btn-xs btn-info" title="Download">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url($record->share_url) }}" class="btn btn-xs btn-info" target="_blank" title="Play">
                                        <i class="fa fa-play"></i> Play
                                    </a>
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
                            <th>Date</th>
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
