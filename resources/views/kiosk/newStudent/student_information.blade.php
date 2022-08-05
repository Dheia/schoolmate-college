<div id="student-information" class="row">

	<div class="form-group col-md-12">
		<span class="login100-form-title text-left" style="padding-bottom: 0; margin-top: 30px;">
			<small>Student Information</small>
		</span>
	</div>

	{{-- <div class="form-group col-md-12">
		<label for="lrn">LRN</label>
		<input type="text" id="lrn" name="lrn" class="form-control" placeholder="You can leave this form if you do not have LRN">
	</div> --}}

	

	<div class="form-group col-md-3">
		<label for="lastname">Last Name<span class="required">*</span></label>
		<input id="lastname" type="text" value="{{ old('lastname') }}" name="lastname" class="form-control" required>
	</div>

	<div class="form-group col-md-3">
		<label for="firstname">First Name<span class="required">*</span></label>
		<input id="firstname"type="text" value="{{ old('firstname') }}" name="firstname" class="form-control" required>
	</div>

	<div class="form-group col-md-3">
		<label for="middlename">Middle Name</label>
		<input id="middlename" type="text" value="{{ old('middlename') }}" name="middlename" class="form-control"> 
	</div>


	<div class="form-group col-md-3">
		<label for="gender">Gender<span class="required">*</span></label>
		<select name="gender" id="gender" class="form-control" required>
			<option value="Male">Male</option>
			<option value="Female">Female</option>
		</select>
	</div>
	
	<div class="form-group col-md-3">
		<label for="citizenship">Citizenship<span class="required">*</span></label>
		<input id="citizenship" type="text" value="{{ old('citizenship') }}" name="citizenship" class="form-control" required>
	</div>

	<div class="form-group col-md-3">
		<label for="religion">Religion<span class="required">*</span></label>
		<input id="religion" type="text" value="{{ old('religion') }}" name="religion" class="form-control" required>
	</div>

	<div class="form-group col-md-3">
		<label for="birthdate">Date of Birth<span class="required">*</span></label>
		<input id="birthdate" type="date" value="{{ old('birthdate') }}" name="birthdate" class="form-control" required>
	</div>

	<div class="form-group col-md-3">
		<label for="birthplace">Place Of Birth<span class="required">*</span></label>
		<input id="birthplace" type="text" value="{{ old('birthplace') }}" name="birthplace" class="form-control" required>
	</div>


	<input id="age" type="hidden" value="{{ old('age') }}" name="age" class="form-control" placeholder="Automatically Computed" disabled readonly>



	<div  class="form-group col-md-12" style="margin-bottom: 0;">
		<label for="residentialaddress">Residential Address<span class="required">*</span></label>
	</div>

	<div class="form-group col-md-3">
		{{-- <input id="province" type="text" value="{{ old('province') }}" name="province" placeholder="Province" class="form-control" required> --}}
		<select name="province" placeholder="Province" id="province" class="form-control" tabindex="-1" title="">
			<option selected disabled>Please Select Province</option>
		</select>
	</div>

	<div class="form-group col-md-3">
		{{-- <input id="city_municipality" type="text" value="{{ old('city_municipality') }}" name="city_municipality" placeholder="City/Municipality" class="form-control" required> --}}
		<select name="city_municipality" placeholder="City/Municipality" id="city_municipality" class="form-control" tabindex="-1" title="">
			<option selected disabled>Please Select City/Municipality</option>
		</select>
	</div>
	
	<div class="form-group col-md-3">
		{{-- <input id="barangay" type="text" value="{{ old('barangay') }}" name="barangay" placeholder="Barangay." class="form-control" required> --}}
		<select name="barangay" placeholder="Barangay" id="barangay" class="form-control" tabindex="-1" title="">
			<option selected disabled>Please Select Barangay</option>
		</select>
	</div>

	<div class="form-group col-md-3">
		<input id="street_number" type="text" value="{{ old('street_number') }}" name="street_number" placeholder="Street No." class="form-control" required>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="contactnumber">Student Contact Number</label>
			<input type="text" value="{{ old('contactnumber') }}" name="contactnumber" class="form-control">
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="email">Student Email Address</label>
			<input type="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="abc@mail.xyz">
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			<label for="">Are You Transferee?</label>
			<br>
			<div class="form-check form-check-inline">
				<input class="form-check-input" id="transferee_yes" type="radio" name="is_transferee" value="1" @if(old('is_transferee')) checked @endif>
				<label class="form-check-label" for="transferee_yes">
					Yes
				</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" id="transferee_no" type="radio" name="is_transferee" value="0" @if(!old('is_transferee')) checked @endif>
				<label class="form-check-label" for="transferee_no">
					No
				</label>
			</div>
		</div>
	</div>

	{{-- @php
		use Carbon\Carbon;

		$today = (int)Carbon::today()->format('Y');

	@endphp --}}
			{{-- 		
	<div class="col-md-4 transferee-group" style="{{ (is_array(old('is_transferee')) && in_array('1', old('is_transferee'))) ? 'display: block' : 'display: none' }}">
		<div class="form-group">
			<label for="inclusive_date">Inclusive Date</label>
			<select name="inclusive_dates" id="" class="form-control">
				<option value="" selected disabled>Please Select Inclusive Date</option>
				@for($i = $today; $i >= $today - 40; $i--)
					<option value="{{ $i - 1 }} - {{ $i }}">{{ $i - 1 }} - {{ $i }}</option>
				@endfor
			</select>
		</div>
	</div> --}}

	<div class="col-md-6 transferee-group" style="{{ (is_array(old('is_transferee')) && in_array('1', old('is_transferee'))) ? 'display: block' : 'display: none' }}">
		<div class="form-group">
			<label for="previousschool">Last School Attended<span class="required">*</span></label>
			<input type="text" value="{{ old('previousschool') }}" name="previousschool" class="form-control">
		</div>
	</div>
</div>