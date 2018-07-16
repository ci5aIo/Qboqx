<?php

// Get input
$selected_category = get_input('cat');

if ($selected_category == 'all') {
	$category = '';
} elseif ($selected_category == '') {
	$category = '';
	$selected_category = 'all';
} else {
	$category = $selected_category;
}
elgg_set_context('quebx');

elgg_pop_breadcrumb();

//quebx_register_title_button(null, $selected_category);

$name = $selected_category;
$button_action = 'add';
elgg_register_title_button();


$add_quick = array (
	'view' =>  'default/forms/items/add_now',
	'vars' => array(),
	'bypass' => 'false',
	'viewtype' => '', 
);
//$tabs = elgg_view('quebx/menu', array('category' => $selected_category));
$tabs = elgg_view("default/forms/items/add_now");

//set quebx title
$title = sprintf(elgg_echo('quebx:category:title'), elgg_echo("quebx:category:{$selected_category}"));
$num_items = 16;  // 0 = Unlimited

$options = array(
	'types' => 'object',
	'subtypes' => 'item',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
//	'view_type_toggle' => true, //depricated in 1.8
	'list_type' => 'list',
	'list_type_toggle' => true,
);

// Get a list of items in a specific category
if (!empty($category)) {
	elgg_push_breadcrumb(elgg_echo('quebx:title'), "quebx/queb");
	elgg_push_breadcrumb(elgg_echo("quebx:category:{$category}"));
	$options['metadata_name'] = "quebxcategory";
	$options['metadata_value'] = $selected_category;
	$content = elgg_list_entities_from_metadata($options);
} else {
	elgg_push_breadcrumb(elgg_echo('quebx:title'));
	$content = elgg_list_entities($options);
}

if (!$content) {
	$content = elgg_echo('quebx:none:found');
}

// Show sidebar
$sidebar = elgg_view("quebx/sidebar");

$params = array(
		'filter' => $tabs,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

//$body = elgg_view_layout('one_column', $params);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

