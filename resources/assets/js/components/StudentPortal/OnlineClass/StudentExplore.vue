<template>
	<div class="box shadow" style="min-height: 225px;" v-if="isLoading || errored">
        <div class="box-body with-border" style="padding: 20px !important;">
			<!-- Loading GIF -->
            <div class="row" v-if="isLoading">
				<div style="padding-top: 80px;">
			        <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
			    </div>
			</div>
			<!-- Error -->
			<div class="row" v-else-if="errored">
				<img class="img-responsive" v-bind:src="'/images/error-sorry.png'" alt="Loading..." style="margin: auto; height: 130px;">
			    <h3 class="text-center">Please Reload The Page.</h3>
			</div>

		</div>
	</div>

	<div class="row" v-else-if="!isLoading && !errored">
		<!-- Class Posts -->
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" v-if="totalPosts > 0">
			<div v-for="(post, index) in classPosts">
				<!-- Post Box -->
				<div class="box shadow">
					<!-- Post Box Body -->
					<div class="box-body with-border" style="padding: 20px 20px 0px 20px !important;">

						<div class="row posted_by">
			             <div class="col-xs-12 col-md-12 col-lg-12 class-post">
								<!-- Posted By Image -->
			                	<!-- <a class="thumbnail" style="border-radius: 50%; overflow: hidden; padding: 0px; margin-left: auto; margin-right: auto;">
			                    	<img :src="'/'+post.poster_photo" alt="...">
			                  	</a> -->
			                	<!-- Posted By Name -->
			                  	<!-- <h4 class="post_by" style="">
			                    	<strong>{{ post.poster_name }}</strong>
			                 	</h4> -->
			                 	<!-- Class Name -->
			                 	<!-- <h4 class="post_by" style="font-size: 14px;">
			                    	<a v-bind:href="'online-post?class_code=' + post.class_code">{{ post.class.name }}</a>
			                 	</h4> -->
			                 	<!-- Date / Time Posted -->
			                  	<!-- <h5 class="post_at">
			                    	{{ moment(String(post.created_at)).format('MMMM DD, YYYY - h:mm a') }}
			                  	</h5>
			                </div>
		              	</div> -->
						<div class="row-profile">
							<div class="column-profile-pic">
								<!-- Posted By Image -->
			                    	<img :src="'/'+post.poster_photo" alt="..." class="profile-pic">
							</div>
							<div class="column-profile-name">
								<!-- Posted By Name -->
			                  	<h4 class="post_by " style="font-size:14px;" >
			                    	<strong style="font-size:16px;">{{ post.poster_name }}</strong> posted to {{ post.class.level_name }} - <a v-bind:href="'online-post?class_code=' + post.class_code" style="font-size:14px;">{{ post.class.subject_code }}</a>
			                 	</h4>
								 <!-- Date / Time Posted -->
			                    <h5 class="post_at">
			                    	{{ moment(String(post.created_at)).format('MMMM DD, YYYY - h:mm a') }} 
			                  	</h5>
							</div>
						</div>
						 </div>
						</div>

		              	<!-- Post Cotent -->
		              	<h4 class="post_desc ckeditor text-left pb-0 m-l-20 m-t-20 m-b-20" v-html="post.content"></h4>
		              	<!-- Quiz -->
		              	<div v-if="post.class_quiz">
		                 	<div v-if="post.class_quiz.quiz" class="quiz-post__container" style="border: 1px solid #eee8e8; padding: 2rem 4rem; border-radius: 10px; margin-bottom: 10px;">
		                 		<div class="row">
			                 		<div class="col-md-6">
				                 		<div class="quiz-post__title">
				                 		</div>
			                 			<div class="quiz-post__description">
			                 				<h4><b>{{ post.class_quiz.quiz.title }}</b></h4>
			                 				<p class="m-b-0"><b>DATE</b></p>
			                 				<p class="m-b-0"><b>Start: </b>{{ moment(String(post.class_quiz.start_at)).format('MMMM DD, YYYY - h:mm a') }}</p>
			                 				<p><b>End: </b>{{ moment(String(post.class_quiz.end_at)).format('MMMM DD, YYYY - h:mm a') }}</p>

			                 				<p class="m-b-0">{{ post.class_quiz.quiz.total_questions }} questions</p>
			                 			</div>
		                 			</div>

		                 			<div class="col-md-6">
		                 				<div v-if="currentDate >= post.class_quiz.start_at">
		                 					<div v-if="currentDate <= post.class_quiz.end_at || post.class_quiz.allow_late_submission">
		                 						<div v-if="post.class_quiz.allow_retake || !submitted_quiz.includes(post.class_quiz.id)">
		                 							<form v-bind:action="'/student/online-class-quizzes/' + post.class_quiz.id + '/start'" method="POST" target="_blank">
		                 								<input type="hidden" name="_token" :value="csrf">
 		                 								<button v-if="post.class_quiz.allow_retake || !submitted_quiz.includes(post.class_quiz.id)" class="btn btn-primary float-right" type="submit">Take Quiz</button>
 		                 							</form>
		                 						</div>
		                 					</div>
		                 				</div>
		                 				<div class="clearfix"></div>
		                 			</div>
	                 			</div>
		                 	</div>
		              	</div>
		              	<!-- Post Files -->
		              	<div class="row" v-if="post.files.length > 0">
                			<div class="col-xs-12 col-md-12 col-lg-12">
								
								<div v-for="(file, fileIndex) in post.files_with_extension">
									<div class="column">
										<!-- Image -->
										<div  v-if="file['extension'] == 'img'" style="margin-left:auto; margin-right:auto;" >
											<img :src="spaces_url + file['filepath']" style="max-width: 100%; max-height: 300px; padding: auto; ">
										</div>
										<!-- File -->
										<div v-else class="card">
											<div class="column-icon">
												<div  class="card-icon" >
													<!-- PDF ICON -->
													<img v-if="file['extension'] == 'pdf'" src="/images/icons/pdf.jpg" style="width:30px; margin-top:5px; margin-left:5px; background-color:white;">
													<!-- WORD ICON -->
													<img v-else-if="file['extension'] == 'docx'" src="/images/icons/word.jpg" style="width:30px; margin-top:5px; margin-left:5px; background-color:white;">
													<!-- POWERPOINT ICON -->
													<img v-else-if="file['extension'] == 'pptx'" src="/images/icons/powerpoint.jpg" style="width:30px; margin-top:5px; margin-left:5px; background-color:white;">
													<!-- EXCEL ICON -->
													<img v-else-if="file['extension'] == 'xlsx'" src="/images/icons/excel.jpg" style="width:30px; margin-top:5px; margin-left:5px; background-color:white;">
												</div>
											</div>
											<div class="auto-center">
												<a class="font-serif-bold" href="javascript:void(0)" @click="showDocs(file['filepath'])">
													{{ file['filename'] }}
												</a>
												&nbsp; &nbsp; &nbsp;
											</div>
											<div class="auto-center">
												<a class="font-serif-bold float-right" :href="spaces_url + file['filepath']" :download="spaces_url + file['filepath']" target="_blank">
													<i class="fa fa-download"></i>
												</a>
											</div>

										</div>
										<br>
									</div>
								</div>
                			</div>
                		</div>

					</div>
					<!-- Body Footer -->
					<div class="box-footer class-status" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
						<!-- Like Button -->
						<div @click="likePost(post.class.code, post.id)" class="col-md-6 col-lg-6 col-sm-6 col-xs-6 text-center btn-star" data-toggle="tooltip" data-placement="top" title="Like" style="border-bottom: 1px #f4f4f4 solid; ">
							<span v-if="post.likes">
				        		<a :id="'btnLike'+post.id" href="javascript:void(0)" :class="post.student_likes.includes(student.id) ? 'star-on' : 'star-off'">
				        			<i class="fa fa-star"></i> Like {{ post.likes.length > 0 ? '('+post.likes.length+')' : '' }}
				        		</a>
				      		</span>
				      		<span v-else>
				        		<a :id="'btnLike'+post.id" href="javascript:void(0)" :class="post.student_likes.includes(student.id) ? 'star-on' : 'star-off'">
				        			<i class="fa fa-star"></i> Like 
				        		</a>
				      		</span>
				      	</div> 
				      	<!-- Comment Button -->
				      	<div title="Comment" href="javascript:void(0)" class="col-md-6 col-lg-6 col-sm-6 col-xs-6 text-center btn-comment" @click="toggleComment(post.id)" style="border-bottom: 1px #f4f4f4 solid; ">
				      		<span v-if="post.comments">
				          		<i class="fa fa-comments"></i> Comment {{ post.comments.length > 0 ? '('+post.comments.length+')' : '' }}
				        	</span>
				        	<span v-else>
				          		<i class="fa fa-comments"></i> Comment
				        	</span>
				        </div>

				        <!-- Post's Comments -->
				        <div class="text-left comment-section hidden" :id="'postComments'+post.id">
				        	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 pl-0 pr-0 pt-0">

				        		<!-- Comment -->
				        		<div class="no-padding" v-if="post.comments">
				        			<div class="no-padding" v-if="post.comments.length > 0">
			        					<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 p-t-5 pb-0" v-for="(comment, commentIndex) in post.comments" :id="'postComment'+comment.id">
					        				<div class="col-sm-2 col-xs-2 col-md-2 col-lg-1 no-padding">
					        					<div class="comment-thumbnail">
								                  	<a class="thumbnail">
								                    	<img :src="'/'+comment.commentable.photo" alt="...">
								                  	</a>
					        					</div>
							                </div>
							                <div class="col-sm-10 col-xs-10 col-md-10 col-lg-11 pl-0 pr-0 pb-0 p-t-5">
							                	<div class="user-comment pt-0 pb-0 m-l-10">
							                		<!-- Delete Comment -->
							                		<a href="javascript:void(0)" @click="deleteComment(post.class_code, post.id, comment.id)" class="pull-right p-t-5 p-r-5" v-if="student.studentnumber == comment.commentable.studentnumber"><i class="fa fa-times"></i></a>
									                <!-- Comment By -->
								                	<p class="comment_by mr-0 mb-0">
								                		{{comment.commentable.firstname + ' ' + comment.commentable.lastname }}
								                	</p>
								                	<!-- Comment Content -->
								                	<p v-html="comment.content" class="comment_content mr-0 mb-0">
								                	</p>
							                	</div>
								      		</div>
			        					</div>
				        			</div>
				        		</div>
				        		<!-- End Of Comment -->

					      		<!-- User Comment Input -->
					      		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 p-t-5 pb-0">
						        	<div class="col-sm-2 col-xs-2 col-md-2 col-lg-1 no-padding">
						        		<div class="input-comment-thumbnail">
						                  	<a class="thumbnail" style="border-radius: 50%; overflow: hidden; padding: 0px; margin-bottom: 0;">
						                    	<img :src="'/'+student.photo" alt="...">
						                  	</a>
						        		</div>
					                </div>
					                <div class="col-sm-10 col-xs-10 col-md-10 col-lg-11 p-l-10 pr-0 pb-0 p-t-5">
						      			<input :id="'input-comment-'+post.id" class="form-control form-control-sm input-comment" type="text" placeholder="Post your comment" @keyup.enter.exact="addComment(post.class_code, post.id, $event.target.value)">
						      		</div>
					        	</div>
					        	<!-- End Of User Comment Input -->

				        	</div>
				        </div>
				        <!-- End Of Post's Comments -->

				    </div>
				</div>
				<!-- End Of Post Box -->

			</div>
			<!-- All Posts Loaded -->
			<div v-if="nextPage == null && !isLoadingMore" class="text-center">
				<h5>All posts are loaded.</h5>
			</div>
			<!-- Loading More GIF -->
			<div v-if="isLoadingMore" style="margin-bottom: 20px;">
				<img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
			</div>
			<!-- Load More Button -->
			<div v-if="nextPage !== null && !isLoadingMore">
				<a v-if="nextPage !== null" href="javascript:void(0)" @click="requestPage(nextPage)" class="btn btn-info w-100">Load More</a>
			</div>
		</div>
		<!-- End Of Class Posts -->

		<!-- No Post Found -->
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-t-20 p-b-20" v-if="!totalPosts > 0">
			<img class="img-responsive" v-bind:src="'/images/icons/explore.png'" alt="Loading..." style="margin: auto; height: 130px;">
    		<h3 class="text-center">No Posts Yet.</h3>
        </div>
        <!-- End Of No Post Found -->

		<!-- GOOGLE DOCS MODAL IFRAME -->
		<div id="docs-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<iframe id="docs-iframe" :src="iframsrc" frameborder="0" style="width:100%; height:100%; min-height: 80vh;"></iframe>
					</div>
				</div>
			</div>
		</div>
		<!-- END OF GOOGLE DOCS MODAL IFRAME -->
	</div>

