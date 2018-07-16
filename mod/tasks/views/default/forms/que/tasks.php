<?php
/**
 * QuebX pick tasks form
 * Used in:
 	* 
 **/

$access_id          = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$item_guid          = elgg_extract('item_guid', $vars, null);
$pick_type          = elgg_extract('pick_type', $vars, null);
$selection_type     = (int) elgg_extract('selection_type', $vars, 2);
$group_subtype      = elgg_extract('group_subtype', $vars, null);
$container_guid     = elgg_extract('container_guid', $vars, null);
$submit_label       = elgg_echo('Pick');
$owner              = elgg_get_page_owner_entity();
$item               = get_entity($item_guid);
if ($owner){
	$owner_guid     = $owner->getguid();}
else {
	$owner_guid     = elgg_get_logged_in_user_guid();}
if ($item){
	$element_type   = $item->getSubtype();
}
$manage_groups = elgg_view('output/url', array('text' => 'Manage groups','href' =>  "groups/all?filter=alpha"));					

$scheduled_tasks = elgg_get_entities_from_relationship(array(
		'relationship'         => 'que',
		'relationship_guid'    => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));
/*
$tasks = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'task_top',
	'owner_guid' => $owner_guid,
	'no_results' => elgg_echo('tasks:none'),
));
*/
	$tasks = elgg_get_entities(array(
		'type'       => 'object',
		'subtype'    => 'task_top',
 		'owner_guid' => $owner_guid,
		'limit'      => false,
	));


$selected_support_groups = elgg_get_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'support_group_of',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));
$selected_suppliers = elgg_get_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'supplier_of',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));

$selected_task = $scheduled_tasks[0];
if ($selected_task) {
	$url = $selected_task->getURL();
	$relationship = 'schedule';
}

/*
echo '$selection_type'.$selection_type;
echo '$tasks[0][name]'.$tasks[0]['name'];
echo '<br>$selected_task[name]: '.$selected_task['name'];
echo '$url: '.$url;
*/
elgg_load_css('jquery.treeview');

Switch ($pick_type) {
	case 'support':
		$relationship = 'support_group_of';
		break;
	case 'supplier':
		$relationship = 'supplier_of';
		break;
	case 'task':
		$relationship = 'que';
		break;
}

switch ($selection_type){
	case 1:
		$selection = 'single';
		break;
	case 2:
		$selection = 'multiple';
		break;
	default:
		$selection = 'multiple';
		break;
}

$containers     = $tasks;
//$containers     = elgg_get_entities(array('type'=>'group', 'owner_guid' => $owner_guid, ));

$current_groups = array_merge($tasks, get_item_group_collections($item_guid, $relationship));

foreach ($scheduled_tasks as $i){
//foreach ($current_groups as $i){
	$current_guids[] = $i->getguid();
}

echo elgg_view('input/hidden', array('name' => 'pick[pick_type]'     , 'value' => $pick_type    ));
echo elgg_view('input/hidden', array('name' => 'pick[group_subtype]' , 'value' => $group_subtype ));
echo elgg_view('input/hidden', array('name' => 'pick[element_type]'  , 'value' => $element_type  ));
echo elgg_view('input/hidden', array('name' => 'pick[access_id]'     , 'value' => $access_id     ));
echo elgg_view('input/hidden', array('name' => 'pick[item_guid]'     , 'value' => $item_guid     ));
echo elgg_view('input/hidden', array('name' => 'pick[container_guid]', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'pick[owner_guid]'    , 'value' => $owner_guid    ));

//echo elgg_dump($containers);
//echo '$item_guid='.$item_guid;
// echo '$element_type: '.$element_type.'<br>';
// echo '$selection: '.$selection.'<br>';

// Build the group tree
	$group_ids = array();
	$subgroup_ids = array();
	$top_containers = array();
	$containers = array();
	$depths = array();
	$existing_groups = array();
	

	$groups = $tasks;

	foreach ($tasks as $group){
		$group_ids[] = $group['guid'];
	};
	$subtasks = elgg_get_entities(array(
				'type'        => 'object',
				'subtype'    => 'task',
				'owner_guid' => $owner_guid,
			));
	foreach ($subtasks as $subtask){
		$subtask_ids[] = $subtask['guid'];
	};
	 
	$top_container_ids = array_diff($group_ids, $subtask_ids);
	foreach ($top_container_ids as $id){
		$top_containers[] = get_entity($id);
	}
	foreach ($top_containers as $container) {
		$containers[] = array(
			'guid'  => $container->getGUID(),
			'title' => $container->title,
			'url'   => $container->getURL(),
			'depth' => 0,
		);
		$depths[$container->guid] = 0;

		$stack = array();
		array_push($stack, $container);
		while (count($stack) > 0) {
			$parent = array_pop($stack);

			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type'           => 'object',
				'subtype'        => 'task',
				'metadata_name'  => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				$containers[] = array(
					'guid' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_guid' => $parent->getGUID(),
					'depth' => $depths[$parent->guid] + 1,
				);
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
			}
		}
	}
	
