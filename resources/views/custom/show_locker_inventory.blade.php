<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
  <title>Locker Inventory</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

  {{-- {{ dd($inventory[0]) }} --}}
  {{-- {{ dd($inventory[0]->student->schoolYear->schoolYear) }} --}}
  {{-- <div class="text-center">
    <img src="/{{ Config::get('settings.schoollogo')}}" width="150">
    <p><b>{{Config::get('settings.schoolname')}}</b><br/>
    {{Config::get('settings.schooladdress')}}</p>
  </div> --}}

  <div class="container col-lg-3 mt-5">
      <table class="table table-bordered table-striped">
        <tbody>
          <tr>
            <td><b>Locker Names</b></td>
            @if(isset($inventory[0]->name))
              <td>{{ $inventory[0]->name }}</td>
            @else
              <td></td>
            @endif
          </tr>

          <tr>
            <td><b>Student Name</b></td>
            @if(isset($inventory[0]->student))
              <td>{{ title_case($inventory[0]->student->firstname . ' ' . $inventory[0]->student->lastname) }}</td>
            @else
              <td></td>
            @endif
          </tr>

          <tr>
            <td><b>Grade</b></td>
            @if(isset($inventory[0]->student))
              <td>{{ $inventory[0]->student->yearManagement->year }}</td>
            @else
              <td></td>
            @endif
          </tr>

          <tr>
            <td><b>Year</b></td>
            @if(isset($inventory[0]->student))
              <td>{{ $inventory[0]->student->schoolYear->schoolYear }}</td>
            @else
              <td></td>
            @endif
          </tr>

          <tr>
            <td><b>Section</b></td>
            <td>block</td>
          </tr>

          <tr>
            <td><b>Building Deployed</b></td>
            <td>{{$inventory[0]->building->name}}</td>
          </tr>
          
          <tr>
            <td><b>Date Issued</b></td>
            @if(isset($inventory[0]->updated_at))
              <td>{{ \Carbon\Carbon::parse($inventory[0]->updated_at)->format('Y-m-d') }}</td>
            @else
              <td></td>
            @endif
          </tr>

          
        </tbody>
      </table>
      {{-- {{ $inventory}} --}}
     
  </div>
</body>
</html>