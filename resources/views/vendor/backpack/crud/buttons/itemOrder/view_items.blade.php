
	<button  href="{{ url($crud->route.'/#cartModal') }}"
		type="button"
		data-id="{{ $entry->getKey() }}"
		data-code="{{ $entry->getAttributeValue('code') }}"
		onclick = "openCart(this, {{$entry->getKey()}});"
		id="viewItemsButton" 
		name="viewItemsButton"
		class="btn btn-xs btn-default">
		<i class="fa fa-eye"></i> 
		View Items
	</button >


	<!-- Modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="cartModal">
	    <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		        <div class="modal-header">
		          	<h2 class="modal-title" id="cartModalTitle">Order Items</h2>
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		            	<span aria-hidden="true">&times;</span>
		          	</button>
		        </div>
		        <div class="modal-body" id="cartModalBody">
			        <table class="table table-striped table-image">
			        	<thead>
			              <tr>
			                <th scope="col">Item</th>
			                <th scope="col">Price</th>
			                <th scope="col">Quantity</th>
			              </tr>
			            </thead>
			            <tbody id="cartModalTBody">
			              
			            </tbody>
			        </table> 
			        <div class="pull-right">
			        	<h3>Total: <strong>&#8369<span class="price text" id="cartModalTotalPrice">0.00</span></strong></h3>
			        </div>
		        </div>
		        <div class="modal-footer">
		        </div>
		    </div>
	    </div>
	</div>

	<script type="text/javascript">
		function openCart(element, id){
			$("#cartModalTitle").html('Order #'+$(element).data('code'));
			$('#cartModalTBody').empty();
	          var cartTotalPrice = "";
	        $.ajax({
	            url: 'item-order/api/get/items?id='+id,
	            success : function(data){
	                $.each( data, function( key, value ) {
			            cartTotalPrice  = (Number(cartTotalPrice) + (Number(value.price) * Number(value.quantity)));
			            // var tdAction    = "<td>" +
			            //                     "<a href='#' onclick='removeCartItem("+key+")' class='btn btn-danger btn-sm'>" +
			            //                       " <i class='fa fa-times'></i>" +
			            //                     "</a>" +
			            //                   "</td>";
			            // var tdImage     = "<td class='w-25'>" +
			            //                     "<img src='https://i2.wp.com/www.foodrepublic.com/wp-content/uploads/2012/03/033_FR11785.jpg?resize=700%2C%20466&ssl=1' class='img-fluid img-thumbnail' alt='"+value.item_name+"'>" +
			            //                   "</td>"
			            var tdItemName  = '<td>' + 
			                                  value.item_name + 
			                              '</td>';
			            var tdItemPrice = '<td>' + 
			                                  value.price + 
			                              '</td>';
			            var tdItemQuantity  = '<td>' + 
			                                  value.quantity + 
			                              '</td>';
			            var row = "<tr id='cartItem-" + key + "'>" + 
			                        // tdImage     +
			                        tdItemName  +
			                        tdItemPrice +
			                        tdItemQuantity +
			                        // tdAction    +
			                      "</tr>";
			            $('#cartModalTBody').append(row);
			        });
			        cartTotalPrice  = cartTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2 });
	          		$('#cartModalTotalPrice').text(cartTotalPrice);
	            },error: function(data){
	                 console.log("The request failed");
	            },
	        })
	         $('#cartModal').modal('toggle');
	    }
	</script>

@section('after_scripts')

@endsection
