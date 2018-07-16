<?php
$observation      = $vars['entity'];

$body = elgg_view('gallery_field/images_list', array(
		'entity' => $observation,
	));	
echo $body;