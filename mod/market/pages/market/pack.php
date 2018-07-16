<?php
/**
 * Select a container
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

$element_type   = get_input('element_type');
$container_guid = get_input('container_guid');
$item           = get_entity($container_guid);
$owner          = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
$form_vars = array('enctype' => 'multipart/form-data', 
                   'name'    => 'item_list');
$title = elgg_echo("Pack in a different container");
$content = elgg_view_form('market/pack', $form_vars);
$content .= '<br>$element_type: '.$element_type;
$content .= '<br>$container_guid: '.$container_guid;

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
