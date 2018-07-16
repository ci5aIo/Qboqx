<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');
// Get input data
$jot_guid  = $page[1];
if (isset($page[2])) {
    $aspect = $page[2];
}
if (isset($page[3])) {
    $action = $page[3];
}

$element_type = $aspect;
//$element_type = get_input('element_type');
$item = get_entity(get_input('container_guid'));

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo("Attach {$element_type}s");
// create form
$form      = 'jot/add_experimental';
//$form      = 'jot/attach2';
$form_vars  = array('enctype' => 'multipart/form-data', 
                    'name'    => 'file',
					'action'  => 'action/jot/upload_file'
					);
$form_vars2 = array('enctype' => 'multipart/form-data', 
                    'name'    => 'files_list');
$body_vars = jot_prepare_file_vars();
//$content = elgg_view_form('jot/attach', $form_vars, $body_vars);
//$content .= '<br>';
$content .= elgg_view_form($form, $form_vars2, $body_vars);
//$content .= elgg_view_form('jot/attach2', $form_vars2, $body_vars);

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

$title = '';
$header = '';
$module_vars = array($show_inner = true);
$module_type = 'popup';
$page_shell = 'box';
 
echo elgg_view_page($title, $body, $page_shell);
//echo elgg_view_module($module_type, $title, $body, $module_vars);
