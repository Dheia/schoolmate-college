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