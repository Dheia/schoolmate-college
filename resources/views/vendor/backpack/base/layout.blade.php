<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (config('backpack.base.meta_robots_content'))
    <meta name="robots" content="{{ config('backpack.base.meta_robots_content', 'noindex, nofollow') }}">
    @endif

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
    </title>

    @yield('before_styles')
    @stack('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.4.1 -->
{{--     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-3.4.1.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <!-- <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.css"> -->

    <!-- FONT AWESOME KIT -->
    <script src="{{ asset('js/fontawesome-kit.js') }}" crossorigin="anonymous"></script>


    

    <!-- <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css"> -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/svg-icons.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


    <!-- BackPack Base CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('vendor/backpack/base/backpack.base.css') }}?v=3"> --}}
    @if (config('backpack.base.overlays') && count(config('backpack.base.overlays')))
        @foreach (config('backpack.base.overlays') as $overlay)
        <link rel="stylesheet" href="{{ asset($overlay) }}">
        @endforeach
    @endif
    

    <style>
        {{-- /@import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900); --}}
        /@import url(https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700);


        body {
            font-size: 12px;    
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            line-height: 1.7;
            vertical-align: baseline;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            color: #646464;
            /*background-color: #f0f1f3;  */
        }

  /*      .sidebar-menu  .fa { color: #3c8dbc !important; }
        .sidebar-menu span {
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
            border-width: 1px;
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
    </style>

    <!-- JQUERY CONFIRM CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery-confirm-3.3.2.min.css') }}">
    <!-- SCHOOLMATE CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css') }}/schoolmate/schoolmate.css">

    @yield('after_styles')
        




    @stack('after_styles')

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
    <div class="preloader">
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_b88nh30c.json"  background="transparent"  speed="1"  style="width: 170px; height: 170px;"  loop  autoplay></lottie-player>
    </div>
    <!-- Site wrapper -->
    <div class="wrapper">

      @include('backpack::inc.main_header')

      <!-- =============================================== -->

      @include('backpack::inc.sidebar')

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

      <footer class="main-footer text-sm clearfix">
        @include('backpack::inc.footer')
      </footer>

    </div>
    <div class="loadingModal"><!-- Place at bottom of page --></div>
    <!-- ./wrapper -->

    <!-- JavaScripts -->
    <!-- Vue JS Notification Mix -->
    <script src="{{ mix('js/notification.js') }}"></script>

    
    @yield('before_scripts')
    @stack('before_scripts')
    
    @include('backpack::inc.scripts')
    @include('backpack::inc.alerts')

    
    <script src="{{ asset('vendor/adminlte/bower_components/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput.js') }}"></script>
    <!-- JQUERY CONFIRM JS -->
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> --}}
    <script src="{{ asset('js/jquery-confirm-3.3.2.min.js') }}"></script>

    <script>
        window.numberWithCommas =  function (x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        $(window).on('load', function () {
        setTimeout(function () {
            $(".preloader").fadeOut('slow');
            }, 1500)
            
        });
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
          event.preventDefault();
          if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
          } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
          }
        });
        // Set active state on menu element
        var full_url = "{{ Request::fullUrl() }}";
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        // If not found, look for the link that starts with the url
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(full_url) || full_url.startsWith($(this).attr('href')); }
            );
        }
        $curentPageLink.parents('li').addClass('active');
    </script>

    <script>
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



        // $(window).scroll(function() {
        //   if ($(this).scrollTop() > 0) {
        //     $('.navbar-static-top').fadeOut();
        //   } else {
        //     $('.navbar-static-top').fadeIn();
        //   }
        // });
    </script>

    @yield('after_scripts')
    @stack('after_scripts')

    <script>
        $('.dropdown-toggle').dropdown();
    </script>
    
</body>
</html>