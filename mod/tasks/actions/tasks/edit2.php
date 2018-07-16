<?php
/**
 * Create or edit a task
 *
 * @package ElggTasks
 */

$input          =      get_input('task');                 $display .= '$input:'.$input.'<br>'; 
$jot            =      get_input('jot');                  $display .= '$jot[do]:'.$jot['do'].'<br>';
$task_guid      = (int) $input['guid'];                   $display .= '$task_guid:'.$task_guid.'<br>';
$task_steps_raw =      get_input('process_step');
$parts_raw      =      get_input('parts');
$task           =      get_entity($task_guid);
$container_guid = $task->container_guid ?: (int)get_input('container_guid');       $display .= '$container_guid:'.$container_guid.'<br>';
$parent_guid    = $task->parent_guid    ?: (int)get_input('parent_guid');
$element_type   =      get_input('element_type');
$aspect         =      get_input('aspect');

//$task_steps_raw = $jot['process_step'];                      $display .= '$task_steps_raw: '.$task_steps_raw.'<br>';
$container      = get_entity($container_guid);
elgg_make_sticky_form('task');

if (empty($input['title'])) {
	register_error(elgg_echo('tasks:error:no_title'));
	forward(REFERER);
}

if (empty($parent_guid) && ($container->getSubtype() == 'task_top' || $container->getSubtype() == 'task')){
    $parent_guid = $container_guid;
}
if (!empty($task_guid)) {
	$new_task = false;                                                                   
} else {
    $new_task = true;
}
//goto eof;

if (!empty($task_steps_raw)){                                              //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>';
    foreach ($task_steps_raw as $field=>$values){
        foreach($values as $key=>$value){
            $task_steps[$key][$field] = $value;
        }
    }                                                                 //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>';
    foreach ($task_steps as $key=>$task_step){
        if (empty($task_step['title'])){
            unset($task_steps[$key]);
        }
    }
    $task_steps = array_values($task_steps);                           //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>';
}
if (!empty($task_steps)){                                              //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>'; goto eof;
    // Convert the subtype of the parent task to 'task' if it was previously a 'process_step' but it now has steps of its own
    if (!$new_task) {                                                 //$display .= 'subtype: '.$task->getSubtype().'<br>'; goto eof;
	    if ($task->getSubtype() == 'process_step'){
            $subtype_id = (int)get_subtype_id('object', 'task');
            $db_prefix  = elgg_get_config('dbprefix');
            
            update_data("UPDATE {$db_prefix}entities
                         SET subtype = $subtype_id WHERE guid = $task_guid");
	    }
    }
    foreach ($task_steps as $key=>$task_step){                           //$display .= '$task_step:'.$task_step.'<br>';
        if (empty($task_step['title'])){
            continue;
        }
        foreach($task_step as $key1=>$step){                             //$display .= '$task_steps['.$key.']['.$key1.'] = '.$step.'<br>';
            
        }
        if (empty($task_step['guid'])){
            $step              = new ElggObject();
            $step->subtype     = 'process_step';
            $step->owner_guid = elgg_get_logged_in_user_guid();
            $step->access_id  = get_default_access();
        }
        else {
            $step                 = get_entity($task_step['guid']);
        }
        $step->title = $task_step['title'];                             //$display .= '$step->title: '.$step->title.'<br>';
        $step->sort_order = $key+1;                                     //$display .= '$step->sort_order: '.$step->sort_order.'<br>';
        $step->parent_guid = $task_guid;                                //$display .= '$step->guid: '.$step->guid.'<br>';
        $step->container_guid = $task_guid;                             //$display .= '$step:<br>'.print_r($step, true).'<br>';

        if ($step->save()) { 
       		
       		elgg_create_river_item([
       				'view'=>'river/object/task/create',
       				'action_type'=> 'create',
       				'subject_guid' => elgg_get_logged_in_user_guid(),
       				'object_guid' => $step->guid]);
       		
        	$success = true;
        }                 
   }
}
else {
    // If all steps have been removed from this task, convert this task to a step itself
    if (!empty($parent_guid)){
        $parent = get_entity($parent_guid);
        if ($parent->getSubtype() == 'task'){
            $subtype_id = (int)get_subtype_id('object', 'process_step');
            $db_prefix  = elgg_get_config('dbprefix');
            
            update_data("UPDATE {$db_prefix}entities
                         SET subtype = $subtype_id WHERE guid = $parent_guid");
    	}
    }
}
/******************************/
if (!empty($parts_raw)){                                              //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>';
    $asset                = $parts_raw['asset'];
    foreach ($parts_raw as $field=>$values){
        foreach($values as $key=>$value){
            $parts[$key][$field] = $value;
        }
    }                                                                 //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>';
    foreach ($parts as $key=>$part){
        if (empty($part['title'])){
            unset($parts[$key]);
        }
    }
    $parts = array_values($parts);                                //$display .= '$parts:<br>'.print_r($parts, true).'<br>';
}
if (!empty($parts)){                                              //$display .= '$task_steps:<br>'.print_r($task_steps, true).'<br>'; goto eof;
    foreach ($parts as $line=>$part_item){
        if (empty($part_item['title']) || $part_item['qty'] == 0){
            continue;
        }
        if (empty($part_item['guid'])){
            $part             = new ElggObject();
            $part->subtype    = 'part_item';
            $part->owner_guid = elgg_get_logged_in_user_guid();
            $part->access_id  = get_default_access();
        }
        else {
            $part                 = get_entity($part_item['guid']);
        }
        $part->title          = $part_item['title'];                   $display .= '$part->title: '.$part->title.'<br>';
        $part->sort_order     = $line+1;                                $display .= '$part->sort_order: '.$part->sort_order.'<br>';
        $part->asset          = $asset;                                $display .= '$part->asset: '.$part->asset.'<br>';
        $part->item_guid      = $part_item['item_guid'];// id of part from inventory
        $part->qty            = $part_item['qty'];                     $display .= '$part->subtype: '.$part->getsubtype().'<br>';
        $part->container_guid = $task_guid;                            //$display .= '$step:<br>'.print_r($step, true).'<br>';
        $part->save();
   }
}
/******************************/

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
	    if (empty($value)){continue;}
		$task->$name = $value;                                           $display.= $name.':'. $task->$name.'<br>';
	}
}

//@TODO Add check to make sure user can write to container
$task->container_guid = $container_guid;

if (!empty($parent_guid) || $parent_guid != $task_guid) {
	$task->parent_guid = $parent_guid;
}
//goto eof;
if ($task->save()) { 

	elgg_clear_sticky_form('task');

	// Now save description as an annotation
	$task->annotate('task', $task->description, $task->access_id);

	system_message(elgg_echo('tasks:saved'));

	if ($new_task) {
		
		elgg_create_river_item([
				'view'=>'river/object/task/create',
				'action_type'=> 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' => $task->guid]);
	}

	if ($jot['do']== 'Save'){
	    forward("tasks/view/$task_guid");
	}
	
} else {
	register_error(elgg_echo('tasks:notsaved'));
//	forward(REFERER);
}
eof:
echo register_error($display);