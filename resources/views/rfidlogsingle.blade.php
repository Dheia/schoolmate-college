<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Student Logs</title>
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
         
        <rfid-logs-single></rfid-logs-single>
        
</div>


<script src='js/app.js' charset="utf-8"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

<script>
	$(function(){

	    // Cache some selectors

	    var clock = $('#clock'),
	        alarm = clock.find('.alarm'),
	        ampm = clock.find('.ampm');

	    // Map digits to their names (this will be an array)
	    var digit_to_name = 'zero one two three four five six seven eight nine'.split(' ');

	    // This object will hold the digit elements
	    var digits = {};

	    // Positions for the hours, minutes, and seconds
	    var positions = [
	        'h1', 'h2', ':', 'm1', 'm2', ':', 's1', 's2'
	    ];

	    // Generate the digits with the needed markup,
	    // and add them to the clock

	    var digit_holder = clock.find('.digits');

	    $.each(positions, function(){

	        if(this == ':'){
	            digit_holder.append('<div class="dots">');
	        }
	        else{

	            var pos = $('<div>');

	            for(var i=1; i<8; i++){
	                pos.append('<span class="d' + i + '">');
	            }

	            // Set the digits as key:value pairs in the digits object
	            digits[this] = pos;

	            // Add the digit elements to the page
	            digit_holder.append(pos);
	        }

	    });

	    // Add the weekday names

	    var weekday_names = 'MON TUE WED THU FRI SAT SUN'.split(' '),
	        weekday_holder = clock.find('.weekdays');

	    $.each(weekday_names, function(){
	        weekday_holder.append('<span>' + this + '</span>');
	    });

	    var weekdays = clock.find('.weekdays span');

	    // Run a timer every second and update the clock

	    (function update_time(){

	        // Use moment.js to output the current time as a string
	        // hh is for the hours in 12-hour format,
	        // mm - minutes, ss-seconds (all with leading zeroes),
	        // d is for day of week and A is for AM/PM

	        var now = moment().format("hhmmssdA");

	        digits.h1.attr('class', digit_to_name[now[0]]);
	        digits.h2.attr('class', digit_to_name[now[1]]);
	        digits.m1.attr('class', digit_to_name[now[2]]);
	        digits.m2.attr('class', digit_to_name[now[3]]);
	        digits.s1.attr('class', digit_to_name[now[4]]);
	        digits.s2.attr('class', digit_to_name[now[5]]);

	        // The library returns Sunday as the first day of the week.
	        // Stupid, I know. Lets shift all the days one position down, 
	        // and make Sunday last

	        var dow = now[6];
	        dow--;

	        // Sunday!
	        if(dow < 0){
	            // Make it last
	            dow = 6;
	        }

	        // Mark the active day of the week
	        weekdays.removeClass('active').eq(dow).addClass('active');

	        // Set the am/pm text:
	        ampm.text(now[7]+now[8]);

	        // Schedule this function to be run again in 1 sec
	        setTimeout(update_time, 1000);

	    })();

	    // Switch the theme

	    // $('a.button').click(function(){
	    //     clock.toggleClass('light dark');
	    // });

	});
</script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> --}}
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
	$(document).ready(function () {
		var counter = 0;
		var initSlick = function () {

			try {
				$('.top-student-slider').slick('unslick');
			} catch(e) {

			}

			$('.top-student-slider').not('.slick-initialized').slick({
				// dots: true,
				infinite: false,
				speed: 300,
				// slidesToShow: 6,
  				slidesToScroll: 1,
				centerMode: false,
				centerPadding: '60px',
				variableWidth: true
			});

			$('.slick-arrow').remove();

			$('.content-slider').each(function (index) {
				$(this).addClass('slick-slide').attr('data-slick-index', index);
			});
		}

		window.reSlick = function () {
			setTimeout(function () {
				console.log("run");
	            initSlick();
	        }, 300);
		} 
	});
</script>
</body>
</html>