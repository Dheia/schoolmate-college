@php
	$weekDays = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']; 
@endphp



<div class="col-md-12 form-group">
	<label for="">{{ $field['label'] }}</label>
	<table class="table table-striped table-bordered">
		<thead>
			<th>Time</th>
			<th>Mon</th>
			<th>Tue</th>
			<th>Wed</th>
			<th>Thu</th>
			<th>Fri</th>
			<th>Sat</th>
			<th>Sun</th>
		</thead>
		<tbody>
				<tr>
					<td><b>Timein</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="{{ $crud->getActionMethod() == 'edit' ? $entry->{$weekDay . '_timein'} : old($weekDay . '_timein')  ?? '07:00:00' }}" name="{{ $weekDay }}_timein" type="time" class="form-control">
						</td>
					@endforeach
				</tr>
				<tr>
					<td><b>Timeout</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="{{ $crud->getActionMethod() == 'edit' ? $entry->{$weekDay . '_timeout'} : old($weekDay . '_timein') ?? '07:00:00' }}" name="{{ $weekDay }}_timeout" type="time" class="form-control">
						</td>
					@endforeach
				</tr>

				
				<tr>
					<td colspan="{{ count($weekDays) + 1 }}">&nbsp;</td>
				</tr>
				<tr style="background-color: lightblue">
					<td><b>Start/End</b></td>
					<td colspan="{{ count($weekDays) }}" class="text-center"><b>LUNCH BREAK</b></td>
				</tr>
				<tr>
						<td class="text-right"><b>Start</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="{{ $crud->getActionMethod() == 'edit' ? $entry->{'lunch_break_time_start_' . $weekDay} : old('lunch_break_time_start_' . $weekDay) ?? '12:00:00' }}" name="lunch_break_time_start_{{ $weekDay }}" type="time" class="form-control">
						</td>
					@endforeach
				</tr>
				<tr>
						<td class="text-right"><b>End</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="{{ $crud->getActionMethod() == 'edit' ? $entry->{'lunch_break_time_end_' . $weekDay} : old('lunch_break_time_end_' . $weekDay) ?? '12:00:00' }}" name="lunch_break_time_end_{{ $weekDay }}" type="time" class="form-control">
						</td>
					@endforeach
				</tr>
				<tr>
					<td colspan="{{ count($weekDays) + 1 }}">&nbsp;</td>
				</tr>


				<tr>
					<td> <b>Rest Day</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="1" {{ $crud->getActionMethod() == 'edit' ? $entry->{'rest_day_' . $weekDay} ? "checked='true'" : "" : old('rest_day_' . $weekDay) ? "checked='true'" : '' }}" name="rest_day_{{ $weekDay }}" type="checkbox" class="">
						</td>
					@endforeach
				</tr>
				<tr>
					<td><b>No. Of Hours</b></td>
					@foreach($weekDays as $weekDay)
						<td>
							<input value="{{ $crud->getActionMethod() == 'edit' ? $entry->{'no_of_hours_' . $weekDay} : old('no_of_hours_' . $weekDay) }}" name="no_of_hours_{{ $weekDay }}" type="number" class="form-control" readonly>
						</td>
					@endforeach
				</tr>
		</tbody>
	</table>
</div>



{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script>
		var watch = function () {
			console.log('watching');
			var weekdays = [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ];

			$.each(weekdays, function (k, value){

				var inputTimeIn = $('input[name="' + value + '_timein"]');
				var inputTimeOut = $('input[name="' + value + '_timeout"]');
				if(inputTimeIn.val() !== "" || inputTimeOut.val() !== "") {
					var startTime = moment(inputTimeIn.val(), 'hh:mm');
					var endTime = moment(inputTimeOut.val(), 'hh:mm');

					var totalSec = endTime.diff(startTime, 'seconds');
					var hours = parseInt(totalSec / 3600) % 24;
					var minutes = parseInt(totalSec / 60) % 60;
					// var seconds = totalSec % 60; 

					// var lunchBreak = $('input[name="lunch_break_minutes_' + value + '"]').val();
					var lunchBreakTimeStart = $('input[name="lunch_break_time_start_' + value + '"').val();
					var lunchBreakTimeEnd = $('input[name="lunch_break_time_end_' + value + '"').val();

					var lunchStartTime = moment(lunchBreakTimeStart, 'HH:mm:ss');
					var lunchEndTime = moment(lunchBreakTimeEnd, 'HH:mm:ss');
					var lunchBreakDiffInMinutes = moment.duration(lunchEndTime.diff(lunchStartTime)).asHours()
					console.log(value + ' = ', lunchBreakDiffInMinutes);

					$('input[name="no_of_hours_' + value + '"]').val(hours - (lunchBreakDiffInMinutes ?  lunchBreakDiffInMinutes : 0));
				}
			});
		}
		watch();
		$('input').on('change', function () { watch(); })
	</script>
@endpush