</template>

<script>
 	import _ from 'lodash';
 	import moment from 'moment'

 	export default {
	 	props: ['user', 'school_id', 'submitted_quiz', 'spaces_url'],
	    data() {
	    	// var date = new Date();
	    	// var currentDate = date.toLocaleTimeString('PST');
	    	var currentDate = moment().format();
	    	// console.log(currentDate);
	        return {
	        	moment: moment,
	        	schoolID: this.school_id,
	        	student: this.user,
             	searchbar: '',
             	isLoading: true,
             	classPosts: [],
             	baseUrl: location.protocol + '//' + location.host,
             	nextPage: null,
             	prevPage: null,
             	currentPage: null,
             	lastPage: null,
             	totalPosts: 0,
             	errored: false,
             	isLoadingMore: false,
             	currentDate: currentDate,
             	csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				iframsrc: null
	        }
	    },
	    methods: {
			// Show Docs IFrame
			showDocs(url) {
				this.iframsrc = 'https://docs.google.com/viewer?url=' + this.spaces_url + '/' + url + '&embedded=true';
				// this.iframsrc = 'https://docs.google.com/viewer?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true';
				$('#docs-modal').modal('show');
				// $('#docs-ifram').val(url);
			},

	    	// Get Class Posts
	    	getPosts: async function() {
             	this.isLoading = true;       
        	 	await axios.get('/student/online-post/api/get/class-posts')
                 	.then(response => {
                 		// console.log(response);
                     	this.classPosts = response.data.posts.data;
                     	if(response.data.posts.next_page_url){    
                         	this.nextPage    = response.data.posts.next_page_url;
                     	}
                     	else{
                         	this.nextPage    = response.data.posts.next_page_url;
                     	}
                     	if(response.data.posts.prev_page_url){    
                         	this.prevPage   = response.data.posts.prev_page_url;
                     	}
                     	else{
                         	this.prevPage   = response.data.posts.prev_page_url;
                     	}
                     	this.currentPage   	= response.data.posts.current_page;
                     	this.lastPage      	= response.data.posts.last_page;
                     	this.totalPosts    	= response.data.posts.total;
                     	this.isLoading   	= false;
                     	this.errored 		= false;

                 	})
                 	.catch(error => {
				        // console.log(error);
				        this.isLoading   	= false;
				        this.errored 		= true;
				        new PNotify({
	                      	title: 'Error',
	                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	});
				    });
				
         	},
         	// Load More Posts
         	requestPage(url) {
         		let _this = this;
	            this.isLoadingMore   = true;
	            axios.get(url)
	                .then(response => {
	                	// Push Posts To The Array
	                	$.each(response.data.posts.data, function(key, value) {
                                _this.classPosts.push(value);
                        });
	                    if(response.data.posts.next_page_url){    
                         	this.nextPage    	= response.data.posts.next_page_url;
                     	}
                     	else{
                         	this.nextPage    	= response.data.posts.next_page_url;
                     	}
                     	if(response.data.posts.prev_page_url){    
                         	this.prevPage   	= response.data.posts.prev_page_url;
                     	}
                     	else{
                         	this.prevPage   	= response.data.posts.prev_page_url;
                     	}
                     	this.currentPage   		= response.data.posts.current_page;
                     	this.lastPage      		= response.data.posts.last_page;
                     	this.totalPosts    		= response.data.posts.total;
                     	this.errored 			= false;
                     	this.isLoadingMore   	= false;
	                })
	                .catch(error => {
				        // console.log(error);
				        this.errored 			= true;
				        this.isLoadingMore   	= false;
				        new PNotify({
	                      	title: 'Error',
	                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	});
				    });
			    
	        },
	        // Like Post
	        likePost(class_code, post_id) {
	        	let _this = this;
	        	axios.post('/student/online-post/'+post_id+'/like', {
	        		class_code 	: 	class_code,
		            post_id   	: 	post_id
		        }).then(res => {
		            if(res.data.title == 'Success' && res.data.error == false) {
		        		// Update Like Button Color
		        		$("#btnLike"+res.data.data.id).removeClass(res.data.data.student_likes.includes(_this.student.id) ? "star-off" : "star-on");
		        		$("#btnLike"+res.data.data.id).addClass(res.data.data.student_likes.includes(_this.student.id) ? "star-on" : "star-off");
		        	}
		        	else {
		        		new PNotify({
	                      	title: 'Error',
	                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	});
		        	}
		        	// console.log(res.data);
		        }).catch(error => {
		        	new PNotify({
                      	title: 'Error',
                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
                      	type: "error"
                  	});
		        	// alert('Error, Something Went Wrong, Please Try To Reload The Page.');
		            // console.log(error);
		        });
		        
	        },

	        // Add Comment To Post
	        addComment(class_code, post_id, content) {
	        	let _this = this;
	        	// Check if Comment has Content
	        	if(!content || content == null || !content.trim().length)
	        	{
	        	}
	        	else {
		        	axios.post('/student/online-post/'+post_id+'/comment', {
		        		class_code 	: 	class_code,
			            post_id   	: 	post_id,
			            content 	: 	content
			        }).then(res => {
			        	$("#input-comment-"+post_id).val('');
			        	// If Not Success
			        	if(res.data.title != 'Success' || res.data.error != false) {
							new PNotify({
		                      	title: 'Error',
		                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
		                      	type: "error"
		                  	});
			        	}
			        }).catch(error => {
			        	new PNotify({
	                      	title: 'Error',
	                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	});
			        	// alert('Error, Something Went Wrong, Please Try To Reload The Page.');
			            // console.log(error);
			        });
			    }
		        
		    },

		    // Delete Comment
	        deleteComment(class_code, post_id, comment_id){
	        	let _this = this;

	        	$.confirm({
			        title: 'Delete',
			        content: 'Are you sure you want to delete?',
			        buttons: {
			            cancel: function () {
			                // $.alert('Canceled!');
			            },
			            delete: {
			             	text: 'Delete', // text for button
			              	btnClass: 'btn-danger', // class for the button
			              	isHidden: false, // initially not hidden
			              	isDisabled: false, // initially not disabled
			              	action: function(event){
			                	$("body").addClass("loading");
			                	// $.alert('Confirmed!');
				                axios.post('/student/online-post/'+post_id+'/comment/'+comment_id+'/delete', {
					        		class_code 	: 	class_code,
						            post_id   	: 	post_id,
						            comment_id 	: 	comment_id
						        }).then(res => {
						        	// If Not Success
						        	if(res.data.title != 'Success' || res.data.error != false) {
										new PNotify({
					                      	title: 'Error',
					                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
					                      	type: "error"
					                  	});
						        	}
						        	$("body").removeClass("loading");
						        }).catch(error => {
						        	new PNotify({
				                      	title: 'Error',
				                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
				                      	type: "error"
				                  	});
				                  	$("body").removeClass("loading");
						        	// alert('Error, Something Went Wrong, Please Try To Reload The Page.');
						            // console.log(error);
						        });
				            }
				        }
				           
	        		}
	      		});
			    

	        },

	        // Toggle Post Comment | Hide / Show Comment
	        toggleComment(post_id) {
	        	// $("#postComments"+post_id).toggle();
	        	if($("#postComments"+post_id).hasClass( "show" ))
	        	{
	        		$("#postComments"+post_id).removeClass('show');
	        		$("#postComments"+post_id).addClass('hidden');
	        	}
	        	else 
	        	{
	        		$("#postComments"+post_id).addClass('show');
	        		$("#postComments"+post_id).removeClass('hidden');
	        	}
	        }
	    },

	    created() {
	    	// console.log("Reading - " + this.code)
	    	this.isLoading = true;
	        this.getPosts();
	        // console.log(this.submitted_quiz);
	    },
	    components: {

	    },
	    mounted() {
	    	let _this = this;
	    	// console.log("Component mounted.");

	    	// Like Post Listener
	    	Echo.channel(_this.schoolID + '-like-post-channel').listen('.' + _this.schoolID  + 'LikePostEvent', function (response) {
	    		// Update Post No. Of Likes
	    		var no_likes_id = 'btnLike' + response.data.id;
	    		if(response.data.likes.length > 0)
	    		{
	    			$("#"+no_likes_id).html('<i class="fa fa-star"></i> Like (' + response.data.likes.length + ')');
	    		}
	    		else {
	    			$("#"+no_likes_id).html('<i class="fa fa-star"></i> Like');
	    			// $("#"+no_likes_id).empty();
	    		}
	    	});

	    	// Store Post Listener
	    	Echo.channel(_this.schoolID + '-store-post-channel').listen('.' + _this.schoolID  + 'StorePostEvent', function (response) {
	    		// Add Post From The Array
	    		_this.classPosts.unshift(response.data);
	    	});

	    	// New Comment
	    	Echo.channel(_this.schoolID + '-create-comment-channel').listen('.' + _this.schoolID  + 'CreateCommentEvent', function (response) {

				// Usage:
				const class_posts = _this.classPosts;
				const post = class_posts.find(class_post => class_post.id == response.data.online_post_id);

				if(typeof post != 'undefined') {
					var postIndex = _this.classPosts.indexOf(post);
					_this.classPosts[postIndex].comments.push(response.data);
				}
	    	});

	    	// Delete Comment
	    	Echo.channel(_this.schoolID + '-delete-comment-channel').listen('.' + _this.schoolID  + 'DeleteCommentEvent', function (response) {

				// Usage:
				const class_posts 	= 	_this.classPosts;
				const post 			= 	class_posts.find(class_post => class_post.id == response.data.online_post_id);

				if(typeof post != 'undefined') {
					var postIndex 		= 	_this.classPosts.indexOf(post);
					const comment 		= 	class_posts[postIndex].comments.find(comment => comment.id == response.data.id);
					var commentIndex 	= 	_this.classPosts[postIndex].comments.indexOf(comment);

					_this.classPosts[postIndex].comments.splice(commentIndex, 1);
				}
				
	    	});

	    	// Delete Post Listener
	    	Echo.channel(_this.schoolID + '-delete-post-channel').listen('.' + _this.schoolID  + 'DeletePostEvent', function (response) {
	    		// Delete Post From The Array
	    		_this.classPosts 	= 	_this.classPosts.filter(item => {
									    	return item.id != response.data.id;
									    });
	    	});
	    }
 	};
</script>