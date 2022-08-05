
<div id="navbar-default nav-new" class="classroom-navbar">

  <nav class="navbar navbar-default nav-new shadow" role="navigation" style="border:none; border-radius: 15px;background-color: white;">

      <div class="" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-left" style="display: flex;">

          <!-- EXPLORE -->
          <li id="nav-explore" class="text-center">
              <a href="{{ url('student/online-post') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/explore.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin: 0;">Explore</p>
              </a>
          </li>

          <!-- CLASSES -->
          <li id="nav-classes" class="text-center">
                <a href="{{ url('student/online-class') }}">
                      <img class="oc-nav-icon" src="{{ asset('images/icons/class.png') }}" alt="...">
                      <p class="oc-nav-label" style="margin:0;">My Classes</p>
                 </a>
          </li>

          <!-- CLASS COURSE -->
          @if(isset($class))
            @if($class->course)
              <li id="nav-courses" class="text-center">
                <a href="{{ url('student/online-class/course/' . $class->code) }}">
                    <img class="oc-nav-icon" src="{{ asset('images/icons/course.png') }}" alt="...">
                    <p class="oc-nav-label" style="margin:0;">My Course</p>
                </a>
              </li>
            @endif
          @endif 

          <!-- ASSIGNMENTS -->
          <li id="nav-assignment" class="text-center">
            @if(isset($class))
              <a href="{{ url('student/online-class/' . $class->code . '/assignments') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin:0;">Assignment</p>
             </a>
            @else
              <a href="{{ url('student/online-class-assignments') }}">
                  <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">
                  <p class="oc-nav-label" style="margin:0;">Assignment</p>
              </a>
            @endif
          </li>

          <!-- Quizzes -->
          <li id="nav-quiz" class="text-center">
            @if(isset($class))
              <a href="{{ url('student/online-class/' . $class->code . '/quizzes') }}">
            @else
              <a href="{{ url('student/online-class-quizzes') }}">
            @endif
                <img class="oc-nav-icon" src="{{ asset('images/icons/assignment.png') }}" alt="...">
                <p class="oc-nav-label" style="margin:0;">Quiz</p>
              </a>
          </li>
        </ul>
      </div>

  </nav>

</div><!--  end navbar -->

