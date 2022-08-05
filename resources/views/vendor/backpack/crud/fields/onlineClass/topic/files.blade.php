<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

	{{-- Show the file name and a "Clear" button on EDIT form. --}}
    @if (!empty($field['value']))
    <div class="well well-sm">
        @if (isset($field['disk']))
        @if (isset($field['temporary']))
            <a target="_blank" href="{{ (asset(\Storage::disk($field['disk'])->temporaryUrl(array_get($field, 'prefix', '').$field['value'], Carbon\Carbon::now()->addMinutes($field['temporary'])))) }}">
        @else
            <a target="_blank" href="{{ (asset(\Storage::disk($field['disk'])->url(array_get($field, 'prefix', '').$field['value']))) }}">
        @endif
        @else
            <a target="_blank" href="{{ (asset(array_get($field, 'prefix', '').$field['value'])) }}">
        @endif
            {{ $field['value'] }}
        </a>
    	<a id="{{ $field['name'] }}_file_clear_button" href="#" class="btn btn-default btn-xs pull-right" title="Clear file"><i class="fa fa-remove"></i></a>
    	<div class="clearfix"></div>
    </div>
    @endif

	{{-- Show the file picker on CREATE form. --}}
	<div id="items-container"></div>
	<div id="preview" class="form-group"></div>
	<input
        type="file"
        id="{{ $field['name'] }}_file_input"
        name="{{ $field['name'] }}[]"
        value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
        @include('crud::inc.field_attributes', ['default_class' =>  isset($field['value']) && $field['value']!=null?'form-control hidden':'form-control'])
    >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
		<style type="text/css">
			 .file-cont {
		      width: 100%; 
		      height: 40px; 
		      border: 1px solid #ccc; 
		      border-radius: 5px; 
		      background-color: #ddd; 
		      display: flex; 
		      margin: 10px 0px;
		    }

		    .file-text {
		      padding: 8px;
		    }

		    .file-icon {
		      padding: 5px; 
		      border-radius:  5px; 
		      background-color: #ddd; 
		      text-align: left;
		    }

		    .file-close {
		      float: right !important;
		      position: absolute;
		      right: 5px;
		    }
		</style>
        <!-- no styles -->
    @endpush

{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script>
        	$("#items-container").hide();
        	// $("#{{ $field['name'] }}_file_items").hide();
        	var file_count = 0;
        	// var file_items = [];
        	var file_items = []; 
	        $(".file-clear-button").click(function(e) {
	        	e.preventDefault();
	        	var container = $(this).parent().parent();
	        	var parent = $(this).parent();
	        	// remove the filename and button
	        	parent.remove();
	        	// if the file container is empty, remove it
	        	if ($.trim(container.html())=='') {
	        		container.remove();
	        	}
	        	$("<input type='hidden' name='clear_{{ $field['name'] }}[]' value='"+$(this).data('filename')+"'>").insertAfter("#{{ $field['name'] }}_file_input");
	        });

	        $("#{{ $field['name'] }}_file_input").change(function() {
	        	// console.log($(this).val());
	        	// remove the hidden input, so that the setXAttribute method is no longer triggered
	        	// $(this).next("input[type=hidden]").remove();
	        	$(this)[0].files

				// var i;
	        	var outputs = '';
	        	var files =  $(this)[0].files;
	        	if (files.length > 0) {
				    var i;
		        	var outputs;
					for (i = 0; i < $(this)[0].files.length; i++) {
					  outputs += '<div id="file-index-'+file_count+'" class="input-group file-cont">'+
					  				'<i class="file-icon fa fa-files-o fa-2x p-1"></i>' +
					  				'<p class="file-text">'+files.item(i).name+' ('+ Math.round((files.item(i).size/1024))+'KB)'+' </p>' +
					  				'<a data-index="'+file_count+'" href="#" onclick="removeFile('+file_count+')"><i class="file-close fa fa-times float-right pull-right justify-content-end"></i></a>' +
					  			'</div>';
					  
					  file_item = (files.item(i));
					  file_items.push(file_item);
					  // console.log(file_items);
					  
					  $('#items-container').append('<div id="item-'+file_count+'"><input type="file" name="files[]" id="file-'+file_count+'"/></div>');
					  $("#file-"+file_count)[0].files =  $(this)[0].files;
					  file_count++;
					}

					$('#preview').append(outputs);

				}
				if(file_items.length > 0){
	        		$('#btnPost').attr('disabled', false);
	        	} else {
	        		$('#btnPost').attr('disabled', true);
	        	}
	        });

	        function removeFile(index){
	        	file_items.splice(index,1);
	        	$("#file-index-"+index).remove();
	        	$("#item-"+index).remove();
	        }
        </script>

    @endpush
