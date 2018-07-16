<?php
/**
 * QuebX pick elements action
 *
 * Used by: market\views\default\forms\market\pick.php
 *        : market\views\default\forms\market\groups.php
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

$element_type     = $pick[element_type];
$item_guid        = $pick[item_guid]; if (empty($item_guid)){$item_guid = 0;}          $display .= '32 $item_guid: '.$item_guid.'<br>';
$container_guid   = $pick[container_guid]; if (empty($container_guid)){$container_guid = $item_guid;} $display .= '33 $container_guid: '.$container_guid.'<br>';
$group_type       = $pick[group_type]; if (empty($group_type)){$group_type = 'support';}
$group_subtype    = $pick[group_subtype];
$access_id        = $pick[access_id];
$owner_guid       = $pick[owner_guid]; if (empty($owner_guid)){$owner_guid = elgg_get_logged_in_user_guid();}
$selected_groups  = $pick[selected_groups];
$selected_characteristics  = $pick[characteristic_names];
$show_on_timeline = $pick[show_on_timeline];
$timeline_label   = $pick[timeline_label];
$add_cost_to_que  = $pick[add_cost_to_que];
$que_contribution = $pick[que_contribution]; if (empty($que_contribution)){$que_contribution = 'none';}
$retain_line_label = $pick[retain_line_label];
$link_type        =  $pick[link_type];
$distribute_freight =  $pick[distribute_freight]; if (empty($distribute_freight)){$distribute_freight = '0';}
$unpack           =  $pick[unpack]; if (empty($unpack)){$unpack='0';}
$item             =        get_entity($item_guid);                
$container        =        get_entity($container_guid);
if ($container_guid != 0) {
	$container_type   =    $container->getSubtype();
}
else {
	$container_type   =    get_input('container_type', 'transfer');
}
if ($item_guid != 0) {
	$item_type        =    $item->getSubtype();
}
if ($link_type   == 'pick link type ...'){                         //$display .= '79 $link_type: '.$link_type.'<br>';
	unset($link_type);
}
//$display .= '$retain_line_label: '.$retain_line_label.'<br>';
//$display .= '$unpack: '   .$unpack   .'<br>';
elgg_make_sticky_form('jotForm');

Switch ($group_type) {
	case 'support':
		$relationship = 'support_group_of';
		break;
	case 'supplier':
		Switch ($group_subtype){
			case 'Merchant':
				$relationship = 'merchant_of';
				break;
		default:
			$relationship = 'supplier_of';
			break;
		}
	break;
}

if (($container_type == 'transfer' || $container_type == 'receipt_item') && $container_guid != $item_guid){	
	$relationship = 'receipt_item';
}

/*if ($existing_groups){
	$existing_groups = $existing_groups->getguid();
}*/
//$element_type   = $item->getSubtype();
// register_error('$group_subtype: '.$group_subtype);
//register_error('$item_guid: '.$item_guid);
//register_error('$item_type: '.$item_type);
// register_error('$container_guid: '.$container_guid);
// register_error('$container_type: '.$container_type);
// register_error('$element_type: '.$element_type);
// register_error('$relationship: '.$relationship);
// register_error('$relationship: '.$relationship);
// register_error('$show_on_timeline: '.$show_on_timeline);

$relationships = get_entity_relationships ($item_guid, $inverse_relationship=false);
	
/**
 * Updates the group associations of an item
 * @param type $item
 * @param type $selected_groups
 * @param type $existing_groups
 * @param type $relationship
 */
function groups_collections_update($item, $selected_groups, $existing_groups, $relationship) {
	$add_groups = array_diff($selected_groups    , $existing_groups);
	$rem_groups = array_diff($existing_groups, $selected_groups);
	
	$add_groups   = array_unique($add_groups);
	$add_groups   = array_values($add_groups);
	$rem_groups   = array_unique($rem_groups);
	$rem_groups   = array_values($rem_groups);
	$guid_two     = $item->getguid();
	
	foreach($add_groups as $group){
		$guid_one = $group;
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}		
	}
	foreach($rem_groups as $group){
		$guid_one = $group;
		remove_entity_relationship($guid_one, $relationship, $guid_two);
	}
}

