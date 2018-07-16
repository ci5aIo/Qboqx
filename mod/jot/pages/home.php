<?php
/**
 * Elgg jot Plugin
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get input
$selected_aspect = get_input('aspect');

if ($selected_aspect == 'all') {
	$aspect = '';
} elseif ($selected_aspect == '') {
	$aspect = '';
	$selected_aspect = 'all';
} else {
	$aspect = $selected_aspect;
}
//elgg_set_context('jot');

elgg_pop_breadcrumb();

//quebx_register_title_button(null, $selected_aspect);

$name = $selected_aspect;
$button_action = 'add';
elgg_register_title_button();

$tabs = elgg_view('jot/menu', array('aspect' => $selected_aspect));


//set jot title
$title = sprintf(elgg_echo('jot:aspect:title'), elgg_echo("jot:aspect:{$selected_aspect}"));

$num_items = 16;  // 0 = Unlimited

$options = array(
	'types' => 'object',
	'subtypes' => array('jot', 'observation'), //'transfer', 'issue', 'observation',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
	'list_type' => 'list',
	'list_type_toggle' => true,
);


// Get a list of jots for a specific aspect
if (!empty($aspect)) {
	elgg_push_breadcrumb(elgg_echo('jot:title'), "jot/aspect");
	elgg_push_breadcrumb(elgg_echo("jot:aspect:{$aspect}"));
	$options['metadata_name'] = "jot";
	$options['metadata_value'] = $selected_aspect;
	$content = elgg_list_entities_from_metadata($options);
} else {
	elgg_push_breadcrumb(elgg_echo('jot:title'));
	$content = elgg_list_entities($options);
}

if (!$content) {
	$content = elgg_echo('jot:none:found');
}

// Show jot sidebar
$sidebar = elgg_view("jot/sidebar");

$params = array(
		'filter' => $tabs,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

