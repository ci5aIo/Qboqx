Page: mod\tasks\pages\tasks\view.php
<?php
/**
 * View a single task
 *
 * @package ElggTasks
 */

$task_guid = get_input('guid');
$action    = get_input('action');
$task      = get_entity($task_guid);
$owner     = elgg_get_logged_in_user_entity();
if (!$task) {
	forward();
}
if (empty($action)){$action = 'view';}
$selected  = $vars['this_section'];
$item_guid = $vars['guid'];

// View: \mod\quebx\views\default\quebx\menu.php
$tabs = elgg_view('quebx/menu', array('guid' =>$task_guid));
//$tabs = elgg_view('quebx/menu', array('guid' =>$task_guid, 'this_section' => $section));

elgg_set_page_owner_guid($task->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
}

$title = $task->title;
tasks_prepare_parent_breadcrumbs($task, $container);
elgg_push_breadcrumb($title);

$add_comment = true;
$params = array("class"=>"jot_input");
if ($action == 'view'){
    $task->view = 'object/task_top';
    $content .= elgg_view_list_item($task, array('full_view' => true));
    //$content = elgg_view_list_item($task, array('full_view' => true, 'this_section'=>$section));
    //$content = elgg_view_entity($task, array('full_view' => true));
    if ($owner->guid == $task->getOwnerGuid()) {
    	$url = "tasks/add/$task->guid";
/*    	elgg_register_menu_item('title', array(
    			'name'       => 'subtask',
    			'href'       => $url,
    			'text'       => elgg_echo('tasks:newchild'),
    			'link_class' => 'elgg-button elgg-button-action',
    	));*/
    }
}
if ($action == 'edit'){
    $content .= elgg_view_list_item($task, array('full_view' => true, 'action'=>$action, 'view'=>'task'));
}

$content .= elgg_view_comments($task, $add_comment, $params);

$sidebar = elgg_view('tasks/sidebar/navigation', array('owner'=>$owner));


$body = elgg_view_layout('one_sidebar', array(
	'filter'  => $tabs,
	'content' => $content,
	'title'   => $title,
	'sidebar' => $sidebar,
//	'sidebar_alt' => elgg_view('tasks/sidebar/navigation'),
));

echo elgg_view_page($title, $body);
