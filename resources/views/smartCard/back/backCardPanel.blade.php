<div class="front-back-wrapper tab-pane" id="front-back-wrapper">
	<h3>Back Card Design</h3>

	<div class="form-group">
		<label title="Add a background" for="file">Add Background Image</label>
		<input type="file" id="card_back_file" class="form-control " />
	</div>
	
	<div class="form-group">
		<label for="">Add Avatar Placeholder</label>
		<br>
		<a href="javascript:void(0)" onclick="addAvatarBack('rect')" value="1"><i class="fa fa-square-o"></i> Rectangle</a>&nbsp;
		<a href="javascript:void(0)" onclick="addAvatarBack('circle')" value="1"><i class="fa fa-circle-o"></i> Rectangle</a>&nbsp;
		<a href="javascript:void(0)" onclick="addAvatarBack('triangle')" value="1"><i class="fa fa-exclamation-triangle"></i> Triangle</a>&nbsp;
	</div>
	
	<div class="form-group">
		<button class="btn btn-default btn" onclick="addTextBack()">Insert Text</button>
	</div>

	<div class="form-group">
		<label for="fields_back">Add Back Field</label>
		<select id="fields_back" class="form-control">
			@foreach($studentColumns as $item)
		            <option value="{{$item}}">{{strtoupper(str_replace('_id','',$item))}}</option>
		    @endforeach
		</select>
	<button class="btn btn-default btn-block" onclick="addTextFieldBack()">Add Back Field</button>
	</div>

	<div class="form-group">
		<input type="checkbox" id="blackwhiteBack" onclick="blackWhiteBack()"> Black & White
	</div>

	{{-- <a class="btn btn-default btn-block" onclick="addAvatarBack()" title="Add text"><span class="mdi mdi-format-text"> Add Avatar Placeholder</span></a>&emsp; --}}
	{{-- <a class="btn btn-default" onclick="addFullNameBack()" title="Add text"><span class="mdi mdi-format-text"> Add Full Name</span></a>&emsp; --}}
	{{-- <a class="btn btn-default" onclick="addYearLevelBack()" title="Add text"><span class="mdi mdi-format-text"> Add Year Level</span></a>&emsp; --}}
	{{-- <a class="btn btn-default" onclick="addStudentNumberBack()" title="Add text"><span class="mdi mdi-format-text"> Add Student Number</span></a>&emsp; --}}
	<div class="form-group">
		<div class="btn-group">
			<a class="btn btn-default" onClick="deleteObjectBack()" title="Delete Anything Selected"><span class="mdi mdi-delete"> Delete</span></a>&emsp;
			<a class="btn btn-default" onclick="refreshBack()" title="Start fresh"><span class="mdi mdi-shredder"> Clear All</span></a>&emsp;
			<a class="btn btn-default" id="lnkDownloadBack" title="Save"><span class="mdi mdi-download"> Download</span></a>
			<a class="btn btn-default" onclick="textAlignBack('left')" id="textAlignLeftBack" title="Text Left"><span class="mdi mdi-text-left"> Left</span></a>
			<a class="btn btn-default" onclick="textAlignBack('center')" id="textAlignCenterBack" title="Text Center"><span class="mdi mdi-text-center"> Center</span></a>
			<a class="btn btn-default" onclick="textAlignBack('right')" id="textAlignRightBack" title="Text Right"><span class="mdi mdi-text-right"> Right</span></a>
		</div>
	</div>

	<div id="textControlsBack" hidden>
	   	<div id="text-wrapper" data-ng-show="getText()">
	      	<div id="text-controls" class="form-group">
	         {{-- <select id="font-family">
	            <option value="arial">Arial</option>
	            <option value="HelveticaNeue" selected>Helvetica Neue</option>
	            <option value="myriad pro">Myriad Pro</option>
	            <option value="delicious">Delicious</option>
	            <option value="verdana">Verdana</option>
	            <option value="georgia">Georgia</option>
	            <option value="courier">Courier</option>
	            <option value="comic sans ms">Comic Sans MS</option>
	            <option value="impact">Impact</option>
	            <option value="monaco">Monaco</option>
	            <option value="optima">Optima</option>
	            <option value="hoefler text">Hoefler Text</option>
	            <option value="plaster">Plaster</option>
	            <option value="engagement">Engagement</option>
	         </select> --}}
	         <select id="font-family-back">
	         	@foreach (config('fonts') as $key => $font)
	         		<option value="{{ $font }}">{{ $key }}</option>
	         	@endforeach
	         </select>
	         	<input type="color" id="text-color-back" size="10">
	         {{-- <select id="text-align">
	            <option value="left">Align Left</option>
	            <option value="center">Align Center</option>
	            <option value="right">Align Right</option>
	            <option value="justify">Align Justify</option>
	         </select> --}}
	         {{-- <label for="text-stroke-color">Stroke C:</label> --}}
	         {{-- <input type="color" id="text-stroke-color"> --}}
	         {{-- <label for="text-stroke-width">Stroke W:</label> --}}
	         {{-- <input type="number" value="1" min="1" max="5" id="text-stroke-width"> --}}
	         <label for="text-font-size-back">Font S:</label>
	         <input type="number" min="12" max="120" step="1" id="text-font-size-back">
	         {{-- <label for="text-line-height">Line H:</label> --}}
	         {{-- <input type="number" min="0" max="10" step="0.1" id="text-line-height"> --}}
	         {{-- <label for="text-bg-color">BG Color:</label> --}}
	         {{-- <input type="color" id="text-bg-color" size="10"> --}}
	         {{-- <label for="text-lines-bg-color">BG Text Color:</label> --}}
	         {{-- <input type="color" id="text-lines-bg-color" size="10"> --}}
		        <input type='checkbox' name='fonttypeBack' id="text-cmd-bold-back"> <b>B</b>
		        <input type='checkbox' name='fonttypeBack' id="text-cmd-italic-back"> <em>I</em>
	         {{-- <input type='checkbox' name='fonttype' id="text-cmd-underline"> Underline --}}
	         {{-- <input type='checkbox' name='fonttype' id="text-cmd-linethrough"> Linethrough --}}
	         {{-- <input type='checkbox' name='fonttype' id="text-cmd-overline"> Overline --}}
	      	</div>
	   	</div>
	</div>

	<div id="shapeControlsBack" hidden>
		<div id="shape-wrapper">
			<div id="shape-controls" class="form-group">
				<input type="color" id="shape-fill-back" size="10">
			</div>
		</div>
	</div>
</div>