<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

//$element_type = get_input('element_type');
$item_guid = (int) get_input('guid');
$item = get_entity($item_guid);

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
$title = elgg_echo("Set in a different place");
$form_vars = array('enctype'       => 'multipart/form-data', 
                   'name'          => 'place_list');
$body_vars = array('container_guid'=> $item_guid);
// $content  = '<br>$element_type: '.$element_type;
// $content .= '<br>$container_guid: '.$item_guid;
$content .= elgg_view_form('places/set', $form_vars, $body_vars);

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
