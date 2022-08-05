<div id="legal-guardian-information" class="row">
	<div class="form-group col-md-12">
		<span class="login100-form-title text-left" style="padding-bottom: 0; margin-top: 50px;">
			<small>Guardian's Information</small>
		</span>
	</div>	

	<div class="form-group col-md-4">
		<label for="legal_guardian_lastname">Last Name<span class="required">*</span></label>
		<input id="legal_guardian_lastname" type="text" value="{{ old('legal_guardian_lastname') }}" name="legal_guardian_lastname" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="legal_guardian_firstname">First Name<span class="required">*</span></label>
		<input id="legal_guardian_firstname"type="text" value="{{ old('legal_guardian_firstname') }}" name="legal_guardian_firstname" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="legal_guardian_middlename">Middle Name</label>
		<input id="legal_guardian_middlename" type="text" value="{{ old('legal_guardian_middlename') }}" name="legal_guardian_middlename" class="form-control"> 
	</div>
	
	<div class="form-group col-md-4">
		<label for="legal_guardian_citizenship">Citizenship</label>
		<input id="legal_guardian_citizenship" type="text" value="{{ old('legal_guardian_citizenship') }}" name="legal_guardian_citizenship" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="legal_guardian__occupation">Occupation<span class="required">*</span></label>
		<input id="legal_guardian__occupation" type="text" value="{{ old('legal_guardian_occupation') }}" name="legal_guardian_occupation" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<label for="legal_guardian_contact_number">Mobile No.<span class="required">*</span></label>
		<input id="legal_guardian_contact_number" type="text" value="{{ old('legal_guardian_contact_number') }}" name="legal_guardian_contact_number" class="form-control">
	</div>
</div>