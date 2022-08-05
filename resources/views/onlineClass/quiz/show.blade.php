
@extends('backpack::layout')

@section('header')
@endsection

@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">

<div class="row">
    @include('onlineClass/partials/navbar')
</div>

<div class="row">
	<!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

	<div class="col-md-8 col-lg-8 col-one p-l-0">
		<div class="{{ $crud->getShowContentClass() }} no-padding">

		<!-- Default box -->
		  <div class="">
		  	@if ($crud->model->translationEnabled())
		    <div class="row">
		    	<div class="col-md-12 m-b-10">
					<!-- Change translation button group -->
					<div class="btn-group pull-right">
					  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
					  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
						  	<li><a href="{{ url($crud->route.'/'.$entry->getKey()) }}?locale={{ $key }}">{{ $locale }}</a></li>
					  	@endforeach
					  </ul>
					</div>
				</div>
		    </div>
		    @else
		    @endif
		    <div class="col-md-12 pl-0">
		    	<!-- QUIZ TITLE -->
		    	<div class="box no-padding no-border">
		              	<div class="box-body with-border" style="padding: 0 20px !important;">
		                	<h3>
		                   	{{ $entry->quiz->title }}
		                	</h3>
		                	<h5>{!! $entry->quiz->description !!}</h5>
		              	</div>
		              	<div class="box-footer no-padding" style="border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
                          	<a class="btn box-button-one w-100" href="{{ url($crud->route . '/' . $entry->id . '/results?class_code=' . $class->code) }}">
			                    View Submitted Quiz
		                  	</a>
                		</div>
			    </div><!-- /.box-body -->
			    <!-- END OF QUIZ TITLE -->
		    </div>

		    <!-- QUIZ QUESTIONS -->
			<!-- <show-quiz :id={{$entry->quiz->id}}></show-quiz> -->
			<show-quiz :id={{$entry->quiz->id}}></show-quiz>

		    <!-- END OF QUIZ QUESTIONS -->

		  </div><!-- /.box -->

		</div>
	</div>
</div>
@endsection


@section('after_styles')
	

	<style>
		/*.content-wrapper {
			padding-left: 0 !important;
			padding-right: 0 !important;
		}*/
		.sv_main {
		    background-color: #fff !important;
		}
		.sv_main .sv_custom_header {
			background-color: #fff;
		}
		.panel-footer {
			background-color: #fff !important;
		}
		.box{
		    flex-direction: column;
		}
	</style>
@endsection

@section('after_scripts')
	<script type="text/javascript">
    	document.getElementById("nav-quizzes").classList.add("active");
  	</script>

	<script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
	
	{{-- VUE JS --}}
	{{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <script src="{{ mix('js/onlineclass/quiz.js') }}"></script>
@endsection
