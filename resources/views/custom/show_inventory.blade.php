<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <title>Asset Inventory</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="text-center mt-2">
    <img src="/{{\Config::get('settings.schoollogo')}}" width="90">
    <p>
      {{\Config::get('settings.schoolname')}} <br/>
      <small>{{\Config::get('settings.schooladdress')}}</small>
    </p>
    
  </div>

  <div class="container col-lg-10 mt-2">
      
      {{-- {{ dd($inventory->room->name)}} --}}

    <h1 class="text-center mt-5"> {{ $inventory->name }} - {{ $inventory->room->name }}</h1>
    {{-- <h3 class="text-center">{{ \App\Models\Building::find($building_id)->name }}</h3> --}}
    <div class="container mt-5">
      

        <table class="table table-sm table-bordered table-striped">
          <thead>
            <th colspan="2" class="text-center text-uppercase">Asset Inventory Information</th>
          </thead>
          <tbody>
            <tr>
              <td><b>Name</b></td>
              <td>{{ $inventory->name }}</td>
            </tr>

            <tr>
              <td><b>Description</b></td>
              <td>{{ $inventory->description }}</td>
            </tr>

            <tr>
              <td><b>Serial No.</b></td>
              <td>{{ $inventory->serialno }}</td>
            </tr>

            <tr>
              <td><b>Room</b></td>
              <td>{{ $inventory->room->name }}</td>
            </tr>

            <tr>
              <td><b>Remarks</b></td>
              <td>{{ $inventory->remarks }}</td>
            </tr>

            <tr>
              <td><b>Condition</b></td>
              <td>{{ $inventory->condition }}</td>
            </tr>
          </tbody>
        </table>

        @if($inventory->items)
              <table class="table table-sm table-bordered table-striped">
                <thead>
                  <th colspan="4" class="text-center text-uppercase">Additional Internal Inventory</th>
                </thead>
                <tbody>
                  <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Serial No.</th>
                  </tr>
                  
                    @foreach(json_decode($inventory->items) as $item)
                      <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->serial}}</td>
                      </tr>
                    @endforeach

                </tbody>
              </table>
        @endif

          
        {{ csrf_field() }}
          <div class="col-md-12 p-0 mb-2">
            <button class="btn btn-success btn-block" id="btn-edit" data-toggle="modal" data-target="#editModal">
              <i class="fab fa-arrow-alt-circle-right"></i> Move this Asset 
            </button>
            <div class="clearfix"></div>
          </div>
       
        <table class="table table-sm table-bordered table-striped" id="logs">
          <thead>
            <tr>
              <th colspan="5" class="text-center text-uppercase">Movements Logs</th>
            </tr>
            <tr>
              <th>Date</th>
              <th>Old Room</th>
              <th>New Room</th>
              <th>Description</th>
              <th>Updated By</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $log)
            <tr>
              <td>{{ $log->created_at->toFormattedDateString() }} {{ $log->created_at->format('g:i A') }}</td>
              <td>{{ $log->oldRoom->name }}</td>
              <td>{{ $log->room->name }}</td>
              <td>{{ $log->description }}</td>
              <td>{{ $log->user_name }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </form>
    </div>
       
  </div>


  <!-- The Modal -->
<div class="modal" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

        <form action="{{ url('/asset-inventory/'.$inventory->id.'/update') }}" method="GET">
          <!-- Modal body -->
          <div class="modal-body">
            
              {{ csrf_field() }}
              <div class="form-group col-md-12">
                <label for="room">Select Room</label>
                <select name="room_id" id="room" class="form-control form-control-sm">
                  @foreach($rooms as $room)
                    @if($room->id != $inventory->room_id)
                      <option value="{{ $room->id }}">
                        {{ $room->name }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group col-md-12">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
              </div>
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>

    </div>
  </div>
</div>

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script>
    $('#logs').DataTable();
  </script>
</body>
</html>