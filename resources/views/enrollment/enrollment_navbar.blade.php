<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-btn-group">

  <div class="col-lg-2 col-md-2 hidden-sm hidden-xs"></div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5ths">
  
    <div class="small-box bg-green shadow">
      
      <div class="icon">
        <i class="fa fa-user"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/enrollment/create') ? 'active' : '' }}" href="{{url('admin/enrollment/create')}}">Enroll Student</a>
      </div>
    </div>

  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5ths">


    <div class="small-box bg-aqua shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/enrollment-applicant') ? 'active' : '' }}" href="{{url('admin/enrollment-applicant')}}">Enroll Applicants</a>
      </div>
    </div>

  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5ths">


    <div class="small-box bg-yellow shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/enrollment') ? 'active' : '' }}" href="{{url('admin/enrollment')}}">Enroll List</a>
      </div>
    </div>

  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5ths">


    <div class="small-box bg-red shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/enrollment-report') ? 'active' : '' }}" href="{{url('admin/enrollment-report')}}">Enroll Reports</a>
      </div>
    </div>

  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5ths">


    <div class="small-box bg-blue shadow">
      
      <div class="icon">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="inner" style="z-index: 2;">
        <a class="navbar-btn {{(url()->current()) == url('admin/enrollment-status') ? 'active' : '' }}" href="{{url('admin/enrollment-status')}}">Enroll Status</a>
      </div>
    </div>

  </div>



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