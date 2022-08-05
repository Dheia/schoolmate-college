<template lang="html">
	
	<div>
		
		<h4 v-if="books.length > 0 && currentUrl.match('library')">Unreturned Books</h4>
		<br>	
		<table class="table table-striped" v-if="books.length > 0">			
			<thead>
				<tr>					
					<th v-if="currentUrl.match('librarian')">Borrower</th>
					<th>Date Borrowed</th>
					<th>Due Date</th>
					<th>Book Title</th>
					<th>Author</th>
					<th>Publisher</th>					
					<th v-if="currentUrl.match('librarian')">Series</th>
					<th v-if="currentUrl.match('librarian')">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="book in books" >
					
					<td v-if="currentUrl.match('librarian') && book.studentnumber != null"> {{ book.slastname }}, {{ book.sfirstname }}  {{ books.smiddlename }}</td>
					<td v-if="currentUrl.match('librarian') && book.employee_id != null"> {{ book.elastname }}, {{ book.efirstname }}  {{ books.emiddlename }}</td>
					<td>{{ book.date_borrowed }}</td>
					<td>{{ book.due_date }}</td>
					<td>{{ book.title }}</td>
					<td>{{ book.author }}</td>
					<td>{{ book.publisher }}</td>
					
					
					<td v-if="currentUrl.match('librarian')">{{ book.series }}</td>
					<td v-if="currentUrl.match('librarian')">
						<form v-on:click="releaseBook(book.id)">							
							<input type="submit" value="Return" class="form-control">
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
			axios.get(this.currentUrl + '-unreturn')
                    .then(response => {
                    	console.log(response);                  
                      this.books = response.data.books;  
            });			
		},
		releaseBook(bid){
			axios.post(this.currentUrl + '-return', {'name': bid,'action': "cancel"})
                    .then(response => {
                    	console.log(response);                       
            });            	
		}
	}
}
</script>


<style lang="css">

</style>
