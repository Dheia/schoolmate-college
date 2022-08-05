<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | Student Portal</title>

    <!-- Scripts -->

    <!-- Styles -->
    {{-- <link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset("vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
    <link href="{{ asset('css/font-awesome-4.7.0.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('css/ionicons-2.0.0.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("vendor/adminlte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset("vendor/adminlte/dist/css/skins/skin-blue.min.css")}}" rel="stylesheet" type="text/css" />


</head>
<body class="skin-blue">
    <div id="app">
        <div class="wrapper">

        <header class="main-header">

            <a href="#" class="logo"><b>School</b>MATE</a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="{{ $student->photo }}" class="user-image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{ $student->fullname }}</span>
                            </a>
                             {{-- {{ dd(get_defined_vars()) }} --}}
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    {{-- <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image" /> --}}
                                    <p>
                                        {{$student->fullname}} - {{$student->current_enrollment}} 

                                        <small>{{$student->is_enrolled}} </small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Email</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Accounts</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Extra</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ $student->photo }}" class="img-circle" alt="" />
                    </div>
                    <div class="pull-left info">
                        <p>{{ $student->firstname}}</p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <!-- Optionally, you can add icons to the links -->
                    <li><a href="#"><span>Attendance</span></a></li>
                    <li><a href="#"><span>Another Link</span></a></li>
                    <li class="treeview">
                        <a href="#"><span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#">Link in level 2</a></li>
                            <li><a href="#">Link in level 2</a></li>
                        </ul>
                    </li>
                </ul><!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Student Portal
                    <small>Access All Your Data Here</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                    <li class="active">Here</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                <student-portal></student-portal>

            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                SchoolMate
            </div>
            <!-- Default to the left -->
            <strong>Copyright © 2015 <a href="#">Tigernet Hosting and IT Services</a>.</strong> All rights reserved.
        </footer>

    </div>
       
    </div>
   

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
    <!-- jQuery 2.1.3 -->
    <script src="{{ asset ("vendor/adminlte/dist/js/adminlte.min.js") }}" type="text/javascript"></script>

    
</body>
</html>
