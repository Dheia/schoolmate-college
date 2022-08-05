<div class="container-fluid social-links p-0 px-lg-2" style="background-color: #53197d;">
  <div class="container d-flex flex-wrap justify-content-between py-2" style="position: relative;">
    <div class="col p-0">
      <div class="contact-info d-flex mt-2">
        <p class="mb-0">
          <small>
            <a href="mailto:apag.dx.am@gmail.com" class="nav-link">
              <i class="fa fa-envelope "></i>&nbsp; 
              <span class="d-none d-md-inline">registrar@westfields.edu.ph</span>
            </a>
          </small>
        </p>

        <p class="mb-0">
          <small>
            <a href="javascript:void(0)" class="nav-link">
              <i class="fa fa-mobile "></i>&nbsp; 
              <span class="d-none d-md-inline">+639123456789</span>
            </a>
          </small>
        </p>

      </div>
    </div>

    <div class="col p-0">
      <input class="form-control col-md-6 ml-auto mr-5 mt-1 rounded-0 form-control-sm" type="text" placeholder="Search" style="background-color: #eee0f9; cursor: text;">
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light navbar-top nav bg-light py-0 p-lg-3 px-0 pl-1">
  <div class="container r-pad">
      <a class="navbar-brand logo-top" href="#">
        <img src="{{ asset('images/logo.png') }}" alt="">
      </a>
      <button class="navbar-toggler rounded-0 custom-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-chevron-right fa-1x"></i>
      </button>
      
      {{-- <div class="navbar-overlay"> --}}
          <div class="collapse pl-md-5 navbar-collapse" id="navbarHeader">

            <ul class="navbar-nav transformative mr-auto pl-3">
              <button class="navbar-toggler rounded-0 border-0 mt-3 mb-3" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fas fa-times float-left"></i>
              </button>

              <li class="nav-item">
                <a class="nav-link" href="#">Students</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Parents</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Faculty and Staff</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Alumni</a>
              </li>
            </ul>
      
          </div>
      {{-- </div> --}}
  </div>
</nav>

<div class="container-fluid p-0 px-lg-2">
  <div class="container r-pad">
        <nav class="navbar navbar-expand-lg nav-header2 box-shadow-lg px-md-0 px-sm-0">

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader2" aria-controls="navbarHeader2" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars "></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarHeader2">
            <ul class="navbar-nav px-sm-4 mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Academics
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="#">Cambridge</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="#">News and Events</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="#">Contact Us</a>
              </li>
{{--               <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
              </li> --}}
            </ul>
          </div>
        </nav>
  </div>

  {{-- <button class="btn btn-desktop-view d-none d-lg-block" style="background-color: transparent; position: absolute; right: 10%; z-index: 99; bottom: 10%;"><i class="fas text-white fa-2x fa-search"></i></button>
  <div class="search-bar col-md-3 p-2 shadow" style="position: absolute; bottom: -54px; right: 6.8%; z-index: 999; background-color: #F6BB3D;">
        <div class="input-group">
          
          <input type="text" class="form-control rounded-0" id="validationTooltipUsername" placeholder="Search" aria-describedby="validationTooltipUsernamePrepend" required>
          <div class="input-group-prepend">
            <span class="input-group-text" id="validationTooltipUsernamePrepend">Search</span>
          </div>
        </div>
  </div> --}}
</div>

<div class="diagonal-shape d-none d-lg-block">
</div>
