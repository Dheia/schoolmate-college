<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 smo-btn-group">

{{--   <div class="col-lg-4 col-md-4 hidden-sm hidden-xs"></div> --}}

  <div class="col-lg-2 col-md-2 col-xs-4">
  
    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-user"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/schoolyear') ? 'active' : '' }}" href="{{url('admin/schoolyear')}}">School Years</a>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-xs-4">

    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/department') ? 'active' : '' }}" href="{{url('admin/department')}}">Academic Departments</a>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-xs-4">

    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/non-academic-department') ? 'active' : '' }}" href="{{url('admin/non-academic-department')}}">Non-Academic Departments</a>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-xs-4">

    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/year_management') ? 'active' : '' }}" href="{{url('admin/year_management')}}">Levels</a>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-xs-4">

    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-user"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/strand') ? 'active' : '' }}" href="{{url('admin/strand')}}">Strands</a>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-xs-4">
  <div class="small-box bg-aqua shadow">
    
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      <a class="navbar-btn {{(url()->current()) == url('admin/course_management') ? 'active' : '' }}" href="{{url('admin/course_management')}}">Courses</a>
    </div>
  </div>
</div>
  <div class="col-lg-2 col-md-2 col-xs-4">

  <div class="small-box bg-aqua shadow">
    
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      <a class="navbar-btn {{(url()->current()) == url('admin/term-management') ? 'active' : '' }}" href="{{url('admin/term-management')}}">Terms</a>
    </div>
  </div>
</div>
  <div class="col-lg-2 col-md-2 col-xs-4">

  <div class="small-box bg-aqua shadow">
    
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      <a class="navbar-btn {{(url()->current()) == url('admin/period') ? 'active' : '' }}" href="{{url('admin/period')}}">Periods</a>
    </div>
  </div>
</div>
<!-- 

  <div class="small-box bg-yellow shadow">
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      Users
    </div>
  </div>
  <div class="small-box bg-yellow shadow">
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      Roles
    </div>
  </div>

   <div class="small-box bg-yellow shadow">
    <div class="icon">
      <i class="fa fa-file-text-o"></i>
    </div>
    <div class="inner" style="z-index: 2;">
      Permissions
    </div>
  </div> -->

</div>


          <!-- <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 btn-no-spacing">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                <a id="btnAddStudent" href="{{ url($crud->route.'/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add Student</a>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                <a id="" href="#" class="btn btn-success"><i class="fa fa-file-text-o"></i> Student Reports</a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                <a id="" href="#" class="btn btn-success"><i class="fa fa-file-text-o"></i> Requirements</a>
            </div>

          </div> -->