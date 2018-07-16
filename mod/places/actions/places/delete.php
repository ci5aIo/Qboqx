<?php
/**
 * Remove a place
 *
 * Subplaces are not deleted but are moved up a level in the tree
 *
 * @package Elggplaces
 */

$guid = get_input('guid');
$place = get_entity($guid);
if (places_is_place($place)) {
	// only allow owners and admin to delete
	if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $place->getOwnerGuid()) {
		$container = get_entity($place->container_guid);

		// Bring all child elements forward
		$parent = $place->parent_guid;
		$children = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'parent_guid',
			'metadata_value' => $place->getGUID()
		));
		if ($children) {
			$db_prefix = elgg_get_config('dbprefix');
			$subtype_id = (int)get_subtype_id('object', 'place_top');
			$newentity_cache = is_memcache_available() ? new ElggMemcache('new_entity_cache') : null;

			foreach ($children as $child) {
				if ($parent) {
					$child->parent_guid = $parent;
				} else {
					// If no parent, we need to transform $child to a place_top
					$child_guid = (int)$child->guid;

					update_data("UPDATE {$db_prefix}entities
						SET subtype = $subtype_id WHERE guid = $child_guid");

					elgg_delete_metadata(array(
						'guid' => $child_guid,
						'metadata_name' => 'parent_guid',
					));

					_elgg_invalidate_cache_for_entity($child_guid);
					if ($newentity_cache) {
						$newentity_cache->delete($child_guid);
					}
				}
			}
		}

		if ($place->delete()) {
			system_message(elgg_echo('places:delete:success'));
			if ($parent) {
				$parent = get_entity($parent);
				if ($parent) {
					forward($parent->getURL());
				}
			}
			if (elgg_instanceof($container, 'group')) {
				forward("places/group/$container->guid/all");
			} else {
				forward("places/owner/$container->username");
			}
		}
	}
}

register_error(elgg_echo('places:delete:failure'));
forward(REFERER);
