<?php
/**
 * Create a new task
 *
 * @package ElggPages
 * 
 * 11/01/2014 - edited by scottj
 */

gatekeeper();

$container_guid = (int) get_input('guid');

//$input_guid = (int) get_input('parent_guid');
//if ($container_guid == 0) {$container_guid = (int) get_input('container_guid');
//if ($container_guid == 0) {$container_guid = (int) get_input('parent_guid');
/*if ($container_guid == 0) {$container_guid = $input_guid;
}*/

$owner = elgg_get_page_owner_entity();

$container = get_entity($container_guid);
//$container = get_entity($owner);
if (!$container) {

}

$parent_guid = 0;
$task_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$parent_guid = $container->getGUID();
	$task_owner = $container->getContainerEntity();
    elgg_push_breadcrumb($container->title, "market/view/".$container_guid);
}

elgg_set_page_owner_guid($task_owner->getGUID());

elgg_push_breadcrumb($container->title, "market/view/".$container_guid);
elgg_push_breadcrumb("dork", "market/view/225");

$title = elgg_echo('tasks:add');
//elgg_push_breadcrumb($title);	
elgg_push_breadcrumb("dork");	

$vars = tasks_prepare_form_vars(null, $parent_guid); 
$content = elgg_view_form('tasks/edit', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
