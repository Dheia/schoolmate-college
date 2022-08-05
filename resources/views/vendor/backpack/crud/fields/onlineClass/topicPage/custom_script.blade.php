@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {
		    CKEDITOR.config.mathJaxLib = '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML';
		    CKEDITOR.editor.emoji_emojiListUrl = 'https://my.custom.domain/ckeditor/emoji.json';
		});

	</script>
@endpush