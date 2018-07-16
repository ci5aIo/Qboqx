<?php

/*
 * Used by 
   * places\views\default\display\place\details.php
   * jot\views\default\forms\jot\add.php
   * market\views\default\forms\component\add.php
 */
$jot               =       get_input('jot');                                  
foreach ($jot as $name => $value){$display .= '10 $jot['.$name.']='.$value.'<br>';
	if (is_array($value)){
		foreach($value as $key=>$value_2){$display .= '12 $jot['.$name.']['.$key.']: '.$value_2.'<br>';
		}
	}
}
$element_type      =       get_input('element_type');                         $display .= '$element_type: '.$element_type.'<br>';
$container_guid    = (int) get_input('item_guid', get_input('guid', $jot['container_guid']));
$owner_guid        = (int) get_input('owner_guid', elgg_get_logged_in_user_guid());
if (empty($owner_guid)){
	$owner_guid    = $jot['owner_guid'];}
$title             =       get_input('title', "new $element_type");
//$element_title     =       get_input('element_title', "new $element_type");
$asset             = (int) get_input('asset');
$assigned_to       =       get_input('assigned_to');
$referrer          =       get_input('referrer');
$location          = (int) get_input('location');
$action            =       get_input('action');                                  $display .= '$action: '.$action.'<br>';
               
$container         = get_entity($container_guid);                                $display .= '$container_guid: '.$container_guid.'<br>';
$owner             = get_entity($owner_guid);                                    $display .= '$owner_guid: '.$owner_guid.'<br>';

$container_state   = $container['state'];
$aspect            = get_input('aspect', $element_type) ?: $jot['aspect']; // Transfers must specify an aspect

if ($jot['do'] == elgg_echo('jot:set:schedule')){
	$que = TRUE;                                                                 $display .= 'do:Que ...<br>';
} 
if ($jot['do'] == elgg_echo('jot:save:define')){
	$define = TRUE;                                                              $display .= 'do:Define ...<br>';
}
unset($jot['do']);

if (empty($jot['guid']) || $action == 'create'){
	$new = TRUE;
}
if (!$new){
	$element = get_entity($jot['guid']);                                         $display .= '$jot[guid]: '.$jot['guid'].'<br>';
}                                                                                $display .= '$element[images][0]='.$element->images[0].'<br>';
	
//$title             = $element_title;
$access_id         = elgg_extract('access_id', $vars, get_default_access());

// Switch subtype and get configuration variables for $element_type
Switch ($aspect){
	case 'item':
		$relationship  = 'contents';
		$subtype       = 'item';
		$variables     = elgg_get_config("items");
		break;
	case 'market':
		$relationship  = 'contents';
		$subtype       = 'market';
		$variables     = elgg_get_config("market");
		break;
	case 'transfer':
		$relationship  = "transfer_{$aspect}";
		$subtype       = 'transfer';
		$item_type     = 'receipt_item';
		$variables     = elgg_get_config($relationship);
		break;
	case 'part':
		$relationship  = 'part';
		$subtype       = 'item';
		$variables     = elgg_get_config("parts");
		break;
	case 'component':
		$relationship  = 'component';
		$subtype       = 'item';
		$variables     = elgg_get_config("items");
		break;
	case 'accessory':
		$relationship  = 'accessory';
		$subtype       = 'item';
		$variables     = elgg_get_config("items");
		break;
	case 'supply':
		$relationship  = 'supply';
		$subtype       = 'item';
		break;
	case 'schedule':
		$relationship  = 'scheduled_for';
		$subtype       = 'maintenance';                             $display       .= '$relationship: '.$relationship.'<br>';
		break;
	case 'pictures':
		$images = $element->images;
		$jot_images = $jot['images'];
		if (!is_array($images)){$images = array($images);}
		if (!is_array($jot_images)){$jot_images = array($jot_images);}
		foreach($jot_images as $image_guid){
			if (!in_array($image_guid, $images)){
				$images[] = $image_guid;}}
		$element->images = $images;
		$element->save();
		goto eof;
		break;
	case 'documents':
		$documents = $jot['documents'];
		if (!is_array($documents)){$documents = array($documents);} 
		if ($documents){
			foreach($documents as $i){
				if(!check_entity_relationship($i, 'document', $container_guid)){
					add_entity_relationship($i, 'document', $container_guid);
			}}}
		goto eof;
		break;
	default:
		$relationship  = $element_type;
		$subtype       = $element_type;
		$variables     = elgg_get_config("{$subtype}s");
		break;
}                                                                   $display .= '$element_type: '.$element_type.'<br>';
                                                                    $display .= '$aspect: '.$aspect.'<br>';
