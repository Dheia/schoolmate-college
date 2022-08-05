<div class="user-panel">
  <a class="pull-left image" href="">
  	@php
  		$avatar = auth()->user()->student ? auth()->user()->student->photo : 'images/headshot-default.png';
  	@endphp
    <img src="{{ url($avatar) }}" class="img-circle" alt="User Image" width="50">
  </a>
  <div class="pull-left info">

    <p><a href="{{ url('student/my-account') }}">{{ auth()->user()->student->fullname ?? '-' }}</a></p>
    <small><small><a href="{{ url('student/my-account') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a> &nbsp;  &nbsp; <a href="{{ route('student.logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></small></small>
  </div>
</div>