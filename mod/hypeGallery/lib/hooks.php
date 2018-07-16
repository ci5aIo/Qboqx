<?php

namespace hypeJunction\Gallery;

use ElggMenuItem;

/**
 * Bypass default access controls
 * - Allow users to add images to shared albums
 *
 * @param string  $hook   Equals 'container_permissions_check'
 * @param string  $type   Equals 'object'
 * @param boolean $return Current permission
 * @param array   $params Additional params
 * @return boolean Updated permission
 */
function container_permissions_check($hook, $type, $return, $params) {

	$container = elgg_extract('container', $params, false);
	$user = elgg_extract('user', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if (!elgg_instanceof($container) || !elgg_instanceof($user, 'user') || !$subtype) {
		return $return;
	}

	switch ($container->getSubtype()) {

		case hjAlbum::SUBTYPE :

			switch ($subtype) {

				case hjAlbumImage::SUBTYPE :

					$owner = $container->getOwnerEntity();

					$permission = $container->permission;

					switch ($permission) {

						case 'friends' :
							return $owner->isFriendsWith($user->guid);

						case 'public' :
							return true;

						case 'group' :
							$group = $container->getContainerEntity();
							if (elgg_instanceof($group, 'group')) {
								return $group->isMember($user);
							}
							break;
					}

					return $return;
			}
			break;
	}

	return $return;
}

/**
 * Bypass default editing permissions
 * - Allow users to edit tags that have been added to photos they own
 *
 * @param string  $hook   Equals 'permissions_check'
 * @param string  $type   Equals 'object'
 * @param boolena $return Current permission
 * @param array   $params Additional params
 * @return boolean Updated permission
 */
function permissions_check($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$user = elgg_extract('user', $params, false);

	if (!elgg_instanceof($entity, 'object') || !elgg_instanceof($user, 'user')) {
		return $return;
	}

	switch ($entity->getSubtype()) {

		default :
			return $return;

		case 'hjimagetag' :

			$image = $entity->getContainerEntity();
			if ($image->owner_guid == $user->guid) {
				return true;
			}
			return $return;
	}
}

/**
 * Filter access sql to display disabled entities
 *
 * @param string $hook    'get_sql'
 * @param string $type    'access'
 * @param array  $clauses Current clauses
 * @param array  $params  Additional params
 * @return array Filtered clauses
 */
function filter_access_sql($hook, $type, $clauses, $params) {

	if (!elgg_in_context('show_hidden_entities')) {
		return $clauses;
	}

	$table_alias = elgg_extract('table_alias', $params);
	$table_alias = ($table_alias) ? "{$table_alias}." : "";

	$and_clauses = elgg_extract('ands', $clauses, array());
	if (is_array($and_clauses)) {
		foreach ($and_clauses as $key => $clause) {
			if ($clause == "{$table_alias}enabled = 'yes'") {
				unset($clauses['ands'][$key]);
			}
		}
	}

	return $clauses;
}

/**
function entity_menu_setup($hook, $type, $return, $params) {
	if (!elgg_instanceof($entity)) {
	$items = array();
	switch ($entity->getSubtype()) {
		default :
			// Manage album
			// Edit and/or Delete
			break;
					// Edit
					// Delete

	if ($items) {
/**
function manage_album_image_menu_setup($hook, $type, $return, $params) {
	// Is item is pending approval?
		// Approve
		// Delete
	if ($entity->canEdit()) {
		// Delete
		// Crop
	$container = $entity->getContainerEntity();
		// Reorder drag handle
		// Reorder input
		// Make is image an album cover
	if ($items) {
/**
function owner_block_menu_setup($hook, $type, $return, $params) {
/**
function entity_icon_sizes($hook, $type, $return, $params) {