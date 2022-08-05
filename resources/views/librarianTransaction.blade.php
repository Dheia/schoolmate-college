@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        Library Transaction
	  </h1>
	<ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">Librarian</a></li>
	    {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
	  </ol>
	</section>
	
@endsection

@section('content')

<div class="col-md-1"></div>
<div role="tabpanel" class="col-md-10">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
           <li role="presentation" class="active">
	          	<a href="#home" aria-controls="home" role="tab" data-toggle="tab">
	            Available Books
	        	</a>
	      	</li>
	            <li role="presentation">
	          	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
	            Reserved Books
	        </a>
	      	</li>
	            <li role="presentation">
	          <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
	              Unreturned Books
	          </a>
      		</li>
             
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">	       		
	       		<div class="col-md-12">
	          		<search-books></search-books>
	       		</div>
      		</div>
          	<div role="tabpanel" class="tab-pane" id="profile">		      	
			    <div class="col-md-12">
			        <my-books></my-books>
			    </div>        
	     	</div>
	        <div role="tabpanel" class="tab-pane" id="settings">		       	
		        <div class="col-md-12">
		           <un-return></un-return>
		        </div>        
	      </div>
	    </div>
	</div>
@endsection
@push('after_scripts')
	
	<script src="../js/app.js">
		
	</script>

	<script>
		
	</script>
@endpush