if ($containers) {
/*
	usort($containers, function($a, $b) {
		return strnatcmp(strtolower($a->title), strtolower($b->title));	
	});
*/
	anatcasesort($containers,"title");
	$sort_order = (int) '';

//	echo elgg_dump($containers);
	foreach ($containers as $container) {
		    $this_container_guid = $container['guid'];
			$sort_order          = $sort_order + 1;
			$link                = elgg_view('output/url', array(
								      'text' => $container['title'],
								      'href' =>  "tasks/view/$this_container_guid"));
			$label               = "<label style='font-weight:normal;font-size:100%;' for='group$this_container_guid'>$link</label>";
			
			if (in_array($this_container_guid, $current_guids)){
				$input_radio    = "<input type='radio' name='pick[selected_tasks][]' checked='checked' value=$this_container_guid>";
				$input_checkbox = elgg_view('input/checkbox', array(
									   'id'=>"group$this_container_guid", 
									   'value'=>$this_container_guid,
									   'name'=>'pick[selected_tasks][]',
									   'checked' => 'checked', 
									   ));
			}
			else {
				$input_radio    = "<input type='radio' name='pick[selected_tasks][]' value=$this_container_guid>";
				$input_checkbox = elgg_view('input/checkbox', array(
									   'id'=>"task$this_container_guid", 
									   'value'=>$this_container_guid,
									   'name'=>'pick[selected_tasks][]',
									   ));
			}

			$radio    = $input_radio   .$label;
			$checkbox = $input_checkbox.$label;
			
		elgg_register_menu_item('containers_checkboxes', array(
			'name' => $this_container_guid,
			'text' => $checkbox,
		    'href' => false,
			'parent_name' => $container['parent_guid'],
			'sort_order' => $sort_order,
		));
		elgg_register_menu_item('containers_radio', array(
			'name' => $this_container_guid,
			'text' => $radio,
			'href' => false,
			'parent_name' => $container['parent_guid'],
			'sort_order' => $sort_order,
		));
		elgg_register_menu_item('groups_pick_default', array(
			'name' => $this_container_guid,
			'text' => $radio.$input.$container['title'],
			'href' => false,
			'parent_name' => $container['parent_guid'],
		));
	}
}

//echo elgg_view_module('aside', $title, $content);
echo '<div>';

$content_checkboxes = elgg_view_menu('containers_checkboxes', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
if (!$content_checkboxes) {
	$content_checkboxes = '<p>' . elgg_echo('groups:none') . '</p>';
}
$content_radio = elgg_view_menu('containers_radio', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
if (!$content_radio) {
	$content_radio = '<p>' . elgg_echo('groups:none') . '</p>';
}
$content_pick_default = elgg_view_menu('groups_pick_default', array('class' => 'containers-nav'));
if (!$content_pick_default) {
	$content_pick_default = '<p>' . elgg_echo('groups:none') . '</p>';
}
//$content - $content_pick_default;
if ($selection == 'single'){
	$content .= $content_radio;
}
if ($selection == 'multiple'){
	$content .= $content_checkboxes;
}

//$content .= "</div>";
echo elgg_view_module('aside', $title, $content);
//echo elgg_view_module('info', $title, $content);
//echo elgg_view_module('aside', $title, $content_radio);

?>
<script>
require(['jquery', 'jquery.treeview'], function($) {
	$(function() {
		$(".containers-nav").treeview({
			persist: "group",
			collapsed: true,
			unique: true
		});

<?php if ($selected_task) { ?>
		// Manually select the correct menu item
		// code taken from the jquery.treeview library
		var current = $(".containers-nav a[href='<?php echo $url; ?>']");
		var items = current.addClass("selected").parents("ul, li").add( current.next() ).show();
		var CLASSES = $.treeview.classes;
		items.filter("li")
			.swapClass( CLASSES.collapsable, CLASSES.expandable )
			.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
				.find(">.hitarea")
					.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
					.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea );
<?php } ?>
	});
});
</script>
<?php

echo elgg_view('input/submit', array('value' => $submit_label));
echo '</div>';