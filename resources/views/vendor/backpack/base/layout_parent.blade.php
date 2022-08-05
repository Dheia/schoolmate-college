@php
    $photo = auth()->user()->parent->photo;
    $avatar = \Storage::disk('public')->exists($photo) ? 'storage/' . $photo : 'images/headshot-default.png'; 
    auth()->user()->parent->photo = $avatar;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Parent Portal' : config('backpack.base.project_name').' Parent Portal' }}
    </title>

    @yield('before_styles')
    @stack('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    {{-- <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css"> --}}

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/base/backpack.base.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/overlays/backpack.bold.css') }}">
    <link rel="stylesheet" href="{{ asset('css/svg-icons.css') }}">

    <!-- FONT AWESOME KIT -->
    <script src="{{ asset('js/fontawesome-kit.js') }}" crossorigin="anonymous"></script>

    @yield('after_styles')

    <!-- JQUERY CONFIRM CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery-confirm-3.3.2.min.css') }}">
    <!-- SCHOOLMATE CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css') }}/schoolmate/schoolmate.css">

    @stack('after_styles')
    
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900);
        body {
            font-size: 12px;
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            height: 100%;
            line-height: 1.7;
            vertical-align: baseline;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            color: #646464;
            background-color: #f0f1f3;  
        }

        /*.sidebar-menu  .fa { color: #3c8dbc !important; }*/
        /*.sidebar-menu span {
            font-size: 12px;
            color: #888;
        }*/

        
        .search-results {
            /*display: none;*/
            position: absolute;
            top: 35px;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 0;
            margin: 0;
            border-width: 0px;
            border-style: solid;
            border-color: #cbcfe2 #c8cee7 #c4c7d7;
            border-radius: 3px;
            background-color: #fdfdfd;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fdfdfd), color-stop(100%, #eceef4));
            background-image: -webkit-linear-gradient(top, #fdfdfd, #eceef4);
            background-image: -moz-linear-gradient(top, #fdfdfd, #eceef4);
            background-image: -ms-linear-gradient(top, #fdfdfd, #eceef4);
            background-image: -o-linear-gradient(top, #fdfdfd, #eceef4);
            background-image: linear-gradient(top, #fdfdfd, #eceef4);
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            -ms-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            -o-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .search-results li { display: block }

        .search-results li:first-child { margin-top: -1px }

        .search-results li:first-child:before, .search-results li:first-child:after {
            display: block;
            content: '';
            width: 0;
            height: 0;
            position: absolute;
            left: 50%;
            margin-left: -5px;
            border: 5px outset transparent;
        }

        .search-results li:first-child:before {
            border-bottom: 5px solid #c4c7d7;
            top: -11px;
        }

        .search-results li:first-child:after {
            border-bottom: 5px solid #fdfdfd;
            top: -10px;
        }

        .search-results li:first-child:hover:before, .search-results li:first-child:hover:after { display: none }
        .search-results li:last-child { margin-bottom: -1px }

        .search-results a {
            display: block;
            position: relative;
            margin: 0 -1px;
            padding: 6px 40px 6px 10px;
            color: #808394 !important;
            font-weight: 500;
            text-shadow: 0 1px #fff;
            border: 1px solid transparent;
            border-radius: 3px;
        }

        .search-results a span { font-weight: 200 }

        .search-results a:before {
            color: #808394 !important;
            
            content: '';
            width: 18px;
            height: 18px;
            position: absolute;
            top: 50%;
            right: 10px;
            margin-top: -9px;
            background: url("https://cssdeck.com/uploads/media/items/7/7BNkBjd.png") 0 0 no-repeat;
        }

        .search-results a:hover {
            text-decoration: none;
            color: #fff;
            text-shadow: 0 -1px rgba(0, 0, 0, 0.3);
            border-color: #2380dd #2179d5 #1a60aa;
            background-color: #338cdf;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #59aaf4), color-stop(100%, #338cdf));
            background-image: -webkit-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -moz-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -ms-linear-gradient(top, #59aaf4, #338cdf);
            background-image: -o-linear-gradient(top, #59aaf4, #338cdf);
            background-image: linear-gradient(top, #59aaf4, #338cdf);
            -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -moz-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -ms-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            -o-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
            box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        }

        :-moz-placeholder {
            color: #a7aabc;
            font-weight: 200;
        }

        ::-webkit-input-placeholder {
            color: #a7aabc;
            font-weight: 200;
        }

        .lt-ie9 .search input { line-height: 26px }
        
/*        .skin-blue-light .sidebar-menu>li>.treeview-menu, 
        .sidebar-menu li,
        .sidebar-menu li.active a { 
            background: #FFF !important;
        }
    */
        .treeview-menu { position: relative; padding-left: 0; }
        /*.treeview-menu::before {
            content: '';
            height: 100%;
            opacity: 1;
            width: 3px;
            background: #3c8dbc;
            position: absolute;
            left: 20px;
            top: 0;
            border-radius: 15px;
            z-index: 1;
        }*/
        .treeview-menu>li { /*margin-left: 15px;*/ }
        .sidebar-menu>li>a {
    padding: 7px 5px 12px 15px;
    display: block;
}

    .parent-box {
        background:#367FA9; 
        border-radius:10px; 
        display:block; 
        width:200px; 
        height:40px; 
        margin-bottom:10px; 
        margin-left:20px; 
    }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} sidebar-mini">
	<script type="text/javascript">
		/* Recover sidebar state */
		(function () {
			if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
				var body = document.getElementsByTagName('body')[0];
				body.className = body.className + ' sidebar-collapse';
			}
		})();
	</script>
    {{-- Print Section --}}
    <div class="print_section"></div>
    <!-- Site wrapper -->
    <div class="wrapper" id="main-body">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">{!! config('backpack.base.logo_mini') !!}</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">{!! config('backpack.base.logo_lg') !!}</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          @include('backpack::inc.menu_parent')
        </nav>
      </header>

      <!-- =============================================== -->

      @include('backpack::inc.sidebar_parent')

      

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <section id="app" class="content">

          @yield('content')

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        @if (config('backpack.base.show_powered_by'))
            <div class="pull-right hidden-xs">
              {{ trans('backpack::base.powered_by') }} <a target="_blank" href="http://backpackforlaravel.com?ref=panel_footer_link">Backpack for Laravel</a>
            </div>
        @endif
        {{ trans('backpack::base.handcrafted_by') }} <a target="_blank" href="{{ config('backpack.base.developer_link') }}">{{ config('backpack.base.developer_name') }}</a>.
      </footer>
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')
    @stack('before_scripts')

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/plugins/jQuery/jQuery-2.2.3.min.js"><\/script>')</script> --}}
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    {{-- <script src="{{ asset('vendor/adminlte') }}/bower_components/fastclick/lib/fastclick.js"></script> --}}
    <script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>

    <!-- page script -->
    <script type="text/javascript">
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
          event.preventDefault();
          if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
          } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
          }
        });
        // To make Pace works on Ajax calls
        $(document).ajaxStart(function() { Pace.restart(); });

        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        // Set active state on menu element
        var current_url = "{{ Request::fullUrl() }}";
        var full_url = current_url+location.search;
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        // If not found, look for the link that starts with the url
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')); }
            );
        }

        $curentPageLink.parents('li').addClass('active');
        {{-- Enable deep link to tab --}}
        var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
        location.hash && activeTab && activeTab.tab('show');
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            location.hash = e.target.hash.replace("#tab_", "#");
        });

        // ADDITIONAL SCRIPTS
        // $("#nextTab").click(function() {
        //     console.log("Next Tab");
        //     var selected = $("li").attr('role').tabs("option", "selected");
        //     $("li").attr('role').tabs("option", "selected", selected + 1);
        // });

        $('#nextTab').click(function(){
            if ($('ul.nav-tabs .active').next('ul.nav-tabs li').length) {
                $('ul.nav-tabs .active').removeClass('active')
                            .next('ul.nav-tabs li')
                            .addClass('active');
                $getTabContentId = $('ul.nav-tabs .active').find('a').attr('href');

                $('.tab-content div.active').removeClass('active').next('.tab-content div').addClass('active');

            }
        });

        $('#prevTab').click(function(){
            if ($('ul.nav-tabs .active').prev('ul.nav-tabs li').length) {
                $('ul.nav-tabs .active').removeClass('active')
                            .prev('ul.nav-tabs li')
                            .addClass('active');
                $getTabContentId = $('ul.nav-tabs .active').find('a').attr('href');

                $('.tab-content div.active').removeClass('active').prev('.tab-content div').addClass('active');

            }
        });

        var input = $('#search_sidebar');

        input.keyup(function () {
            var tmp             = [],
                parent          = $(this),
                searchText      = $(this).val().toUpperCase(),
                searchResults   = $('.search-results');
                searchResults.html('');

            if(searchText.length < 1) {
                searchResults.html('');
                $('.sidebar-form > .input-group > span > a > i').removeClass('fa-times').addClass('fa-search');
                return;
            }

            $('.sidebar-form > .input-group > span > a > i').removeClass('fa-search').addClass('fa-times');

            $('.sidebar-form > .input-group > span > a ').on('click', 'i[class="fa fa-times"]', function () {
                parent.val('');
                $('.sidebar-form > .input-group > span > a > i').removeClass('fa-times').addClass('fa-search');
                searchResults.html('');
            })

            $('.sidebar .sidebar-menu.tree a[href!="#"]').each(function () {
                var currentAText = $(this).text().toUpperCase(),
                    showCurrentA = currentAText.indexOf(searchText) !== -1;
                if(showCurrentA) { tmp.push($(this));  }
            });

            var count = 0;
            $.each(tmp, function (key, val) {
                if(count === 5) { return; }
                var index = val.parent().index();
                var moduleName = '';

                // For Breadcrumb / Parent Of Item
                var parent = val.closest('ul[parent]').closest('li.treeview').find('a[href="#"]');

                if(parent.length > 0) {
                    moduleName = parent[0].text;
                } else {
                    moduleName = '-';
                }
                searchResults.append('<li><a href="' + val[0].href + '">' + val[0].text + '<br /><span>' + moduleName + '</span></a></li>');
                count++;
            });
        });
    </script>



    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')
    <script src="{{ asset('js/intlTelInput.js') }}"></script>
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> --}}
    <!-- JQUERY CONFIRM JS -->
    <script src="{{ asset('js/jquery-confirm-3.3.2.min.js') }}"></script>

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}

    <!-- Vue JS Notification Mix -->
    <script src="{{ mix('js/notification.js') }}"></script>
</body>
</html>
