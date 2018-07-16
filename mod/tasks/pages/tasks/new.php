<?php
/**
 * Create a new task
 *
 * @package ElggTasks
 */

gatekeeper();

$container_guid = (int) get_input('guid');
if (!$container_guid) {$container_guid = (int) get_input('container_guid');}
$element_type = get_input('element_type');
$mod_type     = get_input('mod_type');

$container = get_entity($container_guid);
if (!$container) {

}

$parent_guid = 0;
$task_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$parent_guid = $container->getGUID();
	$task_owner = $container->getContainerEntity();
//    elgg_push_breadcrumb($container->title, "market/view/".$container_guid);
}

elgg_set_page_owner_guid($task_owner->getGUID());

// 11/22/2014 - Added by scottj
//elgg_push_breadcrumb($container->title, "market/view/".$container_guid);
//
$title = elgg_echo('tasks:add');
elgg_push_breadcrumb($title);

$body_vars = tasks_prepare_form_vars(null, $parent_guid); 
$content = elgg_view_form('tasks/edit', array(), $body_vars);
$content = 'Page: mod\tasks\pages\tasks\new.php<br>'.$content;
$content = '$mod_type: '.$mod_type.'<br>'.$content;
$content = '$element_type: '.$element_type.'<br>'.$content;

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));



echo elgg_view_page($title, $body);
