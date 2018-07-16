<?php

elgg_push_breadcrumb(elgg_echo('quebx:title'), "quebx/category");
elgg_push_breadcrumb(elgg_echo('quebx:add'));

if (elgg_get_plugin_setting('quebx_adminonly', 'quebx') == 'yes') {
	admin_gatekeeper();
}

// How many items can a user have
$quebxmax = elgg_get_plugin_setting('quebx_max', 'quebx');
if(!$quebxmax){
	$quebxmax == 0;
}
//count_user_objects() was deprecated in favor of elgg_get_entities()
//$quebxactive = count_user_objects(elgg_get_logged_in_user_guid(), 'quebx');
$quebxactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'quebx');

$title = elgg_echo('quebx:add:title');

$form_vars = array('name' => 'quebxForm', 'enctype' => 'multipart/form-data');
// set up sticky form

$body_vars = array(
    'title' => NULL,
    'body' => NULL,
    'tags' => NULL,
    'category' => NULL,
    'access_id' => ACCESS_DEFAULT,
);
if (elgg_is_sticky_form('quebx')) {
		$sticky_values = elgg_get_sticky_values('quebx');
		foreach ($sticky_values as $key => $value) {
			$body_vars[$key] = $value;
		}
}

elgg_clear_sticky_form('quebx');

// Show form, or error if users has used his quota
if ($quebxmax == 0 || elgg_is_admin_logged_in()) {
	$content = elgg_view_form("quebx/edit", $form_vars, $body_vars);
} elseif ($quebxmax > $quebxactive) {
	$content = elgg_view_form("quebx/edit", $form_vars, $body_vars);
} else {
	$content = elgg_view("quebx/error");
}

// Show quebx sidebar
$sidebar = elgg_view("quebx/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
