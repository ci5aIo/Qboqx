<!-- Page: tasks\pages\que\set.php--> 
<?php
/**
 * View a schedule task
 *
 * @package ElggTasks
 */

$schedule_guid = get_input('guid');
$section = get_input('section');
$schedule = get_entity($schedule_guid);
$owner = elgg_get_logged_in_user_entity();
if (!$schedule) {
	forward();
}
$selected = $vars['this_section'];
$item_guid = $vars['guid'];

// View: \mod\quebx\views\default\quebx\menu.php
$tabs = elgg_view('quebx/menu', array('guid' =>$schedule_guid, 'this_section' => $section, 'action'=>'display'));
if ($tabs){$tabs_state = 'set';}
else {$tabs_state = 'not set';}

elgg_set_page_owner_guid($schedule->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
}

$title = $schedule->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "tasks/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "tasks/owner/$container->username");
}
tasks_prepare_parent_breadcrumbs($schedule);
elgg_push_breadcrumb($title);

$add_comment = true;
$params = array("class"=>"jot_input");
$content = elgg_view('object/que', array('entity'=>$schedule, 'full_view' => true, 'this_section'=>$section));
//$content = elgg_view_list_item($schedule, array('full_view' => true, 'this_section'=>$section));
// Don't show the jot box
//$content .= elgg_view_comments($schedule, $add_comment, $params);

$sidebar = elgg_view('tasks/sidebar/navigation', array('owner'=>$owner));

$body = elgg_view_layout('content', array(
//$body = elgg_view_layout('one_sidebar', array(
	'filter'  => $tabs,
	'content' => $content,
	'title'   => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page('maint: '.$title, $body);
