<template>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-lg-12 oc">
            <!-- Class Term Filter -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" style="padding-left: 0; padding-right: 0;">
                <div class="form-group">
                    <select class="form-control" id="term" v-model="term" @change="filterClass()">
                        <option value="" selected="true">All Classes</option>
                        <option v-for="(type, term_index) in termTypes" :value="type">
                            {{ type }} Term
                        </option>
                    </select>
                </div>
            </div>
            <!-- Searchbar -->
            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 float-right" style="padding: 0; margin: 0">
                <a href="#" @click="searchClass(searchbar)" class="btn btn-primary btn-block form-control smo-search"><i class="fa fa-search"></i></a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10 float-right" style="padding: 0; margin: 0">
                <input v-model="searchbar" @keyup.enter="searchClass(searchbar)" type="text" class="form-control smo-searchbox" id="search" placeholder="Search subject">
            </div>
        </div>



        <!-- My Classes -->
        <div class="col-md-12 col-lg-12 oc" style="padding-left: 0; padding-right: 0;">
            <!-- Loading GIF -->
            <div v-if="isLoading">
                <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
            </div>
            <!-- Searching GIF / Magnifying Glass GIF -->
            <div v-else-if="isSearching && !isLoading">
                <img class="img-responsive" v-bind:src="'/images/magnify-glass-200px.gif'" alt="Searching..." style="margin: auto;">
            </div>
            <div v-if="!isSearching && !isLoading">
                <div v-if="totalClass > 0">
                    <div v-for="(my_class, index) in searchItems" :set="class_index = 2">
                        <div v-bind:class="[index%2 ? 0 : '', '']">
                            <div class="col-xs-12 col-md-6 col-lg-6" :set="index = index+1">

                                <div class="box shadow class-info">
                                    <div class="box-body with-border">
                                        <span class="dot" :style="'position: absolute; z-index: 999; height: 65px; background-color:'+ my_class.color +';'"></span>
                                        <div class="row">
                                            <div class="">
                                                <!-- Right Circle -->
                                                <span v-if="my_class.ongoing" class="dot" :style="'right: 10px; width: 10px; height: 10px; background-color: #1cc88a;'"></span>
                                                <span v-else="my_class.ongoing" class="dot" :style="'right: 10px; width: 10px; height: 10px; background-color: #e1e1e1;'"></span>
                                                <!-- Subject Code and Class Code -->
                                                <h6 class = "class-desc"> 
                                                    <b>{{ my_class.subject_code }} | {{ my_class.term_type + ' Term' }} - <span>{{my_class.code}}</span></b>
                                                </h6>

                                                <!-- Class Subject Title -->
                                                <a v-bind:href="'/student/online-post?class_code=' + my_class.code">

                                                    <h4 class="class-header"> 
                                                        {{ my_class.subject_name }}
                                                        {{ my_class.summer ? '(Summer)' : '' }}
                                                    </h4>
                                                </a>

                                                <!-- Class Teacher -->
                                                <h6 class = "class-desc">
                                                    {{ my_class.teacher_fullname }} 
                                                </h6>

                                                <!-- Grade and Section -->
                                                <h6 class = "class-desc">
                                                    {{ my_class.level_name }} {{ my_class.track_name != '-' ? '| ' + my_class.track_name : '' }}
                                                </h6>

                                                <!-- Video Conference Status -->                             
                                                <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px;  margin-bottom: 0px;" v-if="my_class.ongoing">
                                                    <span class="badge label-success smo-vc" id="video_conference">
                                                        <i class="fa fa-video-camera"></i> Class is On-going 
                                                    </span>
                                                </h5>
                                                <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px;  margin-bottom: 0px;" v-else>
                                                    <span class="badge label-default smo-vc" id="video_conference">
                                                        <i class="fa fa-video-camera"></i> No On-going Class  
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                         
                    </div>
                    <!-- Pagination -->
                    <div class="col-xs-12 col-md-12 col-lg-12" v-if="prevPage !== null || nextPage !== null">
                        <nav aria-label="Page navigation example" style="text-align: center;">
                            <ul class="pagination justify-content-center m-b-0 m-t-0">
                                <li class="page-item">
                                    <a href="javascript:void(0)" class="page-link" @click="requestPage(prevPage)" v-if="prevPage !== null">Previous</a>
                                </li>
                                <li class="page-item">
                                    <a href="javascript:void(0)" class="page-link" @click="requestPage(nextPage)" v-if="nextPage !== null">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div v-if="!totalClass > 0 || currentPage > lastPage" class="col-xs-12 col-md-12 col-lg-12">
                    <h3 class="text-center">No Class Found.</h3>
                </div>
            </div>
            
        </div>
    </div>


</template>

<script>
    import _ from 'lodash';

    export default {
        props: ['term_types'],
        data() {
            return {
                user: null,
                searchbar: '',
                isTyping: false,
                isLoading: true,
                isSearching: true,
                searchItems: [],
                baseUrl: location.protocol + '//' + location.host,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
                searchQuery: '',
                term: '',
                totalClass: 0,
                termTypes: JSON.parse(this.term_types)
            }
        },
        methods: {
            // Pagination Functi0on ( Next Page and Previous Page)
            requestPage(url) {
                this.isLoading   = true;
                axios.get(url)
                    .then(response => {
                        this.isLoading   = false;
                        this.searchItems = response.data.classes.data;
                        if(response.data.classes.next_page_url){    
                            this.nextPage    = response.data.classes.next_page_url+ '&term=' + this.term + '&search=' + this.searchQuery;
                        }
                        else{
                            this.nextPage    = response.data.classes.next_page_url;
                        }
                        if(response.data.classes.prev_page_url){    
                            this.prevPage   = response.data.classes.prev_page_url+ '&term=' + this.term + '&search=' + this.searchQuery;
                        }
                        else{
                            this.prevPage   = response.data.classes.prev_page_url;
                        }
                        this.currentPage = response.data.classes.current_page;
                        this.lastPage    = response.data.classes.data.last_page;

                        this.user          = response.data.user;
                    });
            },
            // Search Class Function
            searchClass: async function(searchQuery) {
                this.searchQuery = searchQuery;
                this.isSearching = true;       
                await axios.get('/student/online-class/api/get/classes?term='+this.term+'&search=' + this.searchQuery)
                    .then(response => {
                        this.isSearching = false;
                        this.isLoading   = false;
                        this.searchItems = response.data.classes.data;
                        if(response.data.classes.next_page_url){    
                            this.nextPage    = response.data.classes.next_page_url+ '&term=' + this.term + '&search=' + this.searchQuery;
                        }
                        else{
                            this.nextPage    = response.data.classes.next_page_url;
                        }
                        if(response.data.classes.prev_page_url){    
                            this.prevPage   = response.data.classes.prev_page_url+ '&term=' + this.term + '&search=' + this.searchQuery;
                        }
                        else{
                            this.prevPage   = response.data.classes.prev_page_url;
                        }
                        this.currentPage   = response.data.classes.current_page;
                        this.lastPage      = response.data.classes.data.last_page;
                        this.totalClass    = response.data.classes.total;
                    });
                    // $('.dropdown-toggle').dropdown();
            },
            // Filter Class ( All Classes / Onm-going Classes)
            filterClass() {
                this.isLoading   = true;
                this.searchClass(this.searchbar);
            },
         },

        created() {
            // console.log("Reading");
            this.searchClass(this.searchbar);
        },
        components: {

        }
    };
</script>


