<div id="navbar-default nav-new" class="classroom-navbar">
  <nav class="navbar navbar-default nav-new shadow" role="navigation" style="border:none; border-radius: 15px;background-color: white;">
    <div class="">
      <div class="" id="bs-example-navbar-collapse-1">

        <ul class="nav navbar-nav navbar-left" style="display: flex;">

              <!-- Explore -->
              <li id="nav-explore" class="text-center">
                <a href="{{ url( request()->class_code ? 'admin/online-post?class_code=' . request()->class_code : 'admin/online-post') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/explore.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin: 0;">Explore</p>
                </a>
              </li>

              <!-- Courses -->
              <li id="nav-courses" class="text-center">
                <a href="{{ url('admin/online-course') }}">
                    <img class="oc-nav-icon" src="{{ asset('images/icons/course.png') }}" alt="...">
                    <p class="oc-nav-label" style="margin:0;">My Courses</p>
                </a>
              </li> 

              <!-- Classes -->
              <li id="nav-classes" class="text-center">
                <a href="{{ url('admin/teacher-online-class') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/class.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin:0;">My Classes</p>
                 </a>
              </li>

              <!-- Class Assignment -->
              <li id="nav-assignments" class="text-center">
                <a href="{{ url( request()->class_code ? 'admin/online-class/assignment?class_code=' . request()->class_code : 'admin/online-class/assignment') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin:0;">Assignment</p>
                 </a>
              </li>

              <!-- Class Quiz -->
              <li id="nav-quizzes" class="text-center">
                <a href="{{ url( request()->class_code ? 'admin/online-class/quiz?class_code=' . request()->class_code : 'admin/online-class/quiz') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin:0;">Quiz</p>
                 </a>
              </li>
         </ul>

      </div>
    </div>
  </nav>
</div><!--  end navbar -->
