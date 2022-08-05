<ul class="nav nav-tabs" id="userTab">
  @php
    $user = 'student';
    
    if(isset($_GET['searchFor']) && $_GET['searchFor'] == 'employee') {
        $user = 'employee';
    }

    if(isset($_GET['searchFor']) && $_GET['searchFor'] == 'visitor') {
        $user = 'visitor';
    }
  @endphp

  @if(str_contains(\Request::url(), 'create'))
    <li id="studentTab" user-type="student" class="{{ $user == 'student' ? 'active' : '' }}">
    	<a href="{{ url($crud->route) }}/create?searchFor=student">Student</a>
    </li>
    <li id="employeeTab" user-type="employee" class="{{ $user == 'employee' ? 'active' : '' }}">
    	<a href="{{ url($crud->route) }}/create?searchFor=employee">Employee</a>
    </li>
    <li id="visitorTab" user-type="visitor" class="{{ $user == 'visitor' ? 'active' : '' }}">
      <a href="{{ url($crud->route) }}/create?searchFor=visitor">Visitor</a>
    </li>

  @endif
  
  @if(str_contains(\Request::url(), 'edit'))
    <li id="studentTab" user-type="student" class="{{ $user == 'student' ? 'active' : 'disabled' }}">
      <a href="javascript:void(0)">Student</a>
    </li>
    <li id="employeeTab" user-type="employee" class="{{ $user == 'employee' ? 'active' : 'disabled' }}">
      <a href="javascript:void(0)">Employee</a>
    </li>
    <li id="visitorTab" user-type="visitor" class="{{ $user == 'visitor' ? 'active' : '' }}">
      <a href="{{ url($crud->route) }}/create?searchFor=visitor">Visitor</a>
    </li>
  
  @endif
</ul>

@section('after_scripts')
  <script>
    var uType = $('#userTab li[class=active]').attr('user-type');
    $('input[name=user_type]').val(uType);
  </script>
@endsection