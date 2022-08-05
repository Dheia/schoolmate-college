<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Point of Sales</title>
        {{-- <script src="{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script> --}}
        <link rel="stylesheet" href="{{ asset('css/bootstrap4.min.css') }}">
        {{-- <link rel="stylesheet" href="css/app.css"> --}}
        <link rel="stylesheet" href="{{ asset('css/clock.css') }}">
        <link rel="stylesheet" href="{{ asset('css/rfidlogs.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('css/slick.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
        <link rel="stylesheet" href="{{ asset('css/rfid.css') }}">
</head>
<body>

<div id="app">

        Point of Sales
        <point-of-sales></point-of-sales>
        
</div>


<script src='/js/app.js' charset="utf-8"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> --}}
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>

</body>
</html>