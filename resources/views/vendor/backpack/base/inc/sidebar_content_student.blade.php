
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ route('student.dashboard') }}"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<!-- Announcement -->
<li><a href="{{ route('student.announcements') }}"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-bullhorn"></i> <span>Announcement</span></a></li>

<!-- <li><a href="/student/attendance"><i class="fa fa-history"></i> <span>Attendance</span></a></li> -->
<li><a href="{{ route('student.enrollments') }}"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-list-alt"></i> <span>Enrollments</span></a></li>
{{-- <li><a href="/student/account"><i class="fa fa-list-alt"></i> <span>Statement of Account</span></a></li> --}}
{{-- <li><a href="/student/grades"><i class="fa fa-graduation-cap"></i> <span>My Grades</span></a></li> --}}
{{-- <li><a href="/student/library"><i class="fa fa-book"></i> <span>Library</span></a></li> --}}

<!-- Online Payments -->
@if(config('settings.viewstudentaccount'))
<li><a href="{{ url('/student/online-payment') }}"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-money"></i> <span>Online Payment</span></a></li>
@endif
<!-- <li><a href="{{ url('/student/online-payment') }}"><i class="fa fa-credit-card"></i> <span>Online Payment</span></a></li> -->

<!-- Attendances -->
<li class="treeview">
	<a href="/student/online-class"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-history"></i> <span>Attendances</span> <i class="fa fa-angle-left pull-right"></i></a>
			
	<ul class="treeview-menu" parent>
		<li><a href="{{ url('/student/turnstile-attendance') }}"
			style="
			width: 215px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class='fa fa-history'></i> <span>Turnstile Attendance</span></a></li>
		<li><a href="{{ url('/student/class-attendance') }}"
			style="
			width: 215px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class='fa fa-history'></i> <span>Class Attendance</span></a></li>
		<li><a href="{{ url('/student/system-attendance') }}"
			style="
			width: 215px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;
			margin-bottom:10px;"><i class='fa fa-history'></i> <span>System Attendance</span></a></li>
	</ul>
</li>
	
<li style="margin-bottom:10px;"><a href="{{ url('/student/student_pa') }}"
	style="
			width: 220px;
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"><i class="fa fa-legal"></i> <span>Insurance Policy</span></a></li>


<!-- Online Class -->
	<div class="student-box"><a href="/student/online-post" style=""><i class="fa fa-pencil-square fa-2x" style="margin-left:10px; margin-top:7px;"></i> <span style="font-size:21px;"><b style="font-family:sans-serif; margin-left:5px">Online Class</b></span></a></div>
	
		<li ><a href="{{ url('/student/online-post') }}" 
		style="
			width: 200px; margin-left:20px; 
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"> <span>Dashboard</span></a></li>

		<li><a href="{{ url('/student/online-class') }}" 
		style="
			width: 200px; margin-left:20px; 
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"> <span>My Classes</span></a></li>
			
		<li><a href="{{ url('/student/online-class-assignments') }}" 
		style="
			width: 200px; margin-left:20px; 
			border-radius: 0px !important;
			border-top-left-radius: 5px !important;
			border-bottom-left-radius: 5px !important;"> <span>Assignments</span></a></li>


<!-- Online Class -->
	{{-- <li class="treeview">
	<ul class="treeview-menu" parent>
		<li><a href="{{ url('/student/online-post') }}"><i class='fa fa-id-card-o'></i> <span>Dashboard</span></a></li>
		<li><a href="{{ url('/student/online-class') }}"><i class='fa fa-id-card-o'></i> <span>My Classes</span></a></li>
		<li><a href="{{ url('/student/online-class-assignments') }}"><i class='fa fa-id-card-o'></i> <span>Assignments</span></a></li>
	</ul>
</li> --}}

