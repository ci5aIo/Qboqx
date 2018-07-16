<?php
/**
 * 
 */
 
$entity_guid = (int) get_input('entity_guid');
 //$entity_guid = (int) get_input('item_guid');
$aspect      = get_input('aspect');	
$description = get_input('description');
$referrer    = get_input('referrer');
$layout      = get_input('layout');

$user = elgg_get_logged_in_user_entity();

elgg_make_sticky_form('jot');

system_message("Aspect: $aspect");

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("generic_comment:notfound"));
	forward(REFERER);
}
if ($aspect == 'comment'){
	$annotation = create_annotation($entity->guid,
									'generic_comment',
									$description,
									"",
									$user->guid,
									$entity->access_id);
	
	// tell user annotation posted
	if (!$annotation) {
		register_error(elgg_echo("generic_comment:failure"));
		forward(REFERER);
	}
	
	// notify if poster wasn't owner
	if ($entity->owner_guid != $user->guid) {
	
		notify_user($entity->owner_guid,
					$user->guid,
					elgg_echo('generic_comment:email:subject'),
					elgg_echo('generic_comment:email:body', array(
						$entity->title,
						$user->name,
						$description,
						$entity->getURL(),
						$user->name,
						$user->getURL()
					))
				);
	}
	
	system_message(elgg_echo("generic_comment:posted"));
	
	//add to river
	
	elgg_create_river_item([
			'view'=>'river/annotation/generic_comment/create',
			'action_type'=> 'comment',
			'subject_guid' => $user->guid,
			'object_guid' => $entity->guid]);
	
    elgg_clear_sticky_form('jot');
	
	// Forward to the page the action occurred on
	forward($referrer);
}

if ($aspect == 'purchase'){
	forward("jot/add/$aspect");
}
else {forward("jot/add/$entity_guid/$aspect/$layout");
	
}
