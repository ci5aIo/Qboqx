<?php
/**
 * Create or edit an observation
 *
 * @package ElggPages
 */
$testing          = false;

// Get guids
$observation_guid = (int)get_input('observation_guid');
$container_guid   = (int)get_input('container_guid');
$parent_guid      = (int)get_input('parent_guid');
$referrer         = get_input('referrer');
// Receive research efforts
$discoveries        = get_input('discovery');
$jot              = get_entity($observation_guid);

elgg_make_sticky_form('observation');
 
//$variables    = jot_prepare_brief_view_vars('observation', $observation_guid, null, null);
$variables = elgg_get_config('observations');

$input = array();
$display = 'Input<br>';
$display .= '$observation_guid: '.($observation_guid).'<br>';
$display .= '$container_guid: '.($container_guid).'<br>';
$display .= '$parent_guid: '.($parent_guid).'<br>';
$display .= '$jot->title: '.$jot->title.'<br>';
$display .= '$jot->guid: '.$jot->getguid().'<br>';

foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	
//	$display .= $name.': '.$input[$name].'<br>';
	
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}


if (!$input['title']) {
	register_error(elgg_echo('observations:error:no_title'));
	continue;
}

if ($observation_guid) {
	$observation = get_entity($observation_guid);
	if (!$observation || !$observation->canEdit()) {
		register_error(elgg_echo('observations:error:no_save'));
	}
	$new_observation = false;
} else {
	$observation = new ElggObject();
	$observation->subtype = 'observation';
	$new_observation = true;
}
if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$observation->$name = $value;
	}

	// Process research efforts
	  // Pivot line items
	if (sizeof($discoveries) > 0) {                     		$display .= 'Efforts<br>';
		$relationship = 'discovery_effort';
		foreach ($discoveries as $key=>$values){
		  	foreach($values as $key_value=>$value){
		  		$line_items[$key_value][$key] = $value;
			  }
		  }
		// Remove blank Line Items
		foreach ($line_items as $key=>$values){      	  		$display .= '$key: '.$key.'<br>';
		  	foreach($values as $key_value=>$value){		  		$display .= $key_value.': '.$value.'<br>';
		  		if ($key_value == 'action' && $value == ''){
					unset($line_items[$key]);
				}
			 }
		}
	}
	  $item_qty=0;
	  foreach ($line_items as $key=>$values){
	  	$item_qty += $values['qty'];

	  	foreach($values as $key_value=>$value){
	  	}
	  }
		 
	 // Process live actions
                                                            $display .= '<br>Live Actions<br>';
	  foreach ($line_items as $line => $values) {
	  	unset($line_item_guid);
	  	$line_item_guid = $values['guid'];

	  	unset($line_item);
  		$line_item = get_entity($line_item_guid);
	  	if (empty($line_item)){
				$line_item = new ElggObject();
				$line_item->subtype = 'effort';
		 }
		 $values['sort_order'] = $line + 1;
		 $line_item->title     = 'Effort #'.($line + 1);
		 
		 foreach($values as $dimension => $value){		 	$display .= $dimension.': '.$value.'<br>';
		 	$line_item->$dimension = $value;
		 }
		 foreach ($line_item as $key => $value){		 	$display .= $key.': '.$value.'<br>';
		 }
	  }
	  if (!empty($line_item) && !$testing){
		  if ($line_item->save()){
		  	  if (check_entity_relationship($line_item->getguid(), $relationship, $observation_guid) == false){
				add_entity_relationship($line_item->getguid(), $relationship, $observation_guid);
			  }
		  }
		  else {
			register_error(elgg_echo('jot:save:failure'));
		  }
	  }
  }


  if ($testing){register_error($display);}

//@TODO Add check to make sure user can write to container
$observation->container_guid = $container_guid;

if ($parent_guid) {
	$observation->parent_guid = $parent_guid;
}

if ($observation->save()) { 

	elgg_clear_sticky_form('observation');

	// Now save description as an annotation
	$observation->annotate('observation', $observation->description, $observation->access_id);

	$projects = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'assigned_to',
		'relationship_guid' => $observation->getguid(),
	    'inverse_relationship' => true,
		'limit' => false,
	));

	if ($observation->state >= 2 && !$projects){
			$project          = new ElggObject();
			$project->title   = 'Fix: '.$observation->title;
			$project->subtype = 'task_top';
			$project->save();
			add_entity_relationship($project->getguid(), 'assigned_to', $observation->getguid());
	}


//	system_message(elgg_echo('observations:saved'));

	if ($new_observation) {
		
		elgg_create_river_item([
				'view'=>'river/object/jot/create',
				'action_type'=> 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' => $observation->guid]);
	}
} else {
	register_error(elgg_echo('observations:error:no_save'));
}

forward($referrer);