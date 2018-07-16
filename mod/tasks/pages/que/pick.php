<?php
/**
 * Select tasks
 *
 * @package ElggFile
 */

//elgg_load_library('elgg:file');

/*
 * 		case 'pick':
			gatekeeper();
			set_input('pick_type'      , $page[1]);
			set_input('item_guid'      , $page[2]);
			set_input('selection_type' , $page[3]);
			set_input('group_subtype'  , $page[4]);
			set_input('container_guid' , $page[5]);
			include "$base_dir/pick.php";
			break;
 */

$pick_type       =       get_input('pick_type');
$item_guid       = (int) get_input('item_guid');
$selection_type  = (int) get_input('selection_type');
$group_subtype   =       get_input('group_subtype');
$container_guid  = (int) get_input('container_guid');
$item            =       get_entity($item_guid);
$owner           =       elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
$form_vars = array('enctype'        => 'multipart/form-data', 
                   'name'           => 'task_list',
				   'action'         => 'action/que/pick',		
//				   'action'         => 'action/pick',
);
$body_vars = array('item_guid'      => $item_guid,
				   'pick_type'      => $pick_type,
		           'group_subtype'  => $group_subtype,
				   'selection_type' => $selection_type,
				   'container_guid' => $container_guid,
);
if ($selection_type == 1){
	$title    = elgg_echo("Pick task");
	}
else {
	$title    = elgg_echo("Pick tasks");
}

$content .= elgg_view_form('que/tasks', $form_vars, $body_vars);
//$content = elgg_dump($body_vars).$content;

$body     = elgg_view_layout('action', array('content' => $content,
											 'title'   => $title,
		                                     'show_inner' => false,
));
$title = '';

$module_type = 'popup';

// show form 
echo elgg_view_module($module_type, $title, $body);
