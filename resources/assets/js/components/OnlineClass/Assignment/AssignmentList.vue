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
			<div v-for="(assignment, index) in classAssignments">
				<!-- Post Box -->
				<div class="box shadow" :id="'post'+post.id">
					<!-- Post Box Body -->
					<div class="box-body with-border" style="padding: 20px 20px 0px 20px !important;">

						<div class="row posted_by">
			                <div class="col-xs-12 col-md-12 col-lg-12 class-post text-center">
								<!-- Posted By Image -->
			                	<a class="thumbnail" style="border-radius: 50%; overflow: hidden; padding: 0px; margin-left: auto; margin-right: auto;">
			                    	<img :src="'/'+post.poster_photo" alt="...">
			                  	</a>
			                	<!-- Posted By Name -->
			                  	<h4 class="post_by" style="">
			                    	<strong>{{ post.poster_name }}</strong>
			                 	</h4>
			                 	<!-- Class Name -->
			                 	<h4 class="post_by" style="font-size: 14px;">
			                    	<a v-bind:href="'teacher-online-post?class_code=' + post.class_code">{{ post.class.name }}</a>
			                 	</h4>
			                 	<!-- Date / Time Posted -->
			                  	<h5 class="post_at">
			                    	{{ moment(String(post.created_at)).format('MMMM DD, YYYY - h:mm a') }}
			                  	</h5>
			                </div>
		              	</div>

		              	<!-- Post Cotent -->
		              	<h4 class="post_desc ckeditor text-center pb-0" v-html="post.content"></h4>
		              	<!-- Post Files -->
		              	<div class="row" v-if="post.files.length > 0">
                			<div class="col-xs-12 col-md-12 col-lg-12 text-center">
                				<div v-for="(file, fileIndex) in post.files_with_extension">
                					<!-- Image -->
                					<div v-if="file['extension'] == 'img'">
                						<img :src="'/'+file['filepath']" style="max-width: 100%; max-height: 300px; padding-left: 50px; padding-right: 50px;">
                					</div>
                					<!-- File -->
                					<div v-else>
                						<a style="margin-left: auto; margin-right: auto;" target="_blank" :href="'/'+file['filepath']" :download="'/'+file['filepath']">
                							<i v-if="file['extension'] == 'pdf'" class="fa fa-file-pdf-o"></i>
                							<i v-else-if="file['extension'] == 'docx'" class="fa fa-file-word-o"></i>
                							<i v-else-if="file['extension'] == 'xlsx'" class="fa fa-file-excel-o"></i>
                							<i v-else-if="file['extension'] == 'pptx'" class="fa fa-file-powerpoint-o"></i>
                							<i v-else class="fa fa-file"></i>
                							{{ file['filename'] }}
                						</a>
                					</div>
                					<br>
                				</div>
                			</div>
                		</div>
					</div>
					<!-- Body Footer -->
					<div class="box-footer class-status" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
						

				    </div>
				</div>

			</div>
			<!-- All Posts Loaded -->
			<div v-if="nextPage == null && !isLoadingMore" class="text-center">
				<h5>All posts is loaded.</h5>
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
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" v-if="!totalPosts > 0" style="padding-top: 20px;">
			<img class="img-responsive" v-bind:src="'/images/icons/explore.png'" alt="Loading..." style="margin: auto; height: 130px;">
    		<h3 class="text-center">No Posts Yet.</h3>
        </div>
        <!-- End Of No Post Found -->
	</div>

</template>

<script>
 	//import UserBookTransactions from './UserBookTransactions.vue';
 	import _ from 'lodash';
 	import moment from 'moment'

 	export default {
 		scrollToTop: false,
	 	props: ['user', 'school_id'],
	    data() {
	        return {
	        	moment: moment,
	        	schoolID: this.school_id,
	        	employee: this.user.employee,
	        	employee_id: this.user.employee_id,
             	searchbar: '',
             	isLoading: true,
             	classAssignments: [],
             	baseUrl: location.protocol + '//' + location.host,
             	nextPage: null,
             	prevPage: null,
             	currentPage: null,
             	lastPage: null,
             	totalPosts: 0,
             	errored: false,
             	isLoadingMore: false
	        }
	    },
	    methods: {
	    	// Get Posts
	    	getPosts: async function(class_code) {
             	this.isLoading = true;    

        	 	await axios.get('/admin/teacher-online-post/api/get/class-posts')
                 	.then(response => {
                     	this.classAssignments = response.data.posts.data;
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
                     	this.currentPage   		= response.data.posts.current_page;
                     	this.lastPage      		= response.data.posts.last_page;
                     	this.totalAssignments	= response.data.posts.total;
                     	this.isLoading   		= false;
                     	this.errored 			= false;
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
	                	$.each(response.data.posts.data, function(key, value) {
                                _this.classAssignments.push(value);
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
                     	this.totalAssignments   = response.data.posts.total;
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

	    },

	    created() {
	    	// console.log("Reading - " + this.code)
	    	this.isLoading = true;
	        this.getAssignments();
	    },
	    components: {

	    },
	    mounted() {
	    	
	    }
 	};
</script>