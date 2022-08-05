@extends('kiosk.layout')

@section('after_styles')
	<style>
		.form-group label {
			color: #ebe965;
		}
		.btn-xs {
		    padding: 1px 5px;
		    font-size: 12px;
		    line-height: 1.5;
	        border-radius: 3px;
		}
	</style>
@endsection

@section('content')

	<kiosk-enrollment :schoolabbr="'{{ config('settings.schoolabbr') }}'" :commitmentpayment="{{ $commitmentPayment ?? '{}' }}" :tuition="{{ json_encode($tuition) ?? '{}' }}"  :student="{{ $student }}" :enrollment="{{ $enrollment ?? '{}' }}" :studentnumber="{{ $studentnumber }}" :nextgradelevel="{{ json_encode($nxtGradeLevel) ?? '{}' }}" :schoolyearactive="{{ $schoolYearActive ?? '{}' }}"></kiosk-enrollment>	

@endsection

@section('after_scripts')
	<script src="/js/app.js"></script>

	<script type="text/javascript">
		
	</script>
@endsection