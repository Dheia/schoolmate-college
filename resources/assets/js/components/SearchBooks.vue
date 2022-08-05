<template>

    <div class="container-fluid" style="padding: 0; margin: 0;">
    	<div class="d-flex justify-content-center row">
    		<div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
                <input v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control" id="search">
            </div>
            <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
                <a href="#" @click="searchNow()" class="btn btn-primary btn-block" style="border-radius: 0;">Search</a>
            </div>
            <h4 class="col-md-12 col-sm-12 text-center pt-5">SEARCH FOR BOOK</h4> 
            <i class="fa fa-book fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
    	</div>
            

        <div id="book-list" v-if="searchItems != null" class="col-md-12" style="padding: 0;">
            <!-- <tuition-fee></tuition-fee> -->
            <div>
	            <table class="table table-bordered table-striped" >
	                <thead style="background-color: rgb(66, 40, 108); color: rgb(255, 255, 255);">
	                  <th style="padding: 5px;">Accession No</th>
	                  <th style="padding: 5px;">Title</th>
	                  <th style="padding: 5px;">Code</th>
	                  <th style="padding: 5px;">Status</th>
	                </thead>
	                <tbody>
	                    <tr v-for="bookTransaction in searchItems">
	                        <td>{{ bookTransaction.accession_number }}</td>
	                        <td>{{ bookTransaction.title }}</td>
	                        <td>{{ bookTransaction.code }}</td>
	                        <td v-if="bookTransaction.is_available == 'Available'"><span class="badge badge-success">{{ bookTransaction.is_available }}</span></td>
	                        <td v-if="bookTransaction.is_available == 'Not Available'"><span class="badge badge-danger">{{ bookTransaction.is_available }}</span></td>
	                    </tr>
	                </tbody>
	            </table>
	            <nav aria-label="Page navigation example">
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

    </div>


</template>

<script>
    export default {
        data() {
            return {
                searchbar: null,
                searchItems: null,
                baseUrl: location.protocol + '//' + location.host,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
            }
        },
        methods: {

            requestPage(url) {
                 axios.get(url)
                    .then(response => {
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevPage    = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;
                    });
            },

            searchNow() {
                if(this.searchbar == null || this.searchbar === '') {
                    alert("Please enter a keyword");
                    return false;
                }

                axios.get('/library/get/books?search=' + this.searchbar)
                    .then(response => {
                        
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevePage   = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;

                        // this.getBooks();
                    });
            },
        },

        created() {
            console.log("Reading");    
            jQuery(function ($) {
                $('form').submit(false);
            }) 
        }
    };
</script>