if ($container->getSubtype() == 'place'){
	$location    = $container_guid;
	$parent_guid = $container_guid;
}

	elgg_make_sticky_form('jot');

if (empty($asset        )) {$asset         = $container_guid;}
if (empty($subtype      )) {$subtype       = $container->getSubtype();}
if (empty($assigned_to))   {
	if ($container->assigned_to) {
    	$assigned_to   = $container->assigned_to;
        }
    else {if($container->observer) {
    	$assigned_to   = $container->observer;
        }
        else {
        	$assigned_to   = $container->owner_guid;
		    }
	    }
   }
if (empty($observer)){
	$observer       = elgg_get_logged_in_user_entity()->guid;
}
if ($element_type != 'event'){ // temporarily exclude scheduled events from being processed
	
	$input = array();                                               $display .= 'Variable Values<br>Inputs<br>';
	foreach ($variables as $name => $type) {
		if ($name == 'title' || $name == 'entity_title') {
			$input[$name] = htmlspecialchars(get_input($name, '', false), ENT_QUOTES, 'UTF-8');
			continue;                                              
		}
		if ($type == 'tags') {
			$input[$name] = string_to_tag_array($input[$name]);
			continue;
		}
		
		$input[$name] = get_input($name);                           $display .= $name.' => '.$input[$name].'<br>';
	}
	
	if (!$container || !$container->canEdit()) {
		register_error('Invalid container');
		forward(REFERER);
	}
	if ($new){
		// Create the element
		$element                 = new ElggObject();
	}                                                               $display .= 'Input Names<br>';
	
	if (sizeof($input) > 0) {
		// don't change access if not an owner/admin
		$user = elgg_get_logged_in_user_entity();
		$can_change_access = true;
	
		if ($user) {
			$can_change_access = $user->isAdmin() || $user->getGUID() == $new_element->owner_guid;
		}
	foreach ($input as $name => $value) {
			if (($name == 'access_id' || $name == 'write_access_id') && !$can_change_access) {
				continue;
			}
			if ($name == 'parent_guid') {
				continue;
			}
			$element->$name = $value;                               $display .= "152 element[$name] => $element[$name]<br>";
		}
	}
	if ($subtype == 'task'    ){
		$element->subtype        = 'task_top';
	}
	else {
		$element->subtype        = $subtype;	                    $display .= "176 element->subtype = $element->subtype<br>";
	}
	$element->access_id      = $access_id;
// Push $jot[] to $element
	foreach ($jot as $key => $value) {                              //$display .= "172 jot[$key] => $value<br>";
		$element->$key = $value;                                    //$display .= "173 element[$key] => {$element->$key}<br>";
		
		// for display only ...
		if (is_array($value)){                                      $display .= 'Array: '.$key.'<br>';
			foreach($value as $this_key=>$this_value){              $display .= "168 jot[$this_key] => $this_value<br>";
			}
			continue;
		}                                                           //$display .= $key.'=>'.$value.'<br>';
	}
	foreach($element as $key=>$value){                              $display .= "173 element[$key] => {$element->$key}<br>";
		if (empty($element[$key])){
			unset($element[$key]);
		}
	}                                                                              
//goto eof;	
	/*
	//$new_element->subtype        = $subtype;
	$new_element->access_id      = $access_id;
	$new_element->owner_guid     = elgg_get_logged_in_user_guid();
	$new_element->container_guid = $container_guid;
	$new_element->parent_guid    = $parent_guid;
	$new_element->location       = $location;
	$new_element->asset          = $asset;
	$new_element->title          = $title;
	$new_element->assigned_to    = $assigned_to;
	$new_element->provider       = $assigned_to;
	$new_element->observer       = $observer;
	$new_element->moment = $new_element->time_created;
	$new_element->state          = '1';
	*/
	/*
	foreach($new_element as $name => $value){
		$new_elements .= $name.' => '.$value.'<br>';
	}
	register_error('$aspect: '.$aspect.'<br>
	                $title: '.$title.'<br>
	                $element_type: '.$element_type.'<br>
	                $relationship: '.$relationship);
	
	//register_error($current_variables);
	//register_error($variables_values);
	register_error($input_names);
	//register_error($inputs);
	register_error($new_elements);
	*/

	if (!$element->save()) {
		register_error('Action Failed: Error creating '.$element_type);
	}
		elgg_clear_sticky_form('jot');
	
		// Now save description as an annotation
		$element->annotate("$subtype", $element->description, $element->access_id);
	
		if ($new) {
			$options = array('action_type' => 'create', 
					         'subject_guid' => elgg_get_logged_in_user_guid(), 
					         'object_guid' => $element->guid,
					         'target_guid' => $container_guid,
					        );
			if     (elgg_view_exists("river/object/$subtype/create")){
				$options['view'] = "river/object/$subtype/create";
			}
			elseif (elgg_view_exists("river/object/$aspect/create")){
				$options['view'] = "river/object/$aspect/create";
			}
			else {
				$options['view'] = "river/object/jot/create";
			}
			elgg_create_river_item($options);
		}
	
	// Create the relationship
	add_entity_relationship($element->guid, $relationship, $container_guid);
	
	// Create new line item for transfer receipt element.  Link item to receipt item. Link receipt item to transfer item. 
	if ($element_type == 'transfer' && $aspect == 'receipt'){
		$line_item             = new ElggObject();
		$line_item->title      = $container->title;
		$line_item->subtype    = $item_type;
		$line_item->qty        = 1;
		$line_item->sort_order = 1;
		if ($line_item->save()){
			elgg_clear_sticky_form('jot');
		}
		else {
			register_error(elgg_echo('jot:save:failure'));
		}
		
		$guid_one     = $container_guid;       // item
		$guid_two     = $line_item->getGUID(); // receipt item
		$guid_three   = $element->guid;        // transfer item
		$relationship = 'receipt_item';
	
		// Link item to receipt item	
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}
		// Link receipt item to transfer item
		if(!check_entity_relationship($guid_two, $relationship, $guid_three)){
			add_entity_relationship($guid_two, $relationship, $guid_three);
		}
	} //@END - if ($element_type == 'transfer' && $aspect == 'receipt')
} //@END - if ($element_type != 'event')
if ($aspect == 'schedule'){
	// Create the element
	if ($new){
		$element                 = new ElggObject();
	}
	foreach($jot as $name => $value){
		if (empty($value) && $name != 'last_done'){
			if ($new && $name == 'guid'){
				continue;
			}
			$empty_values = TRUE;
		}
		$element->$name = $value;                                       $display .= $name.'=>'.$element->$name.'<br>';
	}
	if ($empty_values){
 		register_error('Save Failed. Required fields not provided.');
	}
	else { // Not $empty_values
		$element->save();
	    elgg_clear_sticky_form('jot');
	    
		$guid_one     = $element->guid;        // scheduled maintenance event
		$guid_two     = $container_guid;       // item
	
		// Link item to receipt item	
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
	 	}
	}
	if ($que){
		forward("que/set/$element->guid/Schedule");
	}
}//@END - if ($aspect == 'schedule')
if ($define){
	// forward to the appropriate edit form
	$path = elgg_get_site_url() . "jot/edit/$element->guid";
	forward($path);
}	

eof:
//register_error($display);