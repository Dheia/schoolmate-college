<div id="emergency-contact-information" class="row">
	<div class="form-group col-md-12">
		<span class="login100-form-title text-left" style="padding-bottom: 0; margin-top: 50px;">
			<small>Emergency Contact Information</small>
		</span>
	</div>	
	
	<div class="form-group col-md-4">
		<label for="emergencyRelationshipToChild">Relationship To Child<span class="required">*</span></label>
		<select name="emergencyRelationshipToChild" class="form-control" id="emergency-relationship-to-child">
			<option value="Father" {{ (collect(old('emergencyRelationshipToChild'))->contains('Father')) ? 'selected':'' }}>
				Father
			</option>
			<option value="Mother" {{ (collect(old('emergencyRelationshipToChild'))->contains('Mother')) ? 'selected':'' }}>
				Mother
			</option>
			<option value="LegalGuardian" {{ (collect(old('emergencyRelationshipToChild'))->contains('LegalGuardian')) ? 'selected':'' }}>
				Guardian
			</option>
			<option value="Other" {{ (collect(old('emergencyRelationshipToChild'))->contains('Other')) ? 'selected':'' }}>
				Other
			</option>
		</select>
	</div>
	
	<div class="form-group col-md-6 emergency-contact">
		<label for="">&nbsp;</label>
		<input type="text" value="{{ old('emergency_contact_other_relation_ship_to_child') }}" name="emergency_contact_other_relation_ship_to_child" class="form-control" 
			placeholder="Please Specify (Father, Mother, etc.)" >
	</div>
	
	<div class="form-group col-md-4 emergency-contact">
		<label for="emergency_lastname">Lastname<span class="required">*</span></label>
		<input id="emergency_lastname" type="text" value="{{ old('emergency_lastname') }}" name="emergency_lastname" class="form-control">
	</div>

	<div class="form-group col-md-4 emergency-contact">
		<label for="emergency_firstname">Firstname<span class="required">*</span></label>
		<input id="emergency_firstname"type="text" value="{{ old('emergency_firstname') }}" name="emergency_firstname" class="form-control">
	</div>

	<div class="form-group col-md-4 emergency-contact">
		<label for="emergency_middlename">Middlename</label>
		<input id="emergency_middlename" type="text" value="{{ old('emergency_middlename') }}" name="emergency_middlename" class="form-control"> 
	</div>
	
	
	<div class="form-group col-md-4">
		<label for="emergencyaddress">Address<span class="required">*</span></label>
		<input id="emergencyaddress" type="text" value="{{ old('emergencyaddress') }}" name="emergencyaddress" class="form-control">
	</div>

	<div class="form-group col-md-4 emergency-contact">
		<label for="emergencymobilenumber">Mobile No.</label>
		<input id="emergencymobilenumber" type="text" value="{{ old('emergencymobilenumber') }}" name="emergencymobilenumber" class="form-control">
	</div>

	<div class="form-group col-md-4">
		<div class="form-group">
			<label for="emergencyhomephone">Home Mobile No.<span class="required">*</span></label>
			<input type="text" value="{{ old('emergencyhomephone') }}" name="emergencyhomephone" class="form-control">
		</div>
	</div>
</div>

<!-- STUDENT LIVING WITH -->

{{-- <div class="row" style="margin-top: 50px;">
	<div class="form-group col-md-12">
		<label for="guardian">The student will be living in the Philippines with (pls. check all that apply) <span class="required">*</span> </label>
		
		<div class="row" style="margin: 0;">
			<div class="form-check col-md-4">
			  <input class="form-check-input" id="legal-guardian-father" type="checkbox" name="living[]" value="father" {{ (is_array(old('living')) && in_array('father', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-father">
			    Father
			  </label>
			</div>
			<div class="form-check col-md-4">
			  <input class="form-check-input" id="legal-guardian-mother" type="checkbox" name="living[]" value="mother" {{ (is_array(old('living')) && in_array('mother', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-mother">
			    Mother
			  </label>
			</div>
			<div class="form-check col-md-4">
			  <input class="form-check-input" id="legal-guardian-step-father" type="checkbox" name="living[]" value="step-father" {{ (is_array(old('living')) && in_array('step-father', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-step-father">
			    Step-Father
			  </label>
			</div>
			<div class="form-check col-md-4">
			  <input class="form-check-input"  id="legal-guardian-step-mother" type="checkbox" name="living[]" value="step-mother" {{ (is_array(old('living')) && in_array('step-mother', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-step-mother">
			    Step-Mother
			  </label>
			</div>
			<div class="form-check col-md-4">
			  <input class="form-check-input" id="legal-guardian-legal-guardian" type="checkbox" name="living[]" value="emergency" {{ (is_array(old('living')) && in_array('emergency', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-legal-guardian">
			    Legal Guardian
			  </label>
			</div>
			<div class="form-check col-md-4">
			  <input class="form-check-input" id="legal-guardian-other-relative" type="checkbox" name="living[]" value="other-relative" {{ (is_array(old('living')) && in_array('other-relative', old('living'))) ? ' checked' : '' }}>
			  <label class="form-check-label" for="legal-guardian-other-relative">
			    Other Relative
			  </label>

			  &nbsp;&nbsp;<input type="text" class="form-control" name="other_relative" value="{{ old('other_relative') }}" name="other_relative" id="other-relative" style="{{ (is_array(old('living')) && in_array('other-relative', old('living'))) ? 'visibility: visible' : 'visibility: hidden' }}" placeholder="Other Relative" />
			</div>	
		</div>
	</div>
</div> --}}