<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

$element_type = get_input('element_type');
$item = get_entity(get_input('container_guid'));

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo("Attach {$element_type}s");
// create form
$form_vars  = array('enctype' => 'multipart/form-data', 
                    'name'    => 'file',
					'action'  => 'action/jot/upload_file'
					);
$form_vars2 = array('enctype' => 'multipart/form-data', 
                    'name'    => 'files_list');
$body_vars = jot_prepare_file_vars();
//$content = elgg_view_form('jot/attach', $form_vars, $body_vars);
//$content .= '<br>';
$content .= elgg_view_form('jot/attach2', $form_vars2, $body_vars);
$content .= '<br>';

$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));
$title = '';
$header = '';
$show_inner = false;

/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 *                              - defined as classes in views\default\css\elements\modules.php
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */
$module_type = 'popup';
 
echo elgg_view_module($module_type, $title, $body);
