@if ($crud->hasAccess('update'))
    <button class="btn btn-default" id="btnUpdateAccess" onclick="getUpdate()">Tag All RFID to In State</button>

    <div class="modal fade bd-example-modal-sm" id="updating_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Tag All RFID to In State</h5>
	        
	      </div>
	      <div class="modal-body">
	        <input type="text" name="rfid_scanner" id="rfid_scanner" class="form-control" autofocus="true" style="opacity: 0;" autocomplete="off">
	        <div class="progress">
	          <div class="progress-bar progress-bar-striped active" role="progressbar"
	          aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
	            Updating...
	          </div>
	        </div>
	      </div>
	      
	    </div>
	  </div>
	</div>
@endif


        <script>
            function getUpdate(){

				$.ajax({
					type: 'GET',
					url: "/admin/updateredisin",
					success:function(data){
		                  
		               }
				})
            }
			
				



			
           

        </script>



