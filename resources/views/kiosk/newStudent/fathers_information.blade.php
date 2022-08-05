<div id="father-information" class="row">
	<div class="form-group col-md-12">
		<span class="login100-form-title text-left" style="padding-bottom: 0; margin-top: 30px;">
			<small>Father's Information</small>
		</span>
	</div>	

	<div class="form-group col-md-4">
		<label for="fatherlastname">Last Name<span class="required">*</span></label>
		<input id="fatherlastname" type="text" value="{{ old('fatherlastname') }}" name="fatherlastname" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="fatherfirstname">First Name<span class="required">*</span></label>
		<input id="fatherfirstname"type="text" value="{{ old('fatherfirstname') }}" name="fatherfirstname" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="fathermiddlename">Middle Name</label>
		<input id="fathermiddlename" type="text" value="{{ old('fathermiddlename') }}" name="fathermiddlename" class="form-control"> 
	</div>
	
	<div class="form-group col-md-4">
		<label for="fatherCitizenship">Citizenship</label>
		<input id="fatherCitizenship" type="text" value="{{ old('fatherCitizenship') }}" name="fatherCitizenship" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="father_occupation">Occupation<span class="required">*</span></label>
		<input id="father_occupation" type="text" value="{{ old('father_occupation') }}" name="father_occupation" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="fatherMobileNumber">Mobile No.</label>
		<input id="fatherMobileNumber" type="text" value="{{ old('fatherMobileNumber') }}" name="fatherMobileNumber" class="form-control">
	</div>

	<div class="form-group col-md-6" style="margin-bottom: 0;">
		<div class="form-check form-check-inline">
		  <input class="form-check-input" id="father_deceased" type="checkbox" name="father_living_deceased" value="deceased">
		  <label class="form-check-label" for="father_deceased">
		    Deceased
		  </label>
		</div>
	</div>
</div>