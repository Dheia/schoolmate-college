<div class="user-panel">
  <a class="pull-left image" href="{{ route('backpack.account.info') }}">
    @php
      $avatar = backpack_auth()->user()->employee ? backpack_auth()->user()->employee->photo : 'images/headshot-default.png';
    @endphp
    <img src="{{ url($avatar) }}" class="img-circle" alt="User Image">
  </a>
  <div class="pull-left info">

    <p><a href="{{ route('backpack.account.info') }}">{{ backpack_auth()->user()->employee ? backpack_auth()->user()->employee->fullname : backpack_auth()->user()->name}} </a></p>
    <small>
    	<small>
    		<a href="{{ route('backpack.account.info') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a> &nbsp;  &nbsp; 
    		<a href="{{ backpack_url('logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a>
    	</small>
    </small>
  </div>
</div>