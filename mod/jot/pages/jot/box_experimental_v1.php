<?php
// Get input data
$jot_guid  = $page[1];
if (isset($page[2])) {
    $aspect = $page[2];
}
if (isset($page[3])) {
    $action = $page[3];
}
$title = elgg_echo("jot:title:{$aspect}");

$form      = 'jot/add_experimental';
$form_vars = array('name'    => 'jotForm', 
                   'enctype' => 'multipart/form-data',
                   'action'  => 'action/jot/add',
				  );
$body_vars = array('message' => 'hello world');
$content = elgg_view_form($form, NULL, $body_vars);
$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => FALSE,	
	'filter' => '',
));

$module_type = 'popup';
 
echo elgg_view_module($module_type, $title, $body);
