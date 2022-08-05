<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Library | Westfields International</title>
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
     
  </head>
  <body class="skin-blue">
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
    <div id="app" class="container-fluid" style="padding-top: 150px;">
      <div class="container-fluid">
        <search-books></search-books>
      </div>
       
      <!-- <div>
        <form class="container-fluid pb-5" method="POST" action="{{url('library/search')}}" enctype="multipart/form-data">
          <div class="d-flex justify-content-center row">
            @csrf
            <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
                <input type="text" class="form-control" id="bookSearchbar" placeholder="Accession No.">
            </div>
            <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
                <button href="#" class="btn btn-primary btn-block" style="border-radius: 0;" type="submit">Search</button>
            </div>
            <div id="book_icon" hidden="true">
              <h4 class="col-md-12 col-sm-12 text-center pt-5">SEARCH FOR BOOK</h4> 
              <i class="fa fa-book fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
            </div>
          </div>
        </form>
      </div> -->

      <!-- table class="table table-striped" id="tableBooks" hidden="true">
        <thead style="background-color: rgb(66, 40, 108); color: rgb(255, 255, 255);">
          <tr>
            <th scope="col">Accession No.</th>
            <th scope="col">Title</th>
            <th scope="col">Code</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Code Araling Panlipunan Araling Panlipunan</td>
            <td>Mark</td>
            <td>@mdo</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Code</td>
            <td>Jacob</td>
            <td>@fat</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Code</td>
            <td>Larry</td>
            <td>@twitter</td>
          </tr>
        </tbody>
      </table> -->
      <!-- Main Footer -->
      <footer class="main-footer">
         <div class="col-md-12">
            <p class="text-center footer-message">
              <i class="fa fa-mobile-phone"></i> +63 917 510 0002
              <br>
              <i class="fa fa-address-book"></i>  Cutcut, Angeles City, Philippines 
              <br>
              Handcrafted by: <a href="https://tigernethost.com">Tigernet Hosting and IT Services</a>
            </p>
          </div>
      </footer>
    </div>
     

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
    <!-- jQuery 2.1.3 -->
    <script src="{{ asset ('vendor/adminlte/dist/js/adminlte.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset ('js/app.js') }}" charset="utf-8"></script>

    <!-- <script type="text/javascript">
      $(function () {
        document.getElementById("book_icon").hidden = false;
        document.getElementById("tableBooks").hidden = false;
      });

    </script> -->
     
  </body>
</html>
