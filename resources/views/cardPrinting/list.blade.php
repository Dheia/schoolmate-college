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
		
	@if($entry !== null)
	<div class="row">
		<div class="col-md-12 m-b-10">
			
			<div class="form-group">
				<div class="col-md-3" style="padding-left: 0; padding-right: 0;">
					<div class="form-inline">
					  <div class="form-group col-md-9" style="padding: 0 !important;">
					    <input type="text" class="form-control" id="searchInput" placeholder="Search Student Here..." style="width: 100%;">
					  </div>
					  <div class="col-md-3" style="padding: 0 !important;">
					  	<button id="searchStudent" class="btn btn-primary">Search Student</button>
					  </div>
					</div>
{{-- 
					<label for="searchStudent">Search Student</label>
					<input type="text" class="form-control" id="searchInput">
					<button id="searchStudent" class="btn btn-primary">Search Student</button> --}}
				</div>
				<div class="col-md-3">
					<div class="form-inline">
						<div class="form-group col-md-9" style="padding: 0 !important;">
							<select name="" id="template" class="form-control" style="width: 100%;">
								@foreach($templates as $template)
									<option value="{{ $template->id }}" {{ $template->active ? 'selected' : '' }}>{{ $template->template_name }}</option>
								@endforeach
							</select>
						</div>
					  	<div class="col-md-3" style="padding: 0 !important;">
						  <button id="changeTemplate" class="btn btn-primary">Change Template</button>
						</div>
					</div>
				</div>
			{{-- 	<div class="col-md-6">
					<label for="template">Select Template</label>
					<select name="" id="template" class="form-control col-6">
						<option value="">Template1</option>
						<option value="">Template2</option>
					</select>
				</div> --}}
				{{-- <div class="col-md-12 m-t-10" style="padding-left: 0; padding-right: 0;">
					<button id="searchStudent" class="btn btn-primary">Search Student</button>
				</div> --}}
			</div>
		</div>
		
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
			
			<div class="form-group m-t-20">
				<label title="Template" for="name"><span class="mdi mdi-image"> Template Name</span></label>
				<input type="text" id="name" name="name" class="form-control" value="{{ $entry->template_name }}" required/>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" id="printPDF"><i class="fa fa-print"></i>&nbsp; Print</button>
			</div>

			{{-- <div class="col-md-12">
				<button class="btn btn-block btn-primary" id="btnJson">Save</button>
			</div> --}}
		</div>
		{{-- Front --}}
		
		<div class="col-md-5" style="margin: auto;">
			<canvas id="canvas_front" width="638" height="1013px" style="border: 1px solid #ccc;"></canvas>
		</div>
		{{-- Back --}}
		<div class="col-md-5" style="margin: auto;">
			<canvas id="card_back" width="638" height="1013px" style="border: 1px solid #ccc;"></canvas>
		</div>
	</div>
	@else
		<div><h1 class="text-center" style="margin-top: 100px;"><i class="fa fa-newspaper-o fa-5x"></i><br>NO ACTIVE TEMPLATE FOUND</h1></div>
	@endif


<!-- Modal -->
<div id="studentsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search &nbsp;
                    <small>
                        [ <span id="currentPage"></span> - <span id="lastPage"></span> ]
                    </small>
                </h4>
            </div>
            <div class="modal-body">
                
                <center>
                    <img class="img-responsive" src="{{ asset('images/magnify-glass-200px.gif') }}" alt="Magnifying Glass">
                    <h3>Searching for <span id="searchString"></span></h3> 
                </center>
                
            </div>
        </div>

    </div>
</div>
@endsection

@section ('after_styles')
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
	<style>
		@import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');
		#card_back {
			border:0px solid #ccc;
		}
	</style>
@endsection

