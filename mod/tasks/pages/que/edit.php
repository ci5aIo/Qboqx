<?php
/**
 * Edit a que
 *
 * @package ElggTasks
 */

gatekeeper();

$que_guid = (int)get_input('guid');
if (empty($que_guid)){$que_guid   = $vars['item_guid'];}
$section =  elgg_extract('section', $vars, 'Schedule');
$que = get_entity($que_guid);
if (!$que) {
	register_error(elgg_echo('noaccess'));
	forward('');
}
$tabs = elgg_view('quebx/menu', array('guid' =>$que_guid, 'this_section' => $section, 'action'=>'edit'));
if(!$referrer){
	$referrer = elgg_get_site_url()."que/set/$que_guid";
}

$container = $que->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($que->title, "que/set/$que_guid");
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo($que->title);

if ($que->canEdit()) {
	$form_vars = array();
	$body_vars = tasks_prepare_form_vars($que);
	$body_vars['referrer'] = $referrer;
	$content = elgg_view_form('que/edit', $form_vars, $body_vars);
} else {
	$content = elgg_echo("tasks:noaccess");
}

$body = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $tabs,
	'content' => $content,
));

echo elgg_view_page($title, $body);
