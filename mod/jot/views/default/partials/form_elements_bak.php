<?php
echo elgg_view_field([
		'#type' => 'date',
		'style'=> 'z-index:1000'
]);

echo elgg_view_field([
		'#type' => 'dropzone',
]);

// if using third-party input views, make sure they initialize their scripts inline
// require(['third-party-input']);
// and not using elgg_require_js(), because currently those are not intialized on
// ajax requests

/*
 * Inline Example

// ckeditor/start
	elgg_define_js('ckeditor', array(
		'deps' => ['elgg/ckeditor/set-basepath'],
		'exports' => 'CKEDITOR',
	));
	
	elgg_extend_view('input/longtext', 'ckeditor/init');

//ckeditor/views/default/ckeditor/init.php
<script>
	require(['elgg/ckeditor'], function (elggCKEditor) {
		elggCKEditor.bind('.elgg-input-longtext');
	});
</script>

//ckeditor/views/default/elgg/ckeditor.js
define(function (require) {
	...
}
*/