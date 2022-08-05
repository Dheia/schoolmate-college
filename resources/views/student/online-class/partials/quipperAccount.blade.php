@if($class->link_to_quipper)
  <!-- Start Quipper Account -->
  <div class="box shadow">
    <div class="box-body with-border" style="padding: 20px !important;">

      <!-- Quipper Logo -->
      <h4 style="padding: 0px 15px 0px 15px; margin: 0px !important; text-align: center;">
         <img src="{{asset('images/quipper.png')}}" style="width: 100px;">
      </h4>

      <span><h5 style="padding-left:15px;"><b>My Account</b></h5></span>

      <!-- Quipper Membership No. -->
      <h6 style=" display:block; margin:0;padding:0px 15px ;">
        <span style="width: 40%;display: inline-block;">Membership No.:</span> 
        <span> {{ $quipperAccount ? $quipperAccount->membership_number : '-' }} </span>
      </h6>

      <!-- Quipper Username -->
      <h6 style=" display:block; margin:0;padding:0px 15px ;">
        <span style="width: 40%;display: inline-block;">Username:</span> 
        <span > {{ $quipperAccount ? $quipperAccount->username : '-' }}</span>
      </h6>

      <!-- Quipper Default Password -->
      <h6 style=" display:block; margin:0;padding:0px 15px ;">
        <span style="width: 40%;display: inline-block;">Default Password:</span> 
        <span> {{ $quipperAccount ? $quipperAccount->password : '-' }}</span>
      </h6>
      
      <!-- Proceed to Quipper -->
      <h4 style="padding: 10px 15px 0px 15px; margin: 0px !important">
        <a class="btn btn-primary btn-block" href="https://learn.quipper.com/en/login" target="_blank">
          <i class="fa fa-video"></i> 
          Proceed to Quipper
        </a>
      </h4>
      
    </div>
  </div>
  <!-- END Quipper Account -->
@endif