@section('after_scripts')
	@if($entry !== null)
		<script src="{{ asset('/js/fabric.js') }}"></script>
		<script src="https://cdn.jsdelivr.net/npm/lodash@3.10.1/index.min.js"></script>

		{{-- // SCRIPT FOR FRONT --}}
		<script src="{{ asset("/js/smartcard/front_card.js") }}"></script>
		<script src="{{ asset("/js/smartcard/back_card.js") }}"></script>

		<script>
			{{-- LOAD FRONT AND BACK --}}
			{{-- {{ dd(json_decode($entry->front_card)) }} --}}
			canvas_front.loadFromJSON('{!! json_encode($entry->front_card) !!}', function() {
			   canvas_front.renderAll(); 
			},function(o,object){
			   // console.log(o,object)
			})

			canvas_back.loadFromJSON('{!! json_encode($entry->rear_card) !!}', function() {
			   canvas_front.renderAll(); 
			},function(o,object){
			   // console.log(o,object)
			})
		</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

		<script>

			$('#changeTemplate').click(function () {
				console.log('template changed');
				$.ajax({
					url: '/{{ $crud->route }}/change-template',
					data: {
						template_id: $('#template').val(),
					},
					success: function (response) {
						location.reload()
					}
				})
			});

			function findByClipName(name) {
			    return _(canvas_front.getObjects()).where({
			            clipFor: name
			        }).first()
			}

			var clipByName = function (ctx) {
				// console.log('this = ', this);
			    this.setCoords();
			    var clipRect = findByClipName(this.clipName);
			    var scaleXTo1 = (1 / this.scaleX);
			    var scaleYTo1 = (1 / this.scaleY);
			    ctx.save();

			    // console.log("a = ", clipRect);
			    var ctxLeft = -( this.width / 2 ) + clipRect.strokeWidth;
				var ctxTop = -( this.height / 2 ) + clipRect.strokeWidth;
				// var ctxWidth = clipRect.cacheHeight - clipRect.strokeWidth;
				var ctxWidth = ( clipRect.width * clipRect.scaleX ) - clipRect.strokeWidth;
				var ctxHeight = ( clipRect.height * clipRect.scaleY ) - clipRect.strokeWidth;

			    ctx.translate( ctxLeft, ctxTop );
			    ctx.rotate(degToRad(this.angle * -1));
			    ctx.scale(scaleXTo1, scaleYTo1);
			    ctx.beginPath();
			    ctx.rect(

			        clipRect.left - this.aCoords.tl.x,
			        clipRect.top - this.aCoords.tl.y,
			        // clipRect.cacheWidth ,
			        ctxWidth ,
			        // clipRect.cacheHeight ,
			        ctxHeight ,
			    );
			    ctx.closePath();
			    ctx.restore();
			}
			function degToRad(degrees) {
			    return degrees * (Math.PI / 180);
			}

			function prepareRectangle (obj, front_items) {
				var objW = obj.width;
				var objH = obj.height;
				var originX = obj.getPointByOrigin().x;
				var originY = obj.getPointByOrigin().y;
				var photo = new Image();
					photo.src = front_items['photo'];

				obj.set({ clipFor: 'avatar' });

				photo.onload = function () {
					var avatar = 	new fabric.Image(photo, {
						left: ( originX / 2) - 1,
						top: originY - ( photo.height / 2 ),
						clipName: 'avatar',
						clipTo: function(ctx) {
							return _.bind(clipByName, avatar)(ctx)
						},
					});
					canvas_front.add(avatar);
				}
			}

			var _studentnumber = null;
			var _printed = false;


			function selectStudent(studentnumber) {
				console.log(studentnumber);
				_studentnumber = studentnumber;
           		$.ajax({
           			url: 'card-printing/student/' + studentnumber,
           			success: function (response) {

 						// FRONT
 						var front_items = response.front_card_items;
 							console.log(front_items);
 						for(var i = 0; i < canvas_front._objects.length; i++) {
 							// PHOTO
 							// RECT
 							if(canvas_front._objects[i].type === "rect" && canvas_front._objects[i].id === "photo") {
 								var objW 	= canvas_front._objects[i].width;
 								var objH 	= canvas_front._objects[i].height;
 								var originX = canvas_front._objects[i].getPointByOrigin().x;
 								var originY = canvas_front._objects[i].getPointByOrigin().y;
 								var photo 	= new Image();
 								photo.src 	= front_items['photo'];

 								canvas_front._objects[i].set({ clipFor: 'avatar' });

 								photo.onload = function () {
	 								var avatar = new fabric.Image(photo, {
	 									left: ( objW / 2 ),
	 									top: originY - ( photo.height / 2 ),
	 									clipName: 'avatar',
	 									clipTo: function(ctx) {
	 										return _.bind(clipByName, avatar)(ctx)
	 									},
	 								});
									canvas_front.add(avatar);
 								}
 							}
 							// TEXT
 							if(canvas_front._objects[i].id !== null && canvas_front._objects[i].type == "textbox") {
 								console.log("TEXTS = ", front_items[canvas_front._objects[i].id]);
								console.log("sad = ", canvas_front._objects[i]);

 								if(front_items[canvas_front._objects[i].id] !== null) {
 									canvas_front._objects[i].text = front_items[canvas_front._objects[i].id].toString();
 								}
 								// if( canvas_front._objects[i]).width > canvas_front._objects[i]).fixedWidth 

 								// if(canvas_front._objects[i].getMinWidth() > canvas_front._objects[i].width) {
 								// 	canvas_front._objects[i].fontSize = canvas_front.width / (canvas_front._objects[i].width + 1);
 								// }

								// canvas_front.on('text:changed', function(opt) {
								// 	  var t1 = opt.target;
								// 	  if (t1.width > t1.fixedWidth) {
								// 	    t1.fontSize *= t1.fixedWidth / (t1.width + 1);
								// 	    t1.width = t1.fixedWidth;
								// 	  }
								// });
						

							} else {
								canvas_front._objects[i].text = "NULL";
							}
 						}
 						

 						// BACK ITEMS
 						var back_items = response.back_card_items;
 							console.log(back_items);
 						for(var i = 0; i < canvas_back._objects.length; i++) {
 							// PHOTO
 							// RECT
 							if(canvas_back._objects[i].type === "rect" && canvas_back._objects[i].id === "photo") {
 								var objW 	= canvas_back._objects[i].width;
 								var objH 	= canvas_back._objects[i].height;
 								var originX = canvas_back._objects[i].getPointByOrigin().x;
 								var originY = canvas_back._objects[i].getPointByOrigin().y;
 								var photo 	= new Image();
 								photo.src 	= back_items['photo'];

 								canvas_back._objects[i].set({ clipFor: 'avatar' });

 								photo.onload = function () {
	 								var avatar = new fabric.Image(photo, {
	 									left: ( objW / 2 ),
	 									top: originY - ( photo.height / 2 ),
	 									clipName: 'avatar',
	 									clipTo: function(ctx) {
	 										return _.bind(clipByName, avatar)(ctx)
	 									},
	 								});
									canvas_back.add(avatar);
 								}
 							}
 							// TEXT
 							if(canvas_back._objects[i].id !== null && canvas_back._objects[i].type == "textbox") {
 								console.log("TEXTS = ", back_items[canvas_back._objects[i].id]);
								console.log("sad = ", canvas_back._objects[i]);
 				
								if(back_items[canvas_back._objects[i].id] !== null) {
									canvas_back._objects[i].text = back_items[canvas_back._objects[i].id].toString();
 								}
 								// if( canvas_back._objects[i]).width > canvas_back._objects[i]).fixedWidth 

 								// if(canvas_back._objects[i].getMinWidth() > canvas_back._objects[i].width) {
 								// 	canvas_back._objects[i].fontSize = canvas_back.width / (canvas_back._objects[i].width + 1);
 								// }

								// canvas_back.on('text:changed', function(opt) {
								// 	  var t1 = opt.target;
								// 	  if (t1.width > t1.fixedWidth) {
								// 	    t1.fontSize *= t1.fixedWidth / (t1.width + 1);
								// 	    t1.width = t1.fixedWidth;
								// 	  }
								// });
						

							} else {
								canvas_back._objects[i].text = "NULL";
							}
 						}
 						canvas_front.renderAll();
 						canvas_back.renderAll();
           			}
           		});
				// AJAX

            	$('#studentsModal').modal('hide');
	        }

			function disablePaginateButton() {
	            $('#prev').attr('disabled', true);
	            $('#next').attr('disabled', true);
	        }

	        function enablePaginateButton() {
	            $('#prev').removeAttr('disabled');
	            $('#next').removeAttr('disabled');
	        }

			function requestPage (url) {
                disablePaginateButton();
                $.ajax({
                    url: url,
                    success: function (response) {
                        var students = "";
                        $.each(response.data, function (key, val) {
                            students += "<tr id='student-" + val.id + "'>\
                                            <td id='student-number'     style='vertical-align:middle'>" + val.studentnumber + "</td>\
                                            <td id='student-fullname'   style='vertical-align:middle'>" + val.firstname + ' ' + val.middlename + ' ' + val.lastname + "</td>\
                                            <td id='student-level'      style='vertical-align:middle'>" + val.current_enrollment + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + val.studentnumber + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        $('#studentsModal .modal-body table > tbody').html(students);

                        $('#currentPage').text(response.current_page);
                        $('#lastPage').text(response.last_page);


                        var prev = '';
                        var next = '';

                        if(response.prev_page_url !== null) {
                            prev = '<li><a id="next" href="#" onclick="requestPage(\'' + response.prev_page_url + '\')">Previous</a></li>';
                        }

                        if(response.next_page_url !== null) {
                            next = '<li><a id="next" href="#" onclick="requestPage(\'' + response.next_page_url + '\')">Next</a></li>';
                        }

                        $('#studentsModal .modal-body nav > ul').html(prev + next);

                        enablePaginateButton()
                    }
                });
        	}

			function searchStudent () {
                var searchInput = $('#searchInput').val();

                if(searchInput.length == 0)  {
                    return false;
                }

                $('#studentsModal').modal('toggle');
                $('#searchString').text('"' + searchInput + '"');

                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/student/search/' + searchInput,
                    success: function (response) {
                        var students = "";
                        $.each(response.data, function (key, val) {
                            var json = JSON.stringify(val, undefined, '\t');
                            students += "<tr id='student-" + val.id + "'>\
                                            <td id='student-number'     style='vertical-align:middle'>" + val.studentnumber + "</td>\
                                            <td id='student-fullname'   style='vertical-align:middle'>" + val.firstname + ' ' + val.middlename + ' ' + val.lastname + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + val.studentnumber + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        var studentsTable = "<table class='table table-striped table-bordered'>\
                                                <thead>\
                                                    <th>StudentNumber</th>\
                                                    <th>Fullname</th>\
                                                    <th>SELECT</th>\
                                                </thead>\
                                                <tbody>\
                                                    " + students + "\
                                                </tbody>\
                                            </table>";

                        $('#currentPage').text(response.current_page);
                        $('#lastPage').text(response.last_page);

                        var next = '';
                        if(response.next_page_url !== null) {
                            next = '<li><a id="next" href="#" onclick="requestPage(\'' + response.next_page_url + '\')">Next</a></li>';
                        } 
                        var paginationNav = '<nav aria-label="...">\
                                                  <ul class="pager">\
                                                    ' + next + '\
                                                  </ul>\
                                                </nav>';

                        $('#studentsModal .modal-body').html(studentsTable + paginationNav);
                    }
                });
            }

            $('#studentsModal').on('hidden.bs.modal', function (e) {
                var imageURL = "{{ asset('images/magnify-glass-200px.gif') }}";
                $(this).find('.modal-body').html('<center>\
					                                <img class="img-responsive" src="'+imageURL+'" alt="Magnifying Glass">\
					                                <h3>Searching for <span id="searchString"></span></h3>\
					                            </center>');
            })

			$('#searchStudent').click(function () {
                searchStudent();
            });

            $('#searchStudent').keypress(function (e) {
                if(e.which == 13) {
                    searchStudent();
                }
            });

		</script>

		<script>
			jQuery(document).ready(function ($) {
				$('#printPDF').click(function () {

					var form = document.createElement("form");
				    var front = document.createElement("input"); 
				    var csrf = document.createElement("input"); 
				    var back = document.createElement("input");  

				    form.method = "POST";
				    form.action = "{{ url($crud->route) . "/print" }}";   
				    form.target = '_blank';

				    front.type 	= 'text';
				    back.type 	= 'text';
				    csrf.type 	= 'text';

				    front.name 	= 'front';
				    back.name 	= 'back';
				    csrf.name   = '_token';
				    
				    front.value = canvas_front.toDataURL('png');
				    back.value 	= canvas_back.toDataURL('png');
				    csrf.value 	= $('meta[name="csrf-token"]').attr('content');

				    form.appendChild(csrf);
				    form.appendChild(front);  
				    form.appendChild(back);
				    document.body.appendChild(form);
				    form.submit();
				    form.parentNode.removeChild(form);

					$.confirm({
					    title: 'Confirm!',
					    content: 'Mark as Printed?',
					    buttons: {
					        yes: function () {
					            $.ajax({
					            	url: '/{{ $crud->route }}/save-logs',
					            	data: {
					            		studentnumber: _studentnumber,
					            		printed: 1,
					            	},
					            	success: function (response) {
					            		console.log(response);
					            	}
					            });
					        },
					        no: function () { },
					    }
					});
					_printed = false;
				})
			});
		</script>
	@endif
@endsection