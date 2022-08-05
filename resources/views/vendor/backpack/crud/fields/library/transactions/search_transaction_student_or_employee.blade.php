<ul class="nav nav-tabs" id="userTab">
  @php
    $user = 'student';
    
    if(isset($_GET['searchFor']) && $_GET['searchFor'] == 'employee') {
        $user = 'employee';
    }
  @endphp
    <li id="studentTab" user-type="student" class="{{ $user == 'student' ? 'active' : '' }}">
        <a href="{{ url($crud->route) }}?searchFor=student">Student</a>
    </li>
    <li id="employeeTab" user-type="employee" class="{{ $user == 'employee' ? 'active' : '' }}">
        <a href="{{ url($crud->route) }}?searchFor=employee">Employee</a>
    </li>
</ul>

@push('crud_fields_scripts')
  <script>
    var uType = $('#userTab li[class=active]').attr('user-type');
    $('input[name=user_type]').val(uType);
  </script>
@endpush
