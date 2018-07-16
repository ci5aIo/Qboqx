<?php
/**
 * Elgg add comment action
 *
 * @package Elgg.Core
 * @subpackage Comments
 */

$entity_guid = (int) get_input('entity_guid');
$comment_text = get_input('jot_text');
$referrer = get_input('referral_path');
$vars['jot_text'] = $comment_text;
$vars['referral_path'] = $referrer;

elgg_make_sticky_form('jot');

if (empty($comment_text)) {
	register_error(elgg_echo("generic_comment:blank"));
	forward(REFERER);
}

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("generic_comment:notfound"));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

forward("jot/jot/{$entity->guid}");

/*
 * Interrupt the process here .........
 */

	
$annotation = create_annotation($entity->guid,
								'generic_comment',
								$comment_text,
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
					$comment_text,
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
		'action_type'=> 'create',
		'subject_guid' => $user->guid,
		'object_guid' => $entity->guid]);

// Forward to the page the action occurred on
//forward(REFERER);

// this might be the only way to pass the comment.  Doesn't work as written
//forward("market/jot/{$entity->guid}");
//forward("market/jot/".$annotation->guid);

