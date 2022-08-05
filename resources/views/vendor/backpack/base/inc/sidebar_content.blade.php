<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>


{{------------------------
	EMPLOYEE
------------------------}}

<li class="treeview">
	<a href="#"><i class="fa fa-user"></i> <span>My Portal</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href="{{ backpack_url('attendance-log') }}"><i class="fa fa-history"></i> <span>My Attendance</span></a></li>
		<li><a href='{{ backpack_url('announcement') }}'><i class='fa fa-bullhorn'></i> <span>Announcements</span></a></li>
		{{-- <li><a href="{{ backpack_url('payroll') }}"><i class="fa fa-money"></i> <span>My Payroll</span></a></li> --}}
		<li><a href="{{ backpack_url('incident-report') }}"><i class="fa fa-exclamation-triangle"></i> <span>Incident Report</span></a></li>
	</ul>
</li>
{{------------------------
	EMPLOYEE
------------------------}}


{{------------------------
	ADMISSION MODULE
------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-user"></i> <span>Admission</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		{{-- <li><a href="{{ url('admin/student/create') }}"><i class="fa fa-tag"></i> <span>Students</span></a></li> --}}
		{{-- <li><a href="{{ url('admin/level') }}"><i class="fa fa-tag"></i> <span>Levels</span></a></li> --}}
		<!-- <li class="treeview"> -->
			<!-- <a href="javascript:void(0)"><i class="fa fa-group"></i> <span>Enlisting</span> <i class="fa fa-angle-left pull-right"></i></a> -->
			<!-- <ul class="treeview-menu"> -->
				<li><a href="{{ backpack_url('student/create') }}"><i class="fa fa-user-plus"></i> <span>Add Student</span></a></li>
				<li><a href="{{ backpack_url('student') }}"><i class="fa fa-list-alt"></i> <span>Students List</span></a></li>
				<li><a href="{{ backpack_url('reports/main-filter') }}"><i class="fa fa-list-alt"></i> <span>Students Report</span></a></li>
				<li><a href="{{ backpack_url('requirement') }}"><i class="fa fa-id-badge"></i> <span>Upload Requirements</span></a></li>
			<!-- </ul> -->
		<!-- </li> -->
	</ul>
</li>

{{------------------------
	ENROLLMENT MODULE
------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-user-plus"></i> <span>Enrollment</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		{{-- <li><a href="{{ url('admin/student/create') }}"><i class="fa fa-tag"></i> <span>Students</span></a></li> --}}
		{{-- <li><a href="{{ url('admin/level') }}"><i class="fa fa-tag"></i> <span>Levels</span></a></li> --}}
		<!-- <li class="treeview"> -->
			<!-- <a href="javascript:void(0)"><i class="fa fa-group"></i> <span>Enrollment</span> <i class="fa fa-angle-left pull-right"></i></a> -->
			<!-- <ul class="treeview-menu"> -->
				<li><a href="{{ backpack_url('enrollment/create') }}"><i class="fa fa-user-plus	"></i> <span>Enroll Student</span></a></li>
				<li><a href="{{ backpack_url('enrollment') }}"><i class="fa fa-list-alt"></i> <span>Enrollment List</span></a></li>		
    			<li><a href="{{ backpack_url('student-accounts/create') }}"><i class="fa fa-id-badge"></i><span>Student Accounts</span></a></li>		
			<!-- </ul> -->
		<!-- </li> -->
	</ul>
</li>

{{----------------------------
	ACCOUNTING MANAGEMENT
----------------------------}}
<li class="treeview">
  <a href="#"><i class="fa fa-area-chart"></i> <span>Accounting</span> <i class="fa fa-angle-left pull-right"></i></a>
  	<ul class="treeview-menu">
  		<li><a href="{{ backpack_url('student-accounts/create') }}"><i class="fa fa-users"></i><span>Student Accounts</span></a></li>
  		<li><a href="{{ backpack_url('payment-method') }}"><i class="fa fa-credit-card"></i><span>Payment Method</span></a></li>
  		<li><a href="{{ backpack_url('commitment-payment') }}"><i class="fa fa-credit-card"></i><span>Commitment Payment</span></a></li>
	    <li><a href="{{ backpack_url('tuition') }}"><i class="fa fa-file-text-o"></i><span>Tuition Management</span></a></li>    	
    	<li><a href="{{ backpack_url('other-programs') }}"><i class="fa fa-calendar-plus-o"></i><span>Other Programs</span></a></li>
    	<li><a href="{{ backpack_url('other-service') }}"><i class="fa fa-bus"></i><span>Other Services</span></a></li>
    	<li><a href="{{ backpack_url('quickbooks/reporting') }}"><i class="fa fa-list-alt"></i><span>Reports</span></a></li>
    	<li><a href="{{ backpack_url('payment-history') }}"><i class="fa fa-list-alt"></i><span>Payment Review</span></a></li>
    	<li><a href="{{ backpack_url('quickbooks/authorize') }}"><i class="fa fa-mobile"></i> <span>Authorize QBO</span></a></li>
    	{{--   <li>
    		<a href="{{ url('admin/misc') }}">
    			<i class="fa fa-file-text-o"></i> 
    			<span>Miscellaneous</span>
    		</a>
    	</li>  --}}
	    {{-- <li>
	    	<a href="{{ backpack_url('cash-account') }}">
	    		<i class="fa fa-credit-card"></i><span>Cash Accounts</span>
	    	</a>
	    </li>
	    <li class="treeview">
			<a href="#"><i class="fa fa-group"></i> <span>Chart of Accounts</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<li>
					<a href="{{ backpack_url('profits-loss-statement') }}">
						<i class="fa fa-user"></i> <span>Profits and Loss Statement</span>
					</a>
				</li>
			</ul>
		</li> --}}
	    

    	{{-- <li>
    		<a href="{{ backpack_url('other-fee') }}">
    			<i class="fa fa-key"></i> 
    			<span>Other Fee</span>
    		</a>
    	</li> --}}
  	</ul>
</li>

{{------------------------
	CURRICULUM MANAGEMENT
------------------------}}
<li class="treeview">
  <a href="#"><i class="fa fa-tasks"></i> <span>Curriculum</span> <i class="fa fa-angle-left pull-right"></i></a>
  	<ul class="treeview-menu">
	    <li><a href="{{ backpack_url('curriculum_management') }}"><i class="fa fa-file-text-o"></i> <span>Curriculum Management</span></a></li>
	    <li><a href="{{ backpack_url('subject_management') }}"><i class="fa fa-pencil-square-o"></i><span>Subject Management</span></a></li>
	    <li><a href="{{ backpack_url('term-management') }}"><i class="fa fa-list-alt"></i><span>Term Management</span></a></li>
	    <li><a href="{{ backpack_url('subject-mapping') }}"><i class="fa fa-th-list"></i><span>Subject Mapping</span></a></li>

  	</ul>
</li>


{{------------------------
	CLASS MANAGEMENT
------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-th-list"></i> <span>Class</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href="{{ backpack_url('section_management') }}"><i class="fa fa-address-book-o"></i><span>Section Management</span></a></li>
		<li><a href='{{ backpack_url('student-section-assignment') }}'><i class='fa fa-vcard'></i> <span>Sectioning</span></a></li>
		@if(backpack_user()->hasRole('Teacher'))
			<li><a href='{{ backpack_url('teacher-subject') }}?teacher_id={{ backpack_user()->id }}'><i class='fa fa-id-card-o'></i> <span>My Subjects</span></a></li>
		@else
			<li><a href='{{ backpack_url('teacher-assignment') }}'><i class='fa fa-id-card-o'></i> <span>Teacher Assignments</span></a></li>
		@endif
	</ul>
</li>



{{----------------------
	GRADE MANAGEMENT
----------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-edit"></i> <span>Grade</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
			<!-- <li class="treeview"> -->
				<!-- <a href="#"><i class="fa fa-group"></i> <span>Encode Grades</span> <i class="fa fa-angle-left pull-right"></i></a> -->
				<!-- <ul class="treeview-menu"> -->
					<li><a href='{{ backpack_url('encode-grade') }}'><i class='fa fa-edit'></i> <span>Encode Grades</span></a></li>
				<!-- </ul> -->
			<!-- </li> -->
			<li class="treeview">
				<a href="#"><i class="fa fa-th-list"></i> <span>Grade Setup</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
				    <li><a href="{{ backpack_url('period') }}"><i class="fa fa-th-large"></i><span>Period Management</span></a></li>
				    <li><a href='{{ backpack_url('grade-template') }}'><i class='fa fa-th'></i> <span>Grade Template</span></a></li>
					<li><a href='{{ backpack_url('setup-grade') }}'><i class='fa fa-file'></i> <span>Grade Setup</span></a></li>
					<li><a href='{{ backpack_url('transmutation') }}'><i class='fa fa-th-list'></i> <span>Transmutation Table</span></a></li>
				  
				</ul>
			</li>
			
		</ul>
</li>





{{----------------------
	ASSET INVENTORY
----------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-inbox"></i> <span>Assets / Inventory</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href="{{ backpack_url('building') }}"><i class="fa fa-building-o"></i> <span>Building</span></a></li>
		<li><a href="{{ backpack_url('room') }}"><i class="fa fa-navicon"></i> <span>Room</span></a></li>
		<li><a href="{{ backpack_url('asset-inventory') }}"><i class="fa fa-laptop"></i> <span>Tangible Asset</span></a></li>
		<li><a href="{{ backpack_url('locker') }}"><i class="fa fa-key"></i> <span>Locker Management</span></a></li>
	</ul>
</li>

{{--------------------------
	SECURITY MANAGEMENT
--------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-lock"></i> <span>Campus Security</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href='{{ backpack_url('rfid-connect') }}'><i class='fa fa-tag'></i> <span>RFID Connect</span></a></li>
		<li><a href='{{ backpack_url('turnstile') }}'><i class='fa fa-tag'></i> <span>Turnstile Management</span></a></li>
		<li><a href='{{ backpack_url('turnstile-log') }}'><i class='fa fa-list-alt'></i> <span>Turnstile Logs</span></a></li>
		<li class="treeview">
			<a href="#"><i class="fa fa-vcard"></i> <span>SmartCard</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="{{ backpack_url('smartcard/card-setup') }}"><i class="fa fa-vcard"></i> <span>Card Template</span></a></li>
				<li><a href="{{ backpack_url('smartcard/card-printing') }}"><i class="fa fa-vcard"></i> <span>Card Printing</span></a></li>
			</ul>
		</li>

	</ul>
</li>


{{--------------------------
	SMS MANAGEMENT
--------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-mobile"></i> <span>SMS</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">	
				<li><a href="{{ backpack_url('sms') }}"><i class="fa fa-user"></i> <span>SMS Registration</span></a></li>
				<li><a href="{{ backpack_url('admin-sms-register') }}"><i class="fa fa-user"></i> <span>Admin SMS Registration</span></a></li>
				<li><a href="{{ backpack_url('turnstile-sms-receiver') }}"><i class="fa fa-user"></i> <span>Turnstile SMS Receipent</span></a></li>
				<li><a href="{{ backpack_url('text-blast') }}"><i class="fa fa-mobile"></i> <span>SMS Blast</span></a></li>
				<li><a href="{{ backpack_url('smslog') }}"><i class="fa fa-list-alt"></i> <span>SMS Logs</span></a></li>
	</ul>
</li>


{{--------------------
	HR MANAGEMENT
--------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-group"></i> <span>Human Resource</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href='{{ backpack_url('employee') }}'><i class='fa fa-user'></i> <span>Employee</span></a></li>
		<li><a href='{{ backpack_url('employee-attendance') }}'><i class='fa fa-user'></i> <span>Employee Attendance</span></a></li>
		<li><a href='{{ backpack_url('employment_status') }}'><i class='fa fa-user'></i> <span>Status Management</span></a></li>
		<li><a href='{{ backpack_url('leave') }}'><i class='fa fa-user'></i> <span>Leave Management</span></a></li>
		<li><a href='{{ backpack_url('leave_credits') }}'><i class='fa fa-user'></i> <span>Leave Credits</span></a></li>
		<li><a href='{{ backpack_url('schedule-template') }}'><i class='fa fa-calendar'></i> <span>Schedule Template</span></a></li>
		<li><a href='{{ backpack_url('schedule-tagging') }}'><i class='fa fa-th-list'></i> <span>Schedule Tagging </span></a></li>
	</ul>
</li>


{{--------------
	PAYROLL
--------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-bar-chart"></i> <span>Payroll</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
			<li><a href='{{ backpack_url('employee-tax-status') }}'><i class='fa fa-th-list'></i> <span>Tax Status</span></a></li>
			<li><a href='{{ backpack_url('employment_status') }}'><i class='fa fa-th-list'></i> <span>Status Management</span></a></li>
			<li><a href='{{ backpack_url('employee-salary-management') }}'><i class='fa fa-money'></i> <span>Salary Management</span></a></li>
			<li><a href='{{ backpack_url('leave') }}'><i class='fa fa-tag'></i> <span>Leave Management</span></a></li>
			<li><a href='{{ backpack_url('leave_credits') }}'><i class='fa fa-tag'></i> <span>Leave Credits</span></a></li>
			<li><a href='{{ backpack_url('employee-mandatory-sss') }}'><i class='fa fa-user'></i> <span>Employee Mandatory SSS</span></a></li>
			<li><a href='{{ backpack_url('employee-mandatory-phil-health') }}'><i class='fa fa-user'></i> <span>Employee Mandatory PhilHealth</span></a></li>
			<li><a href='{{ backpack_url('employee-mandatory-pag-ibig') }}'><i class='fa fa-user'></i> <span>Employee Mandatory Pag-ibig</span></a></li>
			<li><a href='{{ backpack_url('holiday') }}'><i class='fa fa-th-list'></i> <span>Holiday</span></a></li>
			<li><a href='{{ backpack_url('attendance-rule') }}'><i class='fa fa-th-list'></i> <span>Attendance Rule </span></a></li>
			<li><a href='{{ backpack_url('tag-rule') }}'><i class='fa fa-th-list'></i> <span>Tag Rule </span></a></li>
			<li><a href='{{ backpack_url('employee-salary-report') }}'><i class='fa fa-list-alt'></i> <span>Employee Salary Report</span></a></li>
		</ul>
</li>


{{------------------------
	CANTEEN MANAGEMENT
------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-shopping-basket"></i> <span>Canteen</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		{{-- <li><a href="{{ backpack_url('pos-transaction') }}"><i class='fa fa-tag'></i> <span>Transactions</span></a></li> --}}
		<li><a href="http://pos.schoolmate-online.com"><i class='fa fa-calculator'></i><span>POS</span></a></li>
		<li><a href="{{ backpack_url('item-category') }}"><i class='fa fa-th-list'></i><span>Item Category</span></a></li>
		<li><a href="{{ backpack_url('item-inventory') }}"><i class='fa fa-th'></i><span>Item Inventory</span></a></li>
		<li class="treeview"><a href="#"><i class="fa fa-edit"></i> 
			<span>Generate Reports</span><i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="{{ backpack_url('item-inventory/sales-report') }}"><i class='fa fa-list-alt'></i> <span>Sales Reports</span></a></li>
				<li><a href="{{ backpack_url('item-inventory/inventory-report') }}"><i class='fa fa-list-alt'></i> <span>Inventory Reports</span></a></li>
			</ul>
		</li>
		<li><a href="{{ backpack_url('item-order') }}"><i class='fa fa-th'></i><span>Item Orders</span></a></li>
	</ul>
</li>

{{------------------------
	LIBRARY MANAGEMENT
------------------------}}
<li class="treeview">
	<a href="#"><i class="fa fa-book"></i> <span>Library</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu"> 
			<li><a href='{{ backpack_url('library/book') }}'><i class='fa fa-book'></i> <span>Book Management</span></a></li>
			<li><a href='{{ backpack_url('library/book-category') }}'><i class='fa fa-book'></i> <span>Category</span></a></li>
			<li><a href='{{ backpack_url('library/subject-tag') }}'><i class='fa fa-tag'></i> <span>Tag</span></a></li>
			<li><a href='{{ backpack_url('library/book-transaction') }}'><i class='fa fa-th-list'></i> <span>Book Transaction</span></a></li>
			<li><a href='{{ backpack_url('library/librarian') }}'><i class='fa fa-user'></i> <span>Librarian</span></a></li>
			{{-- <li><a href="{{ backpack_url('accounting-monthly') }}"><i class="fa fa-list-alt"></i> <span>Monthly Report</span></a></li>
			<li><a href="{{ backpack_url('accounting-annual') }}"><i class="fa fa-list-alt"></i> <span>Annual Report</span></a></li> --}}
		</ul>
</li>


{{------------------------
	SYSTEM MANAGEMENT
------------------------}}
<li class="treeview">
  <a href="#"><i class="fa fa-gear"></i> <span>System Management</span> <i class="fa fa-angle-left pull-right"></i></a>
  	<ul class="treeview-menu">
	    <li><a href="{{ backpack_url('schoolyear') }}"><i class="fa fa-calendar" aria-hidden="true"></i><span>School Year Management</span></a></li>
	    <li><a href="{{ backpack_url('department') }}"><i class="fa fa-building-o"></i><span>Department Management</span></a></li>
	    <li><a href="{{ backpack_url('non-academic-department') }}"><i class="fa fa-building-o"></i><span>Non-Academic Department</span></a></li>
	    <li><a href="{{ backpack_url('year_management') }}"><i class="fa fa-th-list"></i><span>Level Management</span></a></li>
	    <li><a href="{{ backpack_url('track') }}"><i class="fa fa-road"></i><span>Track Management</span></a></li>
	    <li><a href="{{ backpack_url('curriculum_management') }}"><i class="fa fa-th-list"></i> <span>Curriculum Management</span></a></li>

		<li class="treeview">
			<a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
				<li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
				<li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#"><i class="fa fa-gears"></i> <span>Maintenance</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="{{ backpack_url('setting') }}"><i class="fa fa-gear"></i> <span>Settings</span></a></li>
				<li><a href="{{ backpack_url('backup') }}"><i class="fa fa-user"></i> <span>Backups</span></a></li>
			</ul>
		</li>

	    
	    
	    
		{{-- <li>
			<a href="{{ backpack_url('section-builder') }}">
				<i class="fa fa-address-card-o"></i>
				<span>Section Subject Assignment</span>
			</a>
	    </li> --}}
  	</ul>
</li>