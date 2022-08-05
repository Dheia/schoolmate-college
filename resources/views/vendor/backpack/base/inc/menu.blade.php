<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->

        <!-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> -->

        <!-- ========== End of top menu left items ========== -->
    </ul>
</div>

@php
  $school_years = App\Models\SchoolYear::where('isActive', '!=', 1)->get();
  $user = App\User::where('id', backpack_user()->id)->first();
  // $notification_count = $user->unreadNotifications->count();
  // $notifications = $user->unreadNotifications()->orderBy('created_at', 'desc')->limit(5)->get()->toArray();
  
@endphp


<div class="navbar-custom-menu" id="app_notification_bell">
    <ul class="nav navbar-nav">

      <!-- =========================================== -->
      <!-- ========== PUSH NOTIFICATION VUE ========== -->
      <!-- =========================================== -->
      <employee-push-notification
        :user="{{ $user }}" 
        :school_id="'{{ env('SCHOOL_ID') }}'" 
        :instance_id="'{{ env('BEAMS_INSTANCE_ID') }}'" 
        :secret_key="'{{ env('BEAMS_SECRET_KEY') }}'" 
        :port="{{ env('MIX_SOCKET_PORT') }}">
      </employee-push-notification>

      <!-- ========================================================= -->
      <!-- ========== Top menu right items (ordered left) ========== -->
      <!-- ========================================================= -->
      <li>
        <a href="{{backpack_url('meeting')}}"><i class="fa fa-video-camera"></i></a>
      </li>
      <li class="faq-label">
        <a href="https://schoolmate-online.net/faq" target="_blank"><i class="fa fa-question-circle-o"></i></a>
      </li>


      <!-- =========================================== -->
      <!-- ========== NOTIFICATION BELL VUE ========== -->
      <!-- =========================================== -->
      <employee-notification-bell
        :user="{{ $user }}" 
        :school_id="'{{ env('SCHOOL_ID') }}'"
        :port="{{ env('MIX_SOCKET_PORT') }}">
      </employee-notification-bell>

      
{{--       <li>
        <a href="{{ backpack_url('schoolyear') }}">
          <span><b>{{ App\Models\SchoolYear::active()->first()->schoolYear }}</b></span>
        </a>
      </li> --}}

      
      
      @php
       $sy = App\Models\SchoolYear::active()->first();
       if($sy !== null) {
          $sy = $sy->schoolYear;
       } else {
          $sy = 'No Active School Year';
       }
      @endphp
      <li>
        <a href="javascript:void(0)"><b>S.Y. &nbsp;{{  $sy  }}</b>&nbsp;</a>
      </li>
      @if (config('backpack.base.setup_auth_routes'))
        @if (backpack_auth()->guest())
            <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/login') }}">{{ trans('backpack::base.login') }}</a></li>
            @if (config('backpack.base.registration_open'))
            <li><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
            <li><a href="{{ route('backpack.auth.logout') }}"><i class="fa fa-btn fa-sign-out"></i><span class="logout-label"> {{ trans('backpack::base.logout') }}</span></a></li>
        @endif
       @endif
       <!-- ========== End of top menu right items ========== -->
    </ul>
</div>



