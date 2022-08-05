@php
	

	// dd($categories);
@endphp
@extends("backpack::layout_student")

@section('header')
   <div class="col-md-12">
   	<h1>Library</h1>
   </div> 
@endsection

@push('after_styles')
	
	
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
<div class="col-md-8">

	<search-books></search-books>
	

</div>
<div class="col-md-1">

	

</div>	
<div class="col-md-3">

	<my-books></my-books>
	<br>
	<br>
	<un-return></un-return>	

</div>	


	
@endsection

@push('after_scripts')
	
	<script src="../js/app.js">
		
	</script>

	<script>
		
	</script>
@endpush
