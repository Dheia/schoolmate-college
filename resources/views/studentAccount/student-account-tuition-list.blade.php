@extends('backpack::layout')

@section('header')
    <section class="content-header buttons-header"></section>
@endsection


@section('content')
	<section class="row">
			<!-- Default box -->	
			<tuition-table school-name="{{ config('settings.schoolname') }}"></tuition-table>

	</section>
@endsection

@section('after_styles')
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<link rel="stylesheet" href="{{ asset('vendor/vue-select/vue-select.css') }}">

	<style>
		.skeleton {
		  position: relative;
		  overflow: hidden;
		  width: 100%;
		  height: 15px;
		  background: #ccc;
		}
		.skeleton::after {
		  content: "";
		  position: absolute;
		  top: 0;
		  bottom: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  background: linear-gradient(90deg, #ccc, #dedede, #ccc);
		  animation: progress 1s ease-in-out infinite;
		}

		@keyframes progress {
		  0% {
		    transform: translate3d(-100%, 0, 0);
		  }
		  100% {
		    transform: translate3d(100%, 0, 0);
		  }
		}

	</style>
@endsection

@section('after_scripts')
	<script src="{{ asset('vendor/vue-select/vue-select.js') }}"></script>
	<script src="{{ asset('/js/app.js') }}"></script>
	<script src="{{ asset('/js/jquery-confirm.js') }}"></script>

	<script type="text/javascript">
		$('.modal').on('hidden.bs.modal', function () {
			$('body').css('paddingRight', '0')
		})
	</script>
@endsection