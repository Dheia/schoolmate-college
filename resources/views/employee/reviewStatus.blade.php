@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        <div class="row m-b-10">
          <div class="col-xs-6">
            <div class="hidden-print">
				{{-- BUTTONS --}}
				<button class="btn btn-primary" data-toggle="modal" data-target="#updateStatusModal"><i class="fa fa-edit"></i> Update Status</button>
            </div>
          </div>
          <div class="col-xs-6">
              <div id="datatable_search_stack" class="pull-right"></div>
          </div>
        </div>

		<div class="box">
{{-- 				<header class="header-timeline">
					<div class="container text-center">
						<p>{{ backpack_auth()->user()->full_name }}</p>
						<h1>Employment Status Timeline</h1>
					</div>
				</header>
 --}}
				<section class="timeline m-t-50">
					<div class="container" id="wrapper">
						@php $counter = 0; @endphp
						@foreach($employmentHistories as $employment)
							<div class="timeline-item">
								<div class="timeline-img"></div>
								
									<div class="timeline-content js--fadeIn{{ $counter % 2 === 0 ? 'Left': 'Right' }}">
										<h2>{{ $employment->employmentStatus->name }}</h2>
										<div class="date">{{ $employment->created_at->format('M. d, Y | h:i a') }}</div>
										{{-- <p>{{ $employment->employmentStatus->description }}</p> --}}
										{{-- <a class="bnt-more" href="javascript:void(0)">More</a> --}}
									</div>
							</div> 
							@php $counter++; @endphp
						@endforeach
				
					</div>
				</section>

		</div>

        <div class="overflow-hidden">

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

<!-- Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Update</h4>
			</div>
			<div class="modal-body">
				<label for="employementStatus">Employement Status</label>
				<select name="employement_status_id" id="employementStatus" class="form-control" required>
					@foreach($employmentStatuses as $status)
						<option value="{{ $status->id }}">{{ $status->name }}</option>
					@endforeach
				</select>

				<div class="form-group">
					<label for="status_change_date"></label>
					<input id="status_change_date"type="date" name="status_change_date" class="form-control" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="updateStatus">Update</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('after_styles')
  <style>
		.header-timeline section {
		  padding: 100px 0;
		}

		.header-timeline h1 {
		  font-size: 200%;
		  text-transform: uppercase;
		  letter-spacing: 3px;
		  font-weight: 400;
		}

		.header-timeline {
		  background: #3F51B5;
		  color: #FFFFFF;
		  padding: 150px 0;
		}
		.header-timeline p {
		  font-family: 'Allura';
		  color: rgba(255, 255, 255, 0.2);
		  margin-bottom: 0;
		  font-size: 60px;
		  margin-top: -30px;
		}

		.timeline {
		  position: relative;
		}
		.timeline::before {
		  content: '';
		  background: #C5CAE9;
		  width: 5px;
		  height: 95%;
		  position: absolute;
		  left: 50%;
		  transform: translateX(-50%);
		}

		.timeline-item {
		  width: 100%;
		  margin-bottom: 70px;
		}
		.timeline-item:nth-child(even) .timeline-content {
		  float: right;
		  padding: 40px 30px 10px 30px;
		}
		.timeline-item:nth-child(even) .timeline-content .date {
		  right: auto;
		  left: 0;
		}
		.timeline-item:nth-child(even) .timeline-content::after {
		  content: '';
		  position: absolute;
		  border-style: solid;
		  width: 0;
		  height: 0;
		  top: 30px;
		  left: -15px;
		  border-width: 10px 15px 10px 0;
		  border-color: transparent #f5f5f5 transparent transparent;
		}
		.timeline-item::after {
		  content: '';
		  display: block;
		  clear: both;
		}

		.timeline-content {
		  position: relative;
		  width: 45%;
		  padding: 10px 30px;
		  border-radius: 4px;
		  background: #f5f5f5;
		  box-shadow: 0 20px 25px -15px rgba(0, 0, 0, 0.3);
		}
		.timeline-content::after {
		  content: '';
		  position: absolute;
		  border-style: solid;
		  width: 0;
		  height: 0;
		  top: 30px;
		  right: -15px;
		  border-width: 10px 0 10px 15px;
		  border-color: transparent transparent transparent #f5f5f5;
		}

		.timeline-img {
		  width: 30px;
		  height: 30px;
		  background: #3F51B5;
		  border-radius: 50%;
		  position: absolute;
		  left: 50%;
		  margin-top: 25px;
		  margin-left: -15px;
		}

		.header-timeline a {
		  background: #3F51B5;
		  color: #FFFFFF;
		  padding: 8px 20px;
		  text-transform: uppercase;
		  font-size: 14px;
		  margin-bottom: 20px;
		  margin-top: 10px;
		  display: inline-block;
		  border-radius: 2px;
		  box-shadow: 0 1px 3px -1px rgba(0, 0, 0, 0.6);
		}
		.header-timeline a:hover, a:active, a:focus {
		  background: #32408f;
		  color: #FFFFFF;
		  text-decoration: none;
		}

		.timeline-card {
		  padding: 0 !important;
		}
		.timeline-card p {
		  padding: 0 20px;
		}
		.timeline-card a {
		  margin-left: 20px;
		}

		.timeline-item .timeline-img-header {
		  background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4)), url("https://picsum.photos/1000/800/?random") center center no-repeat;
		  background-size: cover;
		}

		.timeline-img-header {
		  height: 200px;
		  position: relative;
		  margin-bottom: 20px;
		}
		.timeline-img-header h2 {
		  color: #FFFFFF;
		  position: absolute;
		  bottom: 5px;
		  left: 20px;
		}

		.timeline-item blockquote {
		  margin-top: 30px;
		  color: #757575;
		  border-left-color: #3F51B5;
		  padding: 0 20px;
		}

		.date {
		  background: #FF4081;
		  display: inline-block;
		  color: #FFFFFF;
		  padding: 10px;
		  position: absolute;
		  top: 0;
		  right: 0;
		}

		@media screen and (max-width: 768px) {
		  .timeline::before {
		    left: 50px;
		  }
		  .timeline .timeline-img {
		    left: 50px;
		  }
		  .timeline .timeline-content {
		    max-width: 100%;
		    width: auto;
		    margin-left: 70px;
		  }
		  .timeline .timeline-item:nth-child(even) .timeline-content {
		    float: none;
		  }
		  .timeline .timeline-item:nth-child(odd) .timeline-content::after {
		    content: '';
		    position: absolute;
		    border-style: solid;
		    width: 0;
		    height: 0;
		    top: 30px;
		    left: -15px;
		    border-width: 10px 15px 10px 0;
		    border-color: transparent #f5f5f5 transparent transparent;
		  }
		}
  </style>
