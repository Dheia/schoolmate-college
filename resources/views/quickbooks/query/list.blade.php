@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	Query
        	{{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
        	<li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        	<li class="active">Query</li>
        </ol>
    </section>
@endsection

@push('after_styles')
	<style>
		/* Root element */
		.json-document {
		  padding: 1em 2em;
		}

		/* Syntax highlighting for JSON objects */
		ul.json-dict, ol.json-array {
		  list-style-type: none;
		  margin: 0 0 0 1px;
		  border-left: 1px dotted #ccc;
		  padding-left: 2em;
		}
		.json-string {
		  color: #0B7500;
		}
		.json-literal {
		  color: #1A01CC;
		  font-weight: bold;
		}

		/* Toggle button */
		a.json-toggle {
		  position: relative;
		  color: inherit;
		  text-decoration: none;
		}
		a.json-toggle:focus {
		  outline: none;
		}
		a.json-toggle:before {
		  font-size: 1.1em;
		  color: #c0c0c0;
		  content: "\25BC"; /* down arrow */
		  position: absolute;
		  display: inline-block;
		  width: 1em;
		  text-align: center;
		  line-height: 1em;
		  left: -1.2em;
		}
		a.json-toggle:hover:before {
		  color: #aaa;
		}
		a.json-toggle.collapsed:before {
		  /* Use rotated down arrow, prevents right arrow appearing smaller than down arrow in some browsers */
		  transform: rotate(-90deg);
		}

		/* Collapsable placeholder links */
		a.json-placeholder {
		  color: #aaa;
		  padding: 0 1em;
		  text-decoration: none;
		}
		a.json-placeholder:hover {
		  text-decoration: underline;
		}
	</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
	<div class="row">
		{{-- {{ dd(get_defined_vars()) }} --}}
		<div class="col-md-12">
			<div class="box">

				<div class="box-header with-border">
					<h1 class="text-center">EMERGENCY QUERY BEAST MODE -.-</h1>
			    </div>
				
				<div class="box-body">
					<form action="{{ url()->current() }}" method="post">
						{{ csrf_field() }}
						<div class="form-group col-md-12">
							<label for="query">Type SQL</label>
							<input type="text" name="query" value="{{ old('query', $query) }}" class="form-control" placeholder="(ex. SELECT * FROM Item)">
						</div>
						<div class="form-group col-md-12">
							<button class="btn btn-primary">Run Query</button>
						</div>
					</form>

					<div class="col-md-12">
						@if($query !== null)
							<pre id="json-renderer"></pre>
						@endif
					</div>
				</div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')
	
	<script>

		/**
		 * jQuery json-viewer
		 * @author: Alexandre Bodelot <alexandre.bodelot@gmail.com>
		 * @link: https://github.com/abodelot/jquery.json-viewer
		 */
		(function($) {

		  /**
		   * Check if arg is either an array with at least 1 element, or a dict with at least 1 key
		   * @return boolean
		   */
		  function isCollapsable(arg) {
		    return arg instanceof Object && Object.keys(arg).length > 0;
		  }

		  /**
		   * Check if a string represents a valid url
		   * @return boolean
		   */
		  function isUrl(string) {
		     var regexp = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		     return regexp.test(string);
		  }

		  /**
		   * Transform a json object into html representation
		   * @return string
		   */
		  function json2html(json, options) {
		    var html = '';
		    if (typeof json === 'string') {
		      // Escape tags
		      json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		      if (options.withLinks && isUrl(json)) {
		        html += '<a href="' + json + '" class="json-string" target="_blank">' + json + '</a>';
		      } else {
		        html += '<span class="json-string">"' + json + '"</span>';
		      }
		    } else if (typeof json === 'number') {
		      html += '<span class="json-literal">' + json + '</span>';
		    } else if (typeof json === 'boolean') {
		      html += '<span class="json-literal">' + json + '</span>';
		    } else if (json === null) {
		      html += '<span class="json-literal">null</span>';
		    } else if (json instanceof Array) {
		      if (json.length > 0) {
		        html += '[<ol class="json-array">';
		        for (var i = 0; i < json.length; ++i) {
		          html += '<li>';
		          // Add toggle button if item is collapsable
		          if (isCollapsable(json[i])) {
		            html += '<a href class="json-toggle"></a>';
		          }
		          html += json2html(json[i], options);
		          // Add comma if item is not last
		          if (i < json.length - 1) {
		            html += ',';
		          }
		          html += '</li>';
		        }
		        html += '</ol>]';
		      } else {
		        html += '[]';
		      }
		    } else if (typeof json === 'object') {
		      var key_count = Object.keys(json).length;
		      if (key_count > 0) {
		        html += '{<ul class="json-dict">';
		        for (var key in json) {
		          if (json.hasOwnProperty(key)) {
		            html += '<li>';
		            var keyRepr = options.withQuotes ?
		              '<span class="json-string">"' + key + '"</span>' : key;
		            // Add toggle button if item is collapsable
		            if (isCollapsable(json[key])) {
		              html += '<a href class="json-toggle">' + keyRepr + '</a>';
		            } else {
		              html += keyRepr;
		            }
		            html += ': ' + json2html(json[key], options);
		            // Add comma if item is not last
		            if (--key_count > 0) {
		              html += ',';
		            }
		            html += '</li>';
		          }
		        }
		        html += '</ul>}';
		      } else {
		        html += '{}';
		      }
		    }
		    return html;
		  }

		  /**
		   * jQuery plugin method
		   * @param json: a javascript object
		   * @param options: an optional options hash
		   */
		  $.fn.jsonViewer = function(json, options) {
		    // Merge user options with default options
		    options = Object.assign({}, {
		      collapsed: false,
		      rootCollapsable: true,
		      withQuotes: false,
		      withLinks: true
		    }, options);

		    // jQuery chaining
		    return this.each(function() {

		      // Transform to HTML
		      var html = json2html(json, options);
		      if (options.rootCollapsable && isCollapsable(json)) {
		        html = '<a href class="json-toggle"></a>' + html;
		      }

		      // Insert HTML in target DOM element
		      $(this).html(html);
		      $(this).addClass('json-document');

		      // Bind click on toggle buttons
		      $(this).off('click');
		      $(this).on('click', 'a.json-toggle', function() {
		        var target = $(this).toggleClass('collapsed').siblings('ul.json-dict, ol.json-array');
		        target.toggle();
		        if (target.is(':visible')) {
		          target.siblings('.json-placeholder').remove();
		        } else {
		          var count = target.children('li').length;
		          var placeholder = count + (count > 1 ? ' items' : ' item');
		          target.after('<a href class="json-placeholder">' + placeholder + '</a>');
		        }
		        return false;
		      });

		      // Simulate click on toggle button when placeholder is clicked
		      $(this).on('click', 'a.json-placeholder', function() {
		        $(this).siblings('a.json-toggle').click();
		        return false;
		      });

		      if (options.collapsed == true) {
		        // Trigger click to collapse all nodes
		        $(this).find('a.json-toggle').click();
		      }
		    });
		  };
		})(jQuery);

	</script>


	<script>
		var data = {!! json_encode($response) !!};
		$('#json-renderer').jsonViewer(data);
	</script>
@endpush