<?php
/**
 * Select groups
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

/*
 * 		case 'groups':
			gatekeeper();
			set_input('group_type'     , $page[1]);
			set_input('item_guid'      , $page[2]);
			set_input('selection_type' , $page[3]);
			set_input('group_subtype'  , $page[4]);
			set_input('container_guid' , $page[5]);
		    include "$pages/groups.php";
			break;
 */

$group_type      =       get_input('group_type');
$item_guid       = (int) get_input('item_guid');
$selection_type  = (int) get_input('selection_type');
$group_subtype   =       get_input('group_subtype');
$container_guid  = (int) get_input('container_guid');
$item            =       get_entity($item_guid);
$owner           =       elgg_get_page_owner_entity();
/*
if ($group_subtype == 'Merchant'){
	$container_guid = $item_guid;
}
*/
elgg_gatekeeper();
elgg_group_gatekeeper();

// create form
/*$action    = 'groups/add/element';
$form_vars = array('enctype'      => 'multipart/form-data', 
                   'name'         => 'group_list',
				   'action'       => "action/groups/add?element_type=supplier&item_guid=$item_guid");
$body_vars = array('item_guid'    => $item_guid,
				   'group_type'   => $group_type);

$content_flash_group = elgg_view_form($action, $form_vars, $body_vars);
*/
// create form
$form_vars = array('enctype'        => 'multipart/form-data', 
                   'name'           => 'group_list',
				   'action'         => 'action/pick');
$body_vars = array('item_guid'      => $item_guid,
				   'group_type'     => $group_type,
		           'group_subtype'  => $group_subtype,
				   'selection_type' => $selection_type,
				   'container_guid' => $container_guid,
);
if ($group_subtype){
	$title    = elgg_echo("Pick $group_subtype");
}
else {
	$title    = elgg_echo("Pick {$group_type}s");
}
$content .= elgg_view_form('market/groups', $form_vars, $body_vars);
//$content = elgg_dump($body_vars).$content;

$body     = elgg_view_layout('action', array('content' => $content,
											 'title'   => $title,
											 'filter'  => ''));
$title = '';
$header = '';
$show_inner = false;

$module_type = 'popup';

// show form 
echo elgg_view_module($module_type, $title, $body);
