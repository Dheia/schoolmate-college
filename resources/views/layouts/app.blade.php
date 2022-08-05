<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Student')
    </title>

    <!-- Scripts -->
    
    @yield('before_styles')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet">

    @yield('after_styles')
</head>
<body>
    <div id="app">

        <main class="">
            @yield('content')
        </main>
    </div>

    @yield('before_scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap4.min.js') }}"></script>

    @yield('after_scripts')
</body>
</html>
