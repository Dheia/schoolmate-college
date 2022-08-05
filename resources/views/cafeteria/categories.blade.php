<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Cafeteria | Westfields International</title>
      <!-- Styles -->
      <link href="{{ asset('css/bootstrap4-3-1.min.css') }}" rel="stylesheet">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Font Awesome Icon Kit -->
      <script src="https://kit.fontawesome.com/8e38ce13e4.js" crossorigin="anonymous"></script>
      <!-- Animate Css -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">

      <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- GIJGO FOR DATE PICKER -->
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
      <style>
        @media only screen and (max-width: 640px){
          .btn .btn-outline-secondary .border-left-0{
            height: 38px !important;
          }
        }
        .fab {
          z-index: 9999;
          position: fixed;
          bottom: 1rem;
          right: 1rem;
        }

        .fab_cat {
          z-index: 9999;
          position: fixed;
          top: 1rem;
          left: 1rem;
        }

        .fab:hover {
          animation-name: shake !important;
          animation-duration: 1s !important;
        }

        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            border-radius: 35px;
            font-size: 24px;
            line-height: 1.33;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;

        }

      </style>
     
  </head>
  <body class="skin-blue">
      <div id="app" class="container-fluid">
        <!-- Categories Floating Action Buttong Cart -->
        <!-- <button id="categoryButton" name="categoryButton" onclick="myFunction()" type="button" class="btn btn-primary btn-circle btn-xl fab_cat animated rotateIn">
          <i class="fa fa-list"></i>
        </button>  -->
        
        <!-- Floating Action Buttong Cart -->
        <button id="cartButton" name="btnCart" onclick="openCart()" type="button" class="btn btn-primary btn-circle btn-xl fab animated rotateIn">
          <i class="fa fa-shopping-cart"></i>
        </button>      
        <!-- Navigation -->
          <!-- Page Content -->
          <div class="container" id="sidebar">
              <nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(-135deg, #c850c0, #080e7a);">
                  <div class="container d-flex justify-content-center">
                      <a href="{{ url()->current() }}" >
                          <img height="50" src="{{ asset('images/WIS_LOGO.png') }}" class="d-flex justify-content-center" alt="IMG">
                          <center>  
                              <img width="50" height="50" src="{{ asset(Config::get('settings.schoollogo')) }}" alt="IMG" align="center">
                          </center>  
                      </a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" 
                              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                      </button>
                  </div>
              </nav>
          </div>
          <div class="container-fluid" style="padding-top: 110px;">
              <div class="row py-3">

                <div class="col-lg-2" id="myDIV">
                  <div class="sticky-top">
                    <div class="list-group">
                    <h1 class="my-4">Categories</h1>

                      @if(count($ItemCategories) > 0)
                        @foreach($ItemCategories as $key => $ItemCategory)
                          <a href="{{ asset('cafeteria/'.$ItemCategory->id.'/'.$ItemCategory->name) }}" class="list-group-item">{{ $ItemCategory->name }}</a>
                        @endforeach
                      @endif
                    </div>
                  </div>
                </div>
                <!-- /.col-lg-3 -->
                <!-- <canteen-item></canteen-item> -->
                <div class="col-lg-9 mx-auto">

                  <div class="row pt-4">
                    @if(count($ItemInventories) > 0)
                      @foreach($ItemInventories as $key => $ItemInventory)
                        <div class="col-lg-3 col-md-6 mb-4">
                          <div class="card h-100">
                              @if($ItemInventory->image)
                                <img class="card-img-top" style="height: 275px; object-fit: cover;" src="{{$ItemInventory->image}}" alt="">
                              @else
                                <img class="card-img-top" style="height: 275px; object-fit: cover;" src="{{asset('images/menu-default.png')}}" alt="">
                              @endif
                            <div class="card-body">
                              <h4 class="card-title">
                                <strong>{{ $ItemInventory->name }}</strong>
                              </h4>
                              <h5>{{ $ItemInventory->sale_price }}</h5>
                              <p class="card-text">{{ $ItemInventory->description }}</p>
                            </div>
                            <div class="card-footer">
                              <a href="{{ url()->current().'/#addToCartModal' }}" 
                                  data-toggle="modal" 
                                  data-id="{{ $ItemInventory->id }}"  
                                  data-name="{{ $ItemInventory->name }}"
                                  data-image="@if($ItemInventory->image){{ $ItemInventory->image }}@else {{ asset('images/menu-default.png') }} @endif"
                                  data-price="{{ $ItemInventory->sale_price }}"  
                                  data-quantity="1" 
                                  data-target="#addToCartModal" 
                                  class="btn btn-primary w-100 h-100">
                                <i class="fa fa-cart-plus fa-1x" aria-hidden="true"></i> 
                                Add to Cart
                              </a>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>

                </div> 
                <!-- /.col-lg-9 -->
              </div>
              <!-- /.row -->
          </div>
          <!-- /.container -->

          <!-- Main Footer -->
          <footer class="main-footer">
             <div class="col-md-12">
                <p class="text-center footer-message"><i class="fa fa-mobile-phone"></i> +63 917 510 0002</p>
                <p class="text-center footer-message"><i class="fa fa-address-book"></i>  Cutcut, Angeles City, Philippines</p>
                <p class="text-center footer-message">Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a></p>
              </div>
          </footer>

          <!-- Modal List of Cart Item Start-->
          <div class="modal fade" tabindex="-1" role="dialog" id="cartModal">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="cartModalTitle">Your Cart Items</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="cartModalBody">
                  <table class="table table-image">
                    <thead>
                      <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody id="cartModalTBody">
                      
                    </tbody>
                  </table> 
                 <!-- Total -->
                  <div class="d-flex justify-content-end">
                    <h5>Total: &#8369<span class="price text-success" id="cartModalTotalPrice">0.00</span></h5>
                  </div>
                </div>
                <div class="modal-footer">
                  <form id="formSubmit" name="formSubmit" action="{{url('cafeteria/submit-order')}}" method="POST">
                    @csrf
                    <button id="buttonSumitOrder" name="buttonSumitOrder" type="submit" class="btn btn-primary">Submit Order</button>
                  </form>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal List of Cart Item End -->

          <!-- Add To Cart Modal Start-->
          <div class="modal fade" tabindex="-1" role="dialog" id="addToCartModal">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addToCartModalTitle">Add to Cart</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="addToCartModalBody">
                  <input type="hidden" name="addToCartItemID" id="addToCartItemID">
                  <table class="table table-image">
                    <thead>
                      <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                      </tr>
                    </thead>
                    <tbody id="addToCartModalTBody">
                      <tr>
                        <td scope="col" id="addToCartImage" width="150px">Image</td>
                        <td scope="col" id="addToCartItem">Item</td>
                        <td scope="col" id="addToCartPrice">Price</td>
                        <td scope="col">
                          <input type="number" id="addToCartQuantity" width="20" value="1">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="d-flex justify-content-end">
                    <h5>Total: &#8369 <span class="price" id="addToCartTotalPrice"></span></h5>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="addItemToCart()">Add to Cart</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Add To Cart End -->

          <!-- Success Submit Modal Start-->
          <div class="modal" tabindex="-1" role="dialog" id="successModal">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                  <i style="color:black;" class="fas fa-check-circle fa-10x"></i>
                  <h2>Order Submitted</h2>
                </div>
              </div>
            </div>
          </div>
          <!-- Success Submit Modal End -->
      </div>
     

      <script src="{{ asset('js/jquery.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
      <!-- jQuery 2.1.3 -->
      <script src="{{ asset ('vendor/adminlte/dist/js/adminlte.min.js') }}" type="text/javascript"></script>
      <script type="text/javascript">
        function myFunction() {
          var x = document.getElementById("myDIV");
          if (x.style.display === "none") {
            x.style.display = "block";
          } else {
            x.style.display = "none";
          }
        }
        var cart = [];
        var cart_data = [];
        $(function () {
            if (localStorage.cart)
            {
              cart = JSON.parse(localStorage.cart);
              // console.log(cart);
            }
            $('#cartModal').on("show.bs.modal", function (e) {
              $('#addToCartModal').modal('hide');
              $('#loginModal').modal('hide');
            });
            $('#addToCartModal').on("show.bs.modal", function (e) {
              var quantity = $(e.relatedTarget).data('quantity');
              var price = $(e.relatedTarget).data('price');
              $("#addToCartItemID").val($(e.relatedTarget).data('id'));
              $("#addToCartItem").html($(e.relatedTarget).data('name'));
              var image = "<img src='"+ $(e.relatedTarget).data('image') +"' class='img-fluid img-thumbnail'>";
              $("#addToCartImage").html(image);
              $("#addToCartPrice").html($(e.relatedTarget).data('price'));
              $("#addToCartQuantity").val($(e.relatedTarget).data('quantity'));
              var addToCartTotalPrice  = (Number(quantity) * Number(price));
              $("#addToCartTotalPrice").html(addToCartTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2 }));
            });
        });
        // 
        $("#addToCartQuantity").keyup(function() {
            var price = $("#addToCartPrice").text();
            var quantity = $("#addToCartQuantity").val();
            var addToCartTotalPrice  = (Number(quantity) * Number(price));
            $("#addToCartTotalPrice").html(addToCartTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2 }));
        });
        function addItemToCart(){
          var id = $("#addToCartItemID").val();
          var name = $("#addToCartItem").text();
          var price = $("#addToCartPrice").text();
          var quantity = $("#addToCartQuantity").val();
          addToCart(id, name, price, quantity);
          $('#addToCartModal').modal('toggle');
        }
        function addToCart(id, name, sale_price, quantity){
          var length = cart.length;
          for (var i in cart) {
            if (cart[i].id == id) {
              cart[i].quantity = (Number(cart[i].quantity)+ Number(quantity)); // update the entry in the array
              saveCart();
              console.log('if');
              return
            }
          }
          cart.push({
            id: id,
            name: name,
            quantity: quantity,
            price: sale_price
          });
          saveCart();
        }

        function removeCartItem(index){
          var cartTotalPrice = "";
          $('#cartModalTotalPrice').text('0.00')
          cart.splice(index,1);
          saveCart();
          $('#cartModalTBody').empty();
          if(cart.length == 0){
            document.getElementById("buttonSumitOrder").disabled = true;
          }
          else{
            document.getElementById("buttonSumitOrder").disabled = false;
          }
          $.each( cart, function( key, value ) {
            cartTotalPrice  = (Number(cartTotalPrice) + (Number(value.price) * value.quantity));
            var tdAction    = "<td>" +
                                "<a href='#' onclick='removeCartItem("+key+")' class='btn btn-danger btn-sm'>" +
                                  " <i class='fa fa-times'></i>" +
                                "</a>" +
                              "</td>";
            var tdImage     = "<td class='w-25'>" +
                                "<img src='https://i2.wp.com/www.foodrepublic.com/wp-content/uploads/2012/03/033_FR11785.jpg?resize=700%2C%20466&ssl=1' class='img-fluid img-thumbnail' alt='"+value.name+"'>" +
                              "</td>";
            var tdItemName  = '<td>' + 
                                  value.name + 
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
                        tdAction    +
                      "</tr>";
            $('#cartModalTBody').append(row);
            cartTotalPrice  = cartTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2 });
            $('#cartModalTotalPrice').text(cartTotalPrice);
          });
        }

        function saveCart() {
          if (window.localStorage)
          {
            localStorage.setItem("cart",  JSON.stringify(cart));
          }
        }

        function openCart(){
          $('#cartModalTBody').empty();
          var cartTotalPrice = "";
          if(cart.length == 0){
            document.getElementById("buttonSumitOrder").disabled = true;
          }
          else{
            document.getElementById("buttonSumitOrder").disabled = false;
          }
          // console.log(cart);

          $.each( cart, function( key, value ) {
            cartTotalPrice  = (Number(cartTotalPrice) + (Number(value.price) * value.quantity));
            var tdAction    = "<td>" +
                                "<a href='#' onclick='removeCartItem("+key+")' class='btn btn-danger btn-sm'>" +
                                  " <i class='fa fa-times'></i>" +
                                "</a>" +
                              "</td>";
            var tdImage     = "<td class='w-25'>" +
                                "<img src='https://i2.wp.com/www.foodrepublic.com/wp-content/uploads/2012/03/033_FR11785.jpg?resize=700%2C%20466&ssl=1' class='img-fluid img-thumbnail' alt='"+value.name+"'>" +
                              "</td>"
            var tdItemName  = '<td>' + 
                                  value.name + 
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
                        tdAction    +
                      "</tr>";
            $('#cartModalTBody').append(row);
          });
          // console.log(cart_data);
          cartTotalPrice  = cartTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2 });
          $('#cartModalTotalPrice').text(cartTotalPrice);
          $('#cartModal').modal('toggle');
        }
        // function openPickupDate(){
        //   $('#cartModal').modal('hide');
        //   $('#pickupDateModal').modal('toggle');
        // }

        // function openLogin(){
        //   var a = $('#pickupDate').val();
        //   $('#cart_pickupDate').val(a);
        //   // $('#cartModal').modal('toggle');
        //   $('#pickupDateModal').modal('hide');
        //   $('#loginModal').modal('toggle');
        //   $('#cart_data').val(JSON.stringify(cart));
        // }
      </script> 

      <script type="text/javascript">
        @if (count($errors) > 0)
          @if($errors->has('cart_pickupDate'))
            $('#pickupDateModal').modal('show');
          @elseif ($errors->has('studentnumber') || $errors->has('password'))
            $('#loginModal').modal('show');
          @endif
        @endif
      </script>
      <script type="text/javascript">
        @if(session()->has('success'))
           $('#successModal').modal('show');
           localStorage.clear();
        @endif
        @if(session()->has('failed'))
           $('#loginModal').modal('show');
        @endif
      </script>
  </body>
</html>
