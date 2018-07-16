<?php
/**
 * Create or edit a task
 *
 * @package ElggTasks
 */

$que          =      get_input('que');
$item         =      get_input('item');
$upload_guids =      get_input('upload_guids', array());

$album_guid   = (int) get_input('upload_to_album');                           $display .= '12 $album_guid: '.$album_guid.'<br>'; 
$unlink       =       get_input('unlink');
$referrer     =       get_input('referrer');
if ($upload_guids && !is_array($upload_guids)){$upload_guids = array();}
if ($unlink       && !is_array($unlink)      ){$unlink = array();}

$que_guid     = $que['guid'];
$album        = get_entity($album_guid);

if ($que_guid){$new = FALSE;}
else          {$new = TRUE;}

// Handle gallery images
	$item_images = $item['images']; 
	if (!is_array($item_images)){array($item_images);}
	$item_images = array_unique($item_images);
	foreach ($item_images as $this_key=>$guid){                                   $display .= '$item_images['.$this_key.'] => '.$guid.'<br>';
		$image = get_entity($guid);                                   $display .= '29 $image->guid:'.$image->guid.'<br>'; 
		if (!elgg_instanceof($image)) {                               $display .= '30 !elgg_instanceof($image['.$guid.'])<br>';
			continue;
		}
		$image_guids[] = $guid;
	}
	foreach ($unlink as $this_key=>$guid){                                        $display .= '$unlink['.$this_key.'] => '.$guid.'<br>';
	}
	if (!empty($unlink)){
	    $remaining = array_diff ($image_guids, $unlink);
//		$remaining = array_diff ($item_images, $unlink);
	}
	else {$remaining = $image_guids;}
	
	foreach ($remaining as $this_key=>$guid){                                     $display .= '$remaining['.$this_key.'] => '.$guid.'<br>';
	}
	$item['images'] = $remaining;
	$images         = $remaining; 

	if ($upload_guids) {
		
		$metadata = elgg_get_metadata(array(
		'guid' => $album->guid,
		'limit' => 0
			));
	
		// for display only ...
		foreach($upload_guids as $key => $values){                             $display .= '156 ['.$key.']=>'.$values.'<br>';
			foreach ($values as $this_key => $value){                          $display .= '157 ['.$this_key.']=>'.$value.'<br>';
				$upload_file = get_entity($value);
				foreach ($upload_file as $key => $value){                      $display .= '159 ['.$key.']=>'.$value.'<br>';
				}
			}
		}
		
		foreach($upload_guids as $key=>$value){                               $display .= '164 $key:['.$key.']<br>';
			foreach($value as $this_key=>$upload_guid){	                      $display .= '$this_key=>$this_value: ['.$this_key.']=>'.$this_value.'<br>'; 
				$images[] = $upload_guid;                                     $display .= '167 $upload_guid:'.$upload_guid.'<br>';
				
				$image = get_entity($upload_guid);                            $display .= '169 $image->guid:'.$image->guid.'<br>'; 
				if (!elgg_instanceof($image)) {                               $display .= '170 !elgg_instanceof($image)<br>';
					continue;
				}
				$image->container_guid = $album->guid; // in case these were uploaded with filedrop
				
				if (!$image->title) {
					 $image->title = $image->originalfilename;
				}
				$image->access_id = $album->access_id;
	
				foreach ($metadata as $md) {
					$names[] = $md->name;
				}
		
				$names = array_unique($names);
		
				foreach ($names as $name) {
					$image->$name = $album->$name;
				}
		
				if ($image->save()) {                                                 $display .= '190 image saved: '.$upload_guid.'<br>';
				}
			}
		}			
	} //END - if ($upload_guids)

	if (!is_array($images)){$images = array($images);}
	$item['images'] = $images; 
	foreach ($item['images'] as $this_key=>$guid){                                   $display .= '184 $item_images['.$this_key.'] => '.$guid.'<br>';
	}
//END - Handle gallery images
	
if (!$new){
	$element  = get_entity($que['guid']);
}
else {
	$element  = new ElggObject();
}
elgg_make_sticky_form('que');

	foreach($que as $name => $value){
		$element->$name = $value;
	}
	foreach($item as $name => $value){
		$element->$name = $value;
	}
	
	if($element->save()){
	    elgg_clear_sticky_form('que');
	    
	    elgg_create_river_item([
	    		'view'=>'river/object/que/edit',
	    		'action_type'=>  'edit',
	    		'subject_guid' => elgg_get_logged_in_user_guid(),
	    		'object_guid' => $que_guid]);
	    forward($referrer);
	}

/*
$variables = elgg_get_config('tasks');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}


// Get guids
$task_guid = (int)get_input('task_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('task');

if (!$input['title']) {
	register_error(elgg_echo('tasks:error:no_title'));
	forward(REFERER);
}

if ($task_guid) {
	$task = get_entity($task_guid);
	if (!$task || !$task->canEdit()) {
		register_error(elgg_echo('tasks:cantedit'));
		forward(REFERER);
	}
	$new_task = false;
} else {
	$task = new ElggObject();
	if ($parent_guid) {
		$task->subtype = 'task';
	} else {
		$task->subtype = 'task_top';
	}
	$new_task = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$task->$name = $value; echo $name.',';
	}
}

// need to add check to make sure user can write to container
$task->container_guid = $container_guid;

if ($parent_guid) {
	$task->parent_guid = $parent_guid;
}

if ($task->save()) { 

	elgg_clear_sticky_form('task');

	// Now save description as an annotation
	$task->annotate('task', $task->description, $task->access_id);

	system_message(elgg_echo('tasks:saved'));

	if ($new_task) {
		add_to_river('river/object/task/create', 'create', elgg_get_logged_in_user_guid(), $task->guid);
	}

//	forward($task->getURL());
} else {
	register_error(elgg_echo('tasks:notsaved'));
//	forward(REFERER);
}*/
echo register_error($display);