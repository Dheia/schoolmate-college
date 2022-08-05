<div id="navbar-default nav-new" class="classroom-navbar">
  <nav class="navbar navbar-default nav-new shadow" role="navigation" style="border:none; border-radius: 15px;background-color: white;">
    <div class="">
      <!-- Brand and toggle get grouped for better mobile display -->
  
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-left" style="display: flex;">
              <li id="nav-explore" class="text-center">
                  <a href="{{ url('admin/teacher-online-post') }}">
                      {{-- <i class="fa fa-newspaper-o" style="font-size: 18px;"></i> --}}
                      <img class="oc-nav-icon" src="{{ asset('images/icons/explore.png') }}" alt="...">

                      <p class="oc-nav-label" style="margin: 0;">Explore</p>
                  </a>
              </li>
              <li id="nav-courses" class="text-center">
                  <a href="{{ url('admin/online-course') }}">
                      {{-- <i class="fa fa-book" style="font-size: 18px;"> --}}
                          {{-- <span class="label">23</span> --}}
                      {{-- </i> --}}
                      <img class="oc-nav-icon" src="{{ asset('images/icons/course.png') }}" alt="...">

                      <p class="oc-nav-label" style="margin:0;">My Courses</p>
                  </a>
              </li> 
              <li id="nav-classes" class="text-center">
                    <a href="{{ url('admin/teacher-online-class') }}">
                          {{-- <i class="fa fa-users" style="font-size: 18px;"></i> --}}
                          <img class="oc-nav-icon" src="{{ asset('images/icons/class.png') }}" alt="...">

                          <p class="oc-nav-label" style="margin:0;">My Classes</p>
                     </a>
              </li>

              @if(request()->class_code)
              <!-- Class Assignment -->
              <li id="nav-assignments" class="text-center">
                <a href="{{ url( request()->class_code ? 'admin/online-class/assignment?class_code=' . request()->class_code : 'admin/online-class/assignment') }}">
                      {{-- <i class="fa fa-tasks" style="font-size: 18px;"></i> --}}
                          <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">

                      <p class="oc-nav-label" style="margin:0;">Assignment</p>
                 </a>
              </li>
              @endif

              @if(request()->class_code)
              <!-- Class Assignment -->
              <li id="nav-assignments" class="text-center">
                <a href="{{ url( request()->class_code ? 'admin/online-class/quiz?class_code=' . request()->class_code : 'admin/online-class/quiz') }}">
                      {{-- <i class="fa fa-tasks" style="font-size: 18px;"></i> --}}
                          <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">

                      <p class="oc-nav-label" style="margin:0;">Quiz</p>
                 </a>
              </li>
              @endif
         </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</div><!--  end navbar -->
