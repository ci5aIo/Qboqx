<?php
/**
 * Restore disappeared subplaces. This is caused by its parent place being deleted
 * when the parent place is a top level place. We take advantage of the fact that
 * the parent_guid was deleted for the subplaces.
 *
 * This upgrade script will no longer work once we have converted all places to
 * have the same entity subtype.
 */


/**
 * Update subtype
 *
 * @param ElggObject $place
 */
function places_2012061800($place) {
	$dbprefix = elgg_get_config('dbprefix');
	$subtype_id = (int)get_subtype_id('object', 'place_top');
	$place_guid = (int)$place->guid;
	update_data("UPDATE {$dbprefix}entities
		SET subtype = $subtype_id WHERE guid = $place_guid");
	error_log("called");
	return true;
}

$previous_access = elgg_set_ignore_access(true);

$dbprefix = elgg_get_config('dbprefix');
$name_metastring_id = elgg_get_metastring_id('parent_guid');
if (!$name_metastring_id) {
	return;
}

// Looking for places without metadata
$options = array(
	'type' => 'object',
	'subtype' => 'place',
	'wheres' => "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}metadata md
		WHERE md.entity_guid = e.guid
		AND md.name_id = $name_metastring_id)"
);
$batch = new ElggBatch('elgg_get_entities_from_metadata', $options, 'places_2012061800', 50, false);
elgg_set_ignore_access($previous_access);

if ($batch->callbackResult) {
	error_log("Elgg places upgrade (2012061800) succeeded");
}
