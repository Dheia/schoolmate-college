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
    $user = auth()->user();
    // $notification_count = $user->unreadNotifications->count();
    // $notifications = $user->unreadNotifications()->orderBy('created_at', 'desc')->limit(5)->get()->toArray();
@endphp


<div class="navbar-custom-menu" id="app_notification_bell">
    <ul class="nav navbar-nav">
        <!-- ========================================================= -->
        <!-- ========== Top menu right items (ordered left) ========== -->
        <!-- ========================================================= -->

        <!-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> -->
        @if (config('backpack.base.setup_auth_routes'))
            @if (auth()->guest())
                <li>
                    <a href="{{ url(config('backpack.base.route_prefix', 'parent').'/login') }}">
                        {{ trans('backpack::base.login') }}
                    </a>
                </li>
                @if (config('backpack.base.registration_open'))
                    <li>
                        <a href="{{ route('auth.register') }}">
                            {{ trans('backpack::base.register') }}
                        </a>
                    </li>
                @endif
            @else
            
                <!-- =========================================== -->
                <!-- ========== NOTIFICATION BELL VUE ========== -->
                <!-- =========================================== -->
                <parent-notification-bell
                :user="{{ $user }}" 
                :school_id="'{{ env('SCHOOL_ID') }}'"
                :port="{{ env('MIX_SOCKET_PORT') }}">
                </parent-notification-bell>

                <!-- ============================ -->
                <!-- ========== LOGOUT ========== -->
                <!-- ============================ -->
                <li>
                    <a href="{{ route('parent.logout') }}">
                        <i class="fa fa-btn fa-sign-out"></i> {{ trans('backpack::base.logout') }}
                    </a>
                </li>
                
            @endif
       @endif
       <!-- ========== End of top menu right items ========== -->
    </ul>
</div>
