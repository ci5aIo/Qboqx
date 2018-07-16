<?php

$element_type      =       get_input('element_type');
$container_guid    = (int) get_input('guid');
$element_title     =       get_input('element_title', "new $element_type");
$asset             = (int) get_input('asset');
$assigned_to       =       get_input('assigned_to');
$referrer          =       get_input('referrer');

$container         = get_entity($container_guid);
$container_state   = $container->state;
$aspect            = get_input('aspect'); // Transfers must specify an aspect
$title             = $element_title;
$access_id         = elgg_extract('access_id', $vars, get_default_access());

//system_message('Container state: '.$container_state); 
/*	
if ($element_type == 'item' || $element_type == 'market') 
  {$relationship   = 'contents';}
else
  {$relationship   = $element_type;}
*/

// Switch subtype and get configuration variables for $element_type
Switch ($element_type){
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
	default:
		$relationship  = $element_type;
		$subtype       = $element_type;
		$variables     = elgg_get_config("{$subtype}s");
		break;
}

if ($container->getSubtype() == 'place'){
	$location    = $container_guid;
	$parent_guid = $container_guid;
}

	elgg_make_sticky_form('jot');

/*
  if ($element_type == 'accessory'){
      $subtype = $container->getSubtype();
  }else {
  	  $subtype = $element_type;
  }
*/
if (empty($asset        )) {$asset         = $container_guid;}
//if (empty($element_title)) {$element_title = "new $element_type";}
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
	$observer       = elgg_get_logged_in_user_entity()->name;
}

$input = array();
foreach ($variables as $name => $type) {
	if ($name == 'title' || $name = 'entity_title') {
		$input[$name] = htmlspecialchars(get_input($name, '', false), ENT_QUOTES, 'UTF-8');
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	} else {
		$input[$name] = get_input($name);
	}
}

if (!$container || !$container->canEdit()) {
	register_error('Invalid container');
	forward(REFERER);
}

// Create the element
$new_element                 = new ElggObject();
if ($subtype == 'task'    ){
	$new_element->subtype        = 'task_top';
}
else {
	$new_element->subtype        = $subtype;	
}
if (sizeof($input) > 0) {
	// don't change access if not an owner/admin
	$user = elgg_get_logged_in_user_entity();
	$can_change_access = true;

	if ($user) {
		$can_change_access = $user->isAdmin() || $user->getGUID() == $page->owner_guid;
	}
	
	foreach ($input as $name => $value) {
		if (($name == 'access_id' || $name == 'write_access_id') && !$can_change_access) {
			continue;
		}
		if ($name == 'parent_guid') {
			continue;
		}

		$new_element->$name = $value;
	}
}

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
$new_element->observation_date = $new_element->time_created;
$new_element->state          = '1';

if (!$new_element->save()) {
	register_error('Action Failed: Error creating '.$element_type);
}
	elgg_clear_sticky_form('jot');

	$element = get_entity($new_element->guid);

	// Now save description as an annotation
	$element->annotate("$subtype", $element->description, $element->access_id);

	if ($new_element) {
		add_to_river("river/object/$subtype/create", 'create', elgg_get_logged_in_user_guid(), $element->guid);
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
}