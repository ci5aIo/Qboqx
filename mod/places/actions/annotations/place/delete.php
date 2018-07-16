<?php
/**
 * Remove a place (revision) annotation
 *
 * @package Elggplaces
 */

// Make sure we can get the annotations and entity in question
$annotation_id = (int) get_input('annotation_id');
$annotation = elgg_get_annotation_from_id($annotation_id);
if ($annotation) {
	$entity = get_entity($annotation->entity_guid);
	if (places_is_place($entity) && $entity->canEdit() && $annotation->canEdit()) {
		$annotation->delete();
		system_message(elgg_echo("places:revision:delete:success"));
		forward("places/history/{$annotation->entity_guid}");
	}
}
register_error(elgg_echo("places:revision:delete:failure"));
forward(REFERER);
