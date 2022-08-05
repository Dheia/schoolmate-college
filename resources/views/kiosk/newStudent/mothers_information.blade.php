<div id="mother-information" class="row">
	<div class="form-group col-md-12">
		<span class="login100-form-title text-left" style="padding-bottom: 0; margin-top: 50px;">
			<small>Mother's Information</small>
		</span>
	</div>	

	<div class="form-group col-md-4">
		<label for="motherlastname">Last Name<span class="required">*</span></label>
		<input id="motherlastname" type="text" value="{{ old('motherlastname') }}" name="motherlastname" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="motherfirstname">First Name<span class="required">*</span></label>
		<input id="motherfirstname"type="text" value="{{ old('motherfirstname') }}" name="motherfirstname" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="mothermiddlename">Middle Name</label>
		<input id="mothermiddlename" type="text" value="{{ old('mothermiddlename') }}" name="mothermiddlename" class="form-control"> 
	</div>
	
	<div class="form-group col-md-4">
		<label for="motherCitizenship">Citizenship</label>
		<input id="motherCitizenship" type="text" value="{{ old('motherCitizenship') }}" name="motherCitizenship" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="mother_occupation">Occupation<span class="required">*</span></label>
		<input id="mother_occupation" type="text" value="{{ old('mother_occupation') }}" name="mother_occupation" class="form-control" required>
	</div>

	<div class="form-group col-md-4">
		<label for="mothernumber">Mobile No.</label>
		<input id="mothernumber" type="text" value="{{ old('mothernumber') }}" name="mothernumber" class="form-control">
	</div>

	<div class="form-group col-md-6" style="margin-bottom: 0;">
		<div class="form-check form-check-inline">
		  <input class="form-check-input" id="mother_deceased" type="checkbox" name="mother_living_deceased" value="deceased">
		  <label class="form-check-label" for="mother_deceased">
		    Deceased
		  </label>
		</div>
	</div>
</div>