@extends('layouts.app')

@section('title', 'Online Class | Start Quiz')

@section('after_styles')
	<link rel="stylesheet" href="https://surveyjs.azureedge.net/1.8.12/modern.css">
@endsection

@section('content')
<body style="background:#F2F2F3;">
	<div class="" style="background:#F2F2F3;">
		<div class="header" >
			<h1 class="text-center py-3">
				<img src="{{ asset('images/logo_schoolmate_alt.png') }}" height="50" alt="SchoolMATE Logo">
			</h1>

			<nav class="navbar" style="background-color: #3C8DBC;">
			 	<a class="navbar-brand py-0 pb-2" href="#" style="color: #FFF;">		 		
					<img src="{{ asset('images/schoolmate-logo.png') }}" alt="SchoolMATE Logo">
			 	</a>
			</nav>
		</div>

		<div class="body" style="background:#F2F2F3;">
			<!-- <start-quiz quiz-item="{{ $classQuiz->quiz }}" id="{{ $classQuiz->id }}"></start-quiz> -->
			<start-quiz quiz_id="{{ $classQuiz->quiz_id }}" id="{{ $classQuiz->id }}" online_quiz_id="{{ $classQuiz->id }}"></start-quiz>
		</div>

		<div class="footer">
			<div style="padding:15px;color: #FFF;background-color:#424242; position: fixed; width: 100%; bottom: 0;">
			    <div class="container">
			        <div class="row">
			            <div class="col-md-6 mx-auto text-center">
			                © {{ now()->format('Y') }} SchoolMATE Quiz. All Rights Reserved
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</body>
@endsection

@section('after_scripts')
	<script src="{{ mix('js/onlineclass/quiz.js') }}"></script>
@endsection