@endsection

@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/scrollreveal@4.0.5/dist/scrollreveal.min.js"></script>
<script>
	$(function(){

	  window.sr = ScrollReveal();

	  if ($(window).width() < 768) {

	  	if ($('.timeline-content').hasClass('js--fadeInLeft')) {
	  		$('.timeline-content').removeClass('js--fadeInLeft').addClass('js--fadeInRight');
	  	}

	  	sr.reveal('.js--fadeInRight', {
		    origin: 'right',
		    distance: '300px',
		    easing: 'ease-in-out',
		    duration: 800,
		  });

	  } else {
	  	
	  	sr.reveal('.js--fadeInLeft', {
		    origin: 'left',
		    distance: '300px',
			  easing: 'ease-in-out',
		    duration: 800,
		  });

		  sr.reveal('.js--fadeInRight', {
		    origin: 'right',
		    distance: '300px',
		    easing: 'ease-in-out',
		    duration: 800,
		  });

	  }
	  
	  sr.reveal('.js--fadeInLeft', {
		    origin: 'left',
		    distance: '300px',
			  easing: 'ease-in-out',
		    duration: 800,
		  });

		  sr.reveal('.js--fadeInRight', {
		    origin: 'right',
		    distance: '300px',
		    easing: 'ease-in-out',
		    duration: 800,
		  });
	});
</script>

<script>
	// $(function () {

		function createTimeLine(html) {
			$('#wrapper').prepend(html);
		}

		$('#updateStatus').on('click', function () {
			var statusId = $('#employementStatus').find('option:selected').val();
			var statusChangeDate = $('#status_change_date').val();

			if(statusId === null || statusId === '') alert('Please Select Status');

			$.ajax({
				url: "/{{ $crud->route . '/' . $id . '/review-status/update' }}",
				data: {
					status_id: parseInt(statusId),
					status_change_date: statusChangeDate,
				},
				type: 'get',
				success: function (response) {
					console.log(response);
					if(response.error) {
						new PNotify({ type: 'error', text: response.message });
						return;
					}
					new PNotify({ type: 'success', text: response.message });
					var timeline = '<div class="timeline-item">\
								<div class="timeline-img"></div>\
									<div class="timeline-content js--fadeIn">\
										<h2>' + response.data.employment_status.name + '</h2>\
										<div class="date">' + response.data.formatted_date + '</div>\
									</div>\
							</div>';
							createTimeLine(timeline);
				}
			});
		});
	// });
</script>
@endsection
