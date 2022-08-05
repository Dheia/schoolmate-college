
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<div class="parent-box" style="margin-left:12px;"><a href="/parent/dashboard" style=""><i class="fa fa-trello fa-2x" style="margin-left:10px; margin-top:7px;"></i> <span style="font-size:21px;"><b style="font-family:sans-serif; margin-left:5px">Parent Portal</b></span></a></div>
	
	<li>
	<a href="{{ route('parent.dashboard') }}" 
			style="
				width: 210px; margin-left:12px; 
				border-radius: 0px !important;
				border-top-left-radius: 5px !important;
				border-bottom-left-radius: 5px !important;"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

	<li>
	<!-- Announcement -->
	<li>
	<a href="{{ route('parent.announcements') }}" 
			style="
				width: 210px; margin-left:12px; 
				border-radius: 0px !important;
				border-top-left-radius: 5px !important;
				border-bottom-left-radius: 5px !important;"><i class="fa fa-bullhorn"></i> <span>Announcement</span></a></li>

	<li>
	<a href="{{ route('parent.add-student') }}" 
			style="
				margin-top:2px;
				width: 210px; margin-left:12px; 
				border-radius: 0px !important;
				border-top-left-radius: 5px !important;
				border-bottom-left-radius: 5px !important;"><i class="fa fa-user-plus"></i> <span>Add Student</span></a></li>	


<!-- Attendances -->
<li class="treeview">
	<a href="/student/online-class" 
	style="
	margin-top:2px;width: 230px; 
	margin-left:12px;
	border-radius: 0px !important;
	border-top-left-radius: 5px !important;
	border-bottom-left-radius: 5px !important;">
	<i class="fa fa-history"></i> <span>Attendances</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu" parent>
		{{-- <li><a href="{{ url('/parent/turnstile-attendance') }}"><i class='fa fa-history'></i> <span>Turnstile Attendance</span></a></li> --}}
		<li><a href="{{ url('/parent/class-attendance') }}" style="width: 280px; margin-left:30px;"><i class='fa fa-history'></i> <span>Class Attendance</span></a></li>
		<li><a href="{{ url('/parent/system-attendance') }}" style="width: 280px; margin-left:30px;"><i class='fa fa-history'></i> <span>System Attendance</span></a></li>
	</ul>
</li>

<li>
	<a href="{{ url('parent/student_pa') }}" 
			style="
				margin-top:2px;
				width: 210px; margin-left:12px; 
				border-radius: 0px !important;
				border-top-left-radius: 5px !important;
				border-bottom-left-radius: 5px !important;"><i class="fa fa-legal"></i> <span>Insurance Policy</span></a></li>	