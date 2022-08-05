<div class="front-card-wrapper tab-pane active"  id="front-card-wrapper">
					
	<h3>Front Card Design</h3>

	<div class="form-group">
		<label title="Add a background" for="file">Add Background Image</label>
		<input type="file" id="file2" class="form-control" />
	</div>

	<div class="form-group">
		<label for="">Add Avatar Placeholder</label>
		<br>
		<a href="javascript:void(0)" onclick="addAvatarFront('rect')" value="1"><i class="fa fa-square-o"></i> Rectangle</a>&nbsp;
		<a href="javascript:void(0)" onclick="addAvatarFront('circle')" value="1"><i class="fa fa-circle-o"></i> Rectangle</a>&nbsp;
		<a href="javascript:void(0)" onclick="addAvatarFront('triangle')" value="1"><i class="fa fa-exclamation-triangle"></i> Triangle</a>&nbsp;
	</div>

	<div class="form-group">
		<button class="btn btn-default btn" onclick="addTextFront()">Insert Text</button>
	</div>

	<div class="form-group">
		<label for="fields">Add Front Field</label>
		<select id="fields" name="fields" class="form-control">
			@foreach($studentColumns as $item)
		            <option value="{{$item}}">{{strtoupper(str_replace('_id','',$item))}}</option>
		    @endforeach
		</select>
	<button class="btn btn-default btn-block" onclick="addTextFieldFront()">Add Front Field</button>
	</div>


	{{-- <a class="btn btn-default" onclick="addFullName()" title="Add text"><span class="mdi mdi-format-text"> Add Full Name</span></a>&emsp;
	<a class="btn btn-default" onclick="addYearLevel()" title="Add text"><span class="mdi mdi-format-text"> Add Year Level</span></a>&emsp;
	<a class="btn btn-default" onclick="addStudentNumber()" title="Add text"><span class="mdi mdi-format-text"> Add Student Number</span></a>&emsp; --}}
	<div class="btn-group">
		<a class="btn btn-default" onClick="deleteObjectFront()" title="Delete Anything Selected"><span class="mdi mdi-delete"> Delete</span></a>&emsp;
		<a class="btn btn-default" onclick="refreshFront()" title="Start fresh"><span class="mdi mdi-shredder"> Clear All</span></a>&emsp;
		<a class="btn btn-default" id="lnkDownloadFront" title="Save"><span class="mdi mdi-download"> Download</span></a>
		<a class="btn btn-default" onclick="textAlignFront('left')" id="textAlignLeft" title="Text Left"><span class="mdi mdi-text-left"> Left</span></a>
		<a class="btn btn-default" onclick="textAlignFront('center')" id="textAlignCenter" title="Text Center"><span class="mdi mdi-text-center"> Center</span></a>
		<a class="btn btn-default" onclick="textAlignFront('right')" id="textAlignRight" title="Text Right"><span class="mdi mdi-text-right"> Right</span></a>
	</div>

	<div id="textControlsFront" hidden>
	   <div id="text-wrapper" data-ng-show="getText()">
	      <div id="text-controls">
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
	         <select id="font-family-front">
	         	@foreach (config('fonts') as $key => $font)
	         		<option value="{{ $font }}">{{ $key }}</option>
	         	@endforeach
	         </select>
	         <input type="color" id="text-color-front" size="10">
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
	         <label for="text-font-size-front">Font S:</label>
	         <input type="number" min="12" max="120" step="1" id="text-font-size-front">
	         {{-- <label for="text-line-height">Line H:</label>
	         <input type="number" min="0" max="10" step="0.1" id="text-line-height">
	         <label for="text-bg-color">BG Color:</label>
	         <input type="color" id="text-bg-color" size="10">
	         <label for="text-lines-bg-color">BG Text Color:</label>
	         <input type="color" id="text-lines-bg-color" size="10"> --}}
	         <input type='checkbox' name='fonttype-front' id="text-cmd-bold"> <b>B</b>
	         <input type='checkbox' name='fonttype-front' id="text-cmd-italic"> <em>I</em>
			
	         {{--<input type='checkbox' name='fonttype' id="text-cmd-underline"> Underline
	         <input type='checkbox' name='fonttype' id="text-cmd-linethrough"> Linethrough
	         <input type='checkbox' name='fonttype' id="text-cmd-overline"> Overline --}}
	      </div>
	   </div>
	</div>
	{{-- <button class="btn btn-primary" id="btnJson">Save</button> --}}

	<div id="shapeControlsFront" hidden>
		<div id="shape-wrapper">
			<div id="shape-controls" class="form-group">
				<input type="color" id="shape-fill-front" size="10">
			</div>
		</div>
	</div>
</div>