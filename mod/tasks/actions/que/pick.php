<?php
/**
 * QuebX pick elements action
 *
 * Used by: 
 *        :
 */

/* Pseudocode
 * Receive element_type.
 * Receive item_guid
 * if item_guid is empty
	* set item_guid to 0  
 * Receive container_guid
 * If container_guid is empty
 	* set container_guid to item_guid 
 * Receive selected groups
 * Determine type of container
 * If container is a transfer item
	 * Create new receipt item
	 * Create new link between receipt item and selected item
 * If container is a receipt item
	 * Delete existing link between receipt item and selected item
	 * Create new link between receipt item and selected item
 */

// Get variables
$pick             = get_input('pick');
/******************/
$element_type     = $pick[element_type];
$item_guid        = $pick[item_guid]; if (empty($item_guid)){$item_guid = 0;}
$pick_type        = $pick[pick_type]; if (empty($pick_type)){$pick_type = 'task';}
$group_subtype    = $pick[group_subtype];
$access_id        = $pick[access_id];
$owner_guid       = $pick[owner_guid]; if (empty($owner_guid)){$owner_guid = elgg_get_logged_in_user_guid();}
$selected         = $pick[selected_tasks];
/******************/
$item             =        get_entity($item_guid);
if ($item_guid != 0) {
	$item_type        =    $item->getSubtype();
}

$display  = '$group_subtype: '.$group_subtype.'<br>';
$display .= '$pick_type: '.$pick_type.'<br>';
$display .= '$item_guid: '.$item_guid.'<br>';
$display .= '$element_type: '.$element_type.'<br>';


Switch ($pick_type) {
	case 'task':
		$relationship = 'que';
		break;
	default:
		$relationship = 'que';
		break;
}

$inverse_relationship=false;
$relationships = get_entity_relationships($item_guid, $inverse_relationship);
	
/**
 * Updates the task associations of a schedule
 * @param type $item
 * @param type $selected_tasks
 * @param type $existing_tasks
 * @param type $relationship
 */
function collections_update($item, $selected, $existing, $relationship) {
$display .= 'collections_update ...<br>';	
	$add = array_diff($selected, $existing);
	$rem = array_diff($existing, $selected);
$display .= '$relationship: '.$relationship.'<br>';
	
	$add      = array_unique($add);
	$add      = array_values($add);
	$rem      = array_unique($rem);
	$rem      = array_values($rem);
	$guid_two = $item->getguid();
	
	foreach($add as $i){
		$guid_one = $i;
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}		
	}
	foreach($rem as $i){
		$guid_one = $i;
		remove_entity_relationship($guid_one, $relationship, $guid_two);
	}
}

if ($element_type == 'maintenance'){
	// remove empty elements
//	$selected_tasks = array_diff($selected_tasks , array("0"));
$display .= '$selected: '.$selected[0].'<br>';	
	$existing = elgg_get_entities_from_relationship(array(
			'relationship'         => $relationship,
			'relationship_guid'    => $item_guid,
			'inverse_relationship' => true,
//			'relationship_join_on' => 'guid',
	));
	
	if ($existing){
		foreach($existing as $i){
			$existing[] = $i->getguid();
$display .= '$existing[]: '.$i->getguid().'<br>';
		}
	}
//	collections_update($item, $selected, $existing, $relationship);
$display .= 'collections_update ...<br>';	
	$add = array_diff($selected, $existing);
	$rem = array_diff($existing, $selected);
$display .= '$relationship: '.$relationship.'<br>';
	
	$add      = array_unique($add);
	$add      = array_values($add);
	$rem      = array_unique($rem);
	$rem      = array_values($rem);
	$guid_two = $item->getguid();
$display .= '$guid_two: '.$guid_two.'<br>';
	
	foreach($add as $i){
		$guid_one = $i;
$display .= '$guid_one: '.$guid_one.'<br>';
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}		
	}
	foreach($rem as $i){
		$guid_one = $i;
		remove_entity_relationship($guid_one, $relationship, $guid_two);
	}
}

//register_error($display);
