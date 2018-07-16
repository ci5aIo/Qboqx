<?php

elgg_load_js('lightbox');
elgg_load_css('lightbox');

elgg_push_breadcrumb(elgg_echo('jot:title'), "jot/show");
elgg_push_breadcrumb(elgg_echo('jot:add'));

if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}

// How many classifieds can a user have
$max = elgg_get_plugin_setting('jot_max', 'jot');
if(!$max){
	$max == 0;
}
//count_user_objects() was deprecated in favor of elgg_get_entities()
//$active = count_user_objects(elgg_get_logged_in_user_guid(), 'jot');
$active = elgg_get_entities(elgg_get_logged_in_user_guid(), 'jot');

$title = elgg_echo('jot:add:title');

$form_vars = array('name' => 'jotForm', 'enctype' => 'multipart/form-data');
// set up sticky form

$body_vars = array(
    'title' => NULL,
    'body' => NULL,
    'tags' => NULL,
    'aspect' => NULL,
    'access_id' => ACCESS_DEFAULT,
);
if (elgg_is_sticky_form('jot')) {
		$sticky_values = elgg_get_sticky_values('jot');
		foreach ($sticky_values as $key => $value) {
			$body_vars[$key] = $value;
		}
}

elgg_clear_sticky_form('jot');

// Show form, or error if users has used his quota
if ($max == 0 || elgg_is_admin_logged_in()) {
	$content = elgg_view_form("jot/{$subtype}s/add", $form_vars, $body_vars);
} elseif ($max > $active) {
	$content = elgg_view_form("jot/{$subtype}s/add", $form_vars, $body_vars);
} else {
	$content = elgg_view("jot/error");
}

// Show jot sidebar
$sidebar = elgg_view("jot/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
