@extends('backpack::layout')

@section('header')
    <section class="content-header buttons-header">
      {{-- <div class="btn-group" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOtherProgram">Add Other Programs</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPayment">Add Payments</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSpecialDiscount">Add Special Discount</button>
		</div> --}}
    </section>
@endsection


@section('content')
			<!-- Default box -->	
		{{-- {{dd($students->getFillable())}} --}}
		
	<div class="row">
		<div class="col-md-2">

			<ul class="nav nav-tabs" id="myTab">
			    <li class="active"><a href=".front-card-wrapper" data-toggle="tab">Front Card</a></li>
			    <li><a href=".front-back-wrapper" data-toggle="tab">Back Card</a></li>
			</ul>
			
			<div class="tab-content">
				{{-- FRONT --}}
				@include('smartCard.front.frontCardPanel')
				{{-- .front-card-wrapper --}}

				{{-- Rear Options --}}
				@include('smartCard.back.backCardPanel')
			</div>
			
			<br>
			<div class="form-group">
				<label title="Template" for="name"><span class="mdi mdi-image"> Template Name</span></label>
				<input type="text" id="name" name="name" class="form-control" required/>
			</div>

			<div class="col-md-12">
				<button class="btn btn-block btn-primary" id="btnJson">Save</button>
			</div>
		</div>
		{{-- Front --}}
		<div class="col-md-5" style="margin: auto;">
			<canvas id="canvas_front" width="638" height="1021px" style="border: 1px solid #ccc;"></canvas>
		</div>
		{{-- Back --}}
		<div class="col-md-5" style="margin: auto;">
			<canvas id="card_back" width="638" height="1021px" style="border: 1px solid #ccc;"></canvas>
		</div>
	</div>
@endsection

@section('after_styles')
	{{-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"> --}}
	<style>
		@import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');
	</style>
@endsection

@section('after_scripts')
	<script src="{{ asset('/js/fabric.js') }}"></script>

	{{-- // SCRIPT FOR FRONT --}}
	<script src="{{ asset("/js/smartcard/front_card.js") }}"></script>
	<script src="{{ asset("/js/smartcard/back_card.js") }}"></script>

	<script>
		$('#btnJson').click(function () {
			var front = canvas_front.toJSON(['id']);
			var back  = canvas_back.toJSON(['id']);
		
			var form = $('<form action="{{ url($crud->route) }}" method="post"></form>');
			form.append('{{ csrf_field() }}');
			form.append('<input type="text" name="template_name" value="' + $('#name').val() + '"/>');
			form.append('<textarea name="front_card">' + JSON.stringify(front) + '</textarea>');
			form.append('<textarea name="rear_card">' + JSON.stringify(back) + ' </textarea>');

			form.appendTo('body').submit();
		})
	</script>

	<script>
		// (function () {
			$('#myTab a').bind('click', function (e) {
				// console.log($(this));
				e.preventDefault();
				$(this).tab('show');
			})
		// })
	</script>
@endsection