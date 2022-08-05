<template lang="html">
	
	<div>
		<h1 v-if="currentUrl.match('library')">My Books</h1>
		<br>
		<h4 v-if="books.length > 0 && currentUrl.match('library')">Reserved Books</h4>
		<br>	
		<table class="table table-striped" v-if="books.length > 0">			
			<thead>
				<tr>
					
					<th v-if="currentUrl.match('librarian')">Reservee</th>
					<th>Reservation Date</th>
					<th>Book Title</th>
					<th>Author</th>
					<th>Publisher</th>
					
					
					<th v-if="currentUrl.match('librarian')">Series</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="book in books" >
					
					<td v-if="currentUrl.match('librarian') && book.student_id != null"> {{ book.slastname }}, {{ book.sfirstname }}  {{ books.smiddlename }}</td>
					<td v-if="currentUrl.match('librarian') && book.employee_id != null"> {{ book.elastname }}, {{ book.efirstname }}  {{ books.emiddlename }}</td>
					<td>{{ book.date_reserved }}</td>
					<td>{{ book.title }}</td>
					<td>{{ book.author }}</td>
					<td>{{ book.publisher }}</td>
					
					
					<td v-if="currentUrl.match('librarian')">{{ book.series }}</td>
					<td >
						<form v-on:click="cancelReservation(book.id)" v-if="currentUrl.match('library')">							
							<input type="submit" value="Cancel" class="form-control">
							
						</form>
						<form v-on:click="releaseBook(book.id)" v-else="currentUrl.match('library')">							
							<input type="submit" value="Release" class="form-control">
						</form>
					</td>
										
				</tr>
			</tbody>
		</table>
	</div>

</template>

<script>	
export default {

	data(){
		return{
			books: [],
			currentUrl: window.location.pathname		
			
		}
	},
	created(){
		this.fetchBooks();
	},	
	methods:{
		fetchBooks(){
			axios.get(this.currentUrl + '-mybooks')
                    .then(response => {
                    	console.log(response);                  
                      this.books = response.data.books;  
            });			
		},
		cancelReservation(bid){
			axios.post(this.currentUrl + '-transaction', {'name': bid,'action': "cancel"})
                    .then(response => {
                    	console.log(response);                       
            });            	
		},
		releaseBook(bid){
			axios.post(this.currentUrl + '-release', {'name': bid,'action': "cancel"})
                    .then(response => {
                    	console.log(response);                       
            });            	
		}
	}
}
</script>


<style lang="css">

</style>