Switch ($element_type){
	case 'item':
		if ($container_type == 'transfer'){ // Pick started from an unsaved line item 
			// Count the existing line items to determine sort order value
			$receipt_items = elgg_get_entities_from_relationship(array(
					'type'                 => 'object',
					'relationship'         => 'receipt_item',
					'relationship_guid'    => $container_guid,
					'inverse_relationship' => true,
					'limit'                => false,
			));
			$line_items = count($receipt_items);
			
			// Define a new line item
			$line_item                    = new ElggObject();
			$line_item->subtype           = $relationship;
			$line_item->qty               = 1;
			$line_item->sort_order        = $line_items + 1;
	
			// Save the new line item
			$line_item->save();
			
			$guid_one   = $item_guid;            // item
			$guid_two   = $line_item->getGUID(); // receipt item
			$guid_three = $container_guid;       // transfer item
		}
		if ($container_type == 'receipt_item'){ // Pick started from a saved line item
			$line_item                   = get_entity($container_guid);
			$guid_one                    = $item_guid;         // item 
			$guid_two                    = $container_guid;	// receipt item
			$linked_items                = elgg_get_entities_from_relationship(array(
												'type'                 => 'object',
												'relationship'         => 'receipt_item',
												'relationship_guid'    => $container_guid,
												'inverse_relationship' => true,
												'limit'                => false,
			));
			foreach ($linked_items as $linked_item){
				if ($linked_item['guid'] != $item_guid){
					remove_entity_relationship ($linked_item['guid'], $relationship, $guid_two);
				}
			}
			
		}
	// 		register_error('$guid_one: '.$guid_one);
	// 		register_error('$relationship: '.$relationship);
	// 		register_error('$guid_two: '.$guid_two);
	// 		register_error('$guid_three: '.$guid_three);
		
			// Populate common attributes
			$line_item->title             = $item->title;
			$line_item->timeline_label    = $timeline_label;
			$line_item->show_on_timeline  = $show_on_timeline;
			$line_item->retain_line_label = $retain_line_label;
			$line_item->add_cost_to_que   = $add_cost_to_que;
			$line_item->que_contribution  = $que_contribution;
			$line_item->distribute_freight=	$distribute_freight;
			$line_item->unpack            =	$unpack;
			$line_item->link_type         = $link_type;
			$line_item->item_guid         = $item_guid;
	
			// Save the line item
			$line_item->save();
	
			// Create relationships
			if(check_entity_relationship($guid_one, $relationship, $guid_two) == false){
				add_entity_relationship($guid_one, $relationship, $guid_two);
			}
			if(check_entity_relationship($guid_two, $relationship, $guid_three) == false && !empty($guid_three)){
				add_entity_relationship($guid_two, $relationship, $guid_three);
			}
		break;
	case 'family_characteristics':                                            $display .= '203 family_characteristics input received<br>';

	//   Remove blank characteristics
		foreach ($selected_characteristics as $key=>$value){                  //$display .= '206 $key: '.$key.'<br>$value: '.$value.'<br>$characteristic_names[$key]: '.$characteristic_names[$key].'<br>';
			if ($value == '0'){                                               //$display .= '207 $value: '.$value.'<br>';
				unset($selected_characteristics[$key]);
			}													  
		}
		$characteristic_names = $container->characteristic_names;              
		if ($characteristic_names && !is_array($characteristic_names)){
			$characteristic_names = array($characteristic_names);
		}
		if ($selected_characteristics){
			if (!is_array($selected_characteristics)){
				$selected_characteristics = array($selected_characteristics);
			}
			foreach ($selected_characteristics as $characteristic){           $display .= '219 selected characteristic: '.$characteristic.'<br>';
				if (!in_array($characteristic, $characteristic_names)){
					$characteristic_names[] = $characteristic;
				}	
			}
		}
		else {
			register_error('No selected characteristics received');
		}
		
		foreach($characteristic_names as $characteristic){                $display .= '229 characteristic_name: '.$characteristic.'<br>';
		}
		$container->characteristic_names = $characteristic_names;
		$container->save();
		break;
	default:
		// remove empty elements
		$selected_groups = array_diff($selected_groups , array("0"));
		
		$existing_groups = elgg_get_entities_from_relationship(array(
				'relationship'         => $relationship,
				'relationship_guid'    => $item_guid,
				'inverse_relationship' => true,
				'relationship_join_on' => 'guid',
		));
		
		if ($existing_groups){
			foreach($existing_groups as $group){
				$existing_groups[] = $group->getguid();
			}
			if ($item_type == 'transfer'){
				// Give the transfer merchant value the merchant name in case merchant link is detached.
				$item->merchant = $group->name;
			}
		}
		
		groups_collections_update($item, $selected_groups, $existing_groups, $relationship);
		
		if ($group_subtype == 'Merchant'){
			// Make Merchant a supplier for the item being purchased
			$existing_groups = elgg_get_entities_from_relationship(array(
					'relationship'         => 'supplier_of',
					'relationship_guid'    => $container_guid,
					'inverse_relationship' => false,
					//'inverse_relationship' => true,
					'relationship_join_on' => 'guid',
			));
			
			if ($existing_groups){
				foreach($existing_groups as $group){
					$existing_groups[] = $group->getguid();
				}
				if ($item_type == 'transfer'){
					// Give the transfer merchant value the merchant name in case merchant link is detached.
					$item->merchant = $group->name;
	//			register_error('$item_type == transfer');
				}
					
			}
			
			groups_collections_update($container, $selected_groups, $existing_groups, 'supplier_of');
		
		}
		break;
}
	
// forward(REFERER);
// register_error($display);
