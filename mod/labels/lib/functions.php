<?php

/*namespace Quebx\Labels;*/

/**
 * Add a new friend
 * 
 * @param type $friend
 * @return boolean
 */
function labels_add_friend($friend) {
	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return false;
	}

	// if not a friend already, add as friend
	if (!$user->isFriendsWith($friend->guid)) {
		$errors = false;

		// 	Get the user
		try {
			if (!$user->addFriend($friend->guid)) {
				$errors = true;
			}
		} catch (Exception $e) {
			register_error(elgg_echo("friends:add:failure", array($friend->name)));
			$errors = true;
		}
		if (!$errors) {
			// 	add to river
			// add to river
			elgg_create_river_item(array(
				'view' => 'river/relationship/friend/create',
				'action_type' => 'friend',
				'subject_guid' => $user->guid,
				'object_guid' => $friend->guid,
			));
			system_message(elgg_echo("friends:add:successful", array($friend->name)));
		}
	}
}

/**
 * takes an array of collections names
 * returns an array of corresponding ids
 * 
 * @param type $user
 * @param type $names_array
 * @return boolean
 */
function labels_collection_names_to_ids($user, $names_array) {
	if (!is_array($names_array)) {
		return false;
	}

	$collections = get_user_access_collections($user->guid);

	$ids = array();
	foreach ($collections as $c) {
		if (in_array($c->name, $names_array)) {
			$ids[] = $c->id;
		}
	}

	return $ids;
}

/**
 * get array of collection ids that the friend is already tagged as
 * 
 * @param type $user
 * @param type $friend
 * @param type $names
 * @return array
 */
function labels_get_friend_collections($user, $friend, $names = false) {
	if (!($user instanceof \ElggUser) || !($friend instanceof \ElggUser)) {
		return array();
	}

	// there's no good api for this that's scalable
	// so lets cheat and do direct queries
	$dbprefix = elgg_get_config('dbprefix');
	$q = "SELECT ac.id as id, ac.name as name FROM {$dbprefix}access_collections ac"
			. " JOIN {$dbprefix}access_collection_membership m ON m.access_collection_id = ac.id"
			. " WHERE ac.owner_guid = {$user->guid} AND m.user_guid = {$friend->guid}";

	$data = get_data($q);

	if (!$data) {
		return array();
	}

	$friend_collections = array();
	foreach ($data as $d) {
		if ($names) {
			$friend_collections[] = $d->name;
		} else {
			$friend_collections[] = $d->id;
		}
	}

	return $friend_collections;
}

/**
 * checks if 2 users are reciprocal friends
 * 
 * @param \ElggUser $user1
 * @param \ElggUser $user2
 * @return boolean
 */
function labels_reciprocal_friendship($user1, $user2) {
	if (!($user1 instanceof \ElggUser) || !($user2 instanceof \ElggUser)) {
		return false;
	}

	$forward = check_entity_relationship($user1->guid, "friend", $user2->guid);
	$backward = check_entity_relationship($user2->guid, "friend", $user1->guid);

	return ($forward && $backward);
}

/**
 * removes a single item from an array
 * resets keys
 * 
 * @param type $value
 * @param type $array
 * @return type
 */
function labels_removeFromArray($value, $array) {
	if (!is_array($array)) {
		return $array;
	}
	if (!in_array($value, $array)) {
		return $array;
	}

	for ($i = 0; $i < count($array); $i++) {
		if ($value == $array[$i]) {
			unset($array[$i]);
			$array = array_values($array);
		}
	}

	return $array;
}

/**
 * 
 * @param type $user
 * @param type $friend
 * @param type $rtags_list
 * @param type $existing_rtags
 */
function labels_collections_update($user, $friend, $rtags_list, $existing_rtags) {
	//notifications are automagically taken care of
	// array of rtags submitted
	$rtags_submitted = explode(',', $rtags_list);

	$rtags_submitted = array_map('trim', $rtags_submitted);

	//add in the checkbox array, and remove duplicate tags
	if (is_array($existing_rtags) && count($existing_rtags) > 0) {
		$rtags_submitted = array_merge($rtags_submitted, $existing_rtags);
	}
	$rtags_submitted = array_unique($rtags_submitted);
	$rtags_submitted = array_values($rtags_submitted);



	// get array of rtags that the friend is already tagged as
	$ids_existing = labels_get_friend_collections($user, $friend);


	//	$ids_submitted = array of ids submitted THAT EXIST AS A COLLECTION ALREADY
	//	$ids_existing = array of ids of collections that our friend is already in
	//  we need to find what rtags are new, create that collection
	//  get array of names of all collections owned by me
	$collections = get_user_access_collections($user->guid);
	$rtag_all = array();
	foreach ($collections as $c) {
		$rtag_all[] = $c->name;
	}

	//find names of collections to create
	$newcollections = array_diff($rtags_submitted, $rtag_all);


	//create new collections
	foreach ($newcollections as $n) {
		$id = create_access_collection($n, $user->guid);
		add_user_to_access_collection($friend->guid, $id);
	}

	// now we should be able to get ids for everything
	$ids_submitted = labels_collection_names_to_ids($user, $rtags_submitted);

	foreach ($ids_existing as $id) {
		if (!in_array($id, $ids_submitted)) {
			remove_user_from_access_collection($friend->guid, $id);
		}
	}

	foreach ($ids_submitted as $id) {
		add_user_to_access_collection($friend->guid, $id);
	}
}


/**
 * subscribes or unsubscribes 
 * 
 * @param type $user
 * @param type $friend
 * @param type $subscriptions
 */
function labels_notifications_update($user, $friend, $subscriptions) {
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {

		// add in chosen notifications methods
		if ($subscriptions[$method] == $friend->guid) {
			add_entity_relationship($user->guid, 'notify' . $method, $friend->guid);
		} else {
			// remove pre-existing methods
			remove_entity_relationship($user->guid, 'notify' . $method, $friend->guid);
		}
	}
}
/**
 * Add a new item
 * 
 * @param type $item
 * @return boolean
 */
function labels_add_item($item) {
	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return false;
	}

	// if not in a collection already, add to collection
		if (!check_entity_relationship($item->guid, "collection", $user->guid)) {
//		if (!$user->isFriendsWith($friend->guid)) {
		$errors = false;

		// 	Get the item
		try {
			if (!add_entity_relationship($item->guid, "collection", $user->guid)) {
//			if (!$user->addFriend($friend->guid)) {
				$errors = true;
			}
		} catch (Exception $e) {
			register_error(elgg_echo("item:add:failure", array($friend->name)));
			$errors = true;
		}
		if (!$errors) {
			// 	add to river
			// add to river
			elgg_create_river_item(array(
				'view' => 'river/relationship/friend/create',
				'action_type' => 'collection',
				'subject_guid' => $user->guid,
				'object_guid' => $item->guid,
			));
			system_message(elgg_echo("item:add:successful", array($item->name)));
		}
	}
}

/** Adapted from engine\lib\tags.php\elgg_get_tags
 * 
 * Get popular tags and their frequencies
 * Supports similar arguments as elgg_get_entities()
 * @param array $options Array in format:
 * 	threshold => INT minimum tag count
 * 	tag_names => array() metadata tag names - must be registered tags
 * 	limit => INT number of tags to return
 *  types => null|STR entity type (SQL: type = '$type')
 * 	subtypes => null|STR entity subtype (SQL: subtype = '$subtype')
 * 	type_subtype_pairs => null|ARR (array('type' => 'subtype'))
 *  (SQL: type = '$type' AND subtype = '$subtype') pairs
 * 	owner_guids => null|INT entity guid
 * 	container_guids => null|INT container_guid
 * 	site_guids => null (current_site)|INT site_guid
 * 	created_time_lower => null|INT Created time lower boundary in epoch time
 * 	created_time_upper => null|INT Created time upper boundary in epoch time
 * 	modified_time_lower => null|INT Modified time lower boundary in epoch time
 * 	modified_time_upper => null|INT Modified time upper boundary in epoch time
 * 	wheres => array() Additional where clauses to AND together
 * 	joins => array() Additional joins
 * @return 	object[]|false If no tags or error, false
 * 						   otherwise, array of objects with ->tag and ->total values
 * @since 1.7.1
 */
function get_labels(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'threshold' => 1,
		'tag_names' => array(),
		'limit' => 10,

		'types'               => ELGG_ENTITIES_ANY_VALUE,
		'subtypes'            => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'  => ELGG_ENTITIES_ANY_VALUE,

		'owner_guids'         => ELGG_ENTITIES_ANY_VALUE,
		'container_guids'     => ELGG_ENTITIES_ANY_VALUE,
		'item_guids'           => ELGG_ENTITIES_ANY_VALUE,
		'site_guids'          => $CONFIG->site_guid,

		'modified_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper' => ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'  => ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'  => ELGG_ENTITIES_ANY_VALUE,

		'joins' => array(),
		'wheres' => array(),
	);


	$options = array_merge($defaults, $options);

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'item_guid', 'site_guid', 'tag_name');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$registered_tags = elgg_get_registered_tag_metadata_names();

	if (!is_array($options['tag_names'])) {
		return false;
	}

	// empty array so use all registered tag names
	if (count($options['tag_names']) == 0) {
		$options['tag_names'] = $registered_tags;
	}

	$diff = array_diff($options['tag_names'], $registered_tags);
	if (count($diff) > 0) {
		elgg_deprecated_notice('Tag metadata names must be registered by elgg_register_tag_metadata_name()', 1.7);
		// return false;
	}

	$wheres = $options['wheres'];

	// catch for tags that were spaces
	$wheres[] = "msv.string != ''";

	$sanitised_tags = array();
	foreach ($options['tag_names'] as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}
	$tags_in = implode(',', $sanitised_tags);
	$wheres[] = "(msn.string IN ($tags_in))";

	$wheres[] = _elgg_services()->entityTable->getEntityTypeSubtypeWhereSql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.site_guid', $options['site_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.owner_guid', $options['owner_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.container_guid', $options['container_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('md.entity_guid', $options['item_guids']);
	$wheres[] = _elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === false) {
			return false;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	$joins = $options['joins'];

	$joins[] = "JOIN {$CONFIG->dbprefix}metadata md on md.entity_guid = e.guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msv on msv.id = md.value_id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msn on md.name_id = msn.id";

	// remove identical join clauses
	$joins = array_unique($joins);

	foreach ($joins as $i => $join) {
		if ($join === false) {
			return false;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}


	$query  = "SELECT msv.string as tag, msv.id as metastring";
	$query .= " FROM {$CONFIG->dbprefix}entities e ";

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= _elgg_get_access_where_sql();

	$threshold = sanitise_int($options['threshold']);
//	$query .= " GROUP BY msv.string HAVING total >= {$threshold} ";
//	$query .= " ORDER BY total DESC ";

	$limit = sanitise_int($options['limit']);
	$query .= " LIMIT {$limit} ";

	return get_data($query);
}

/**
 * Adapted from engine\lib\access.php\get_user_access_collections
 * Returns an array of database row labels owned by $owner_guid.
 *
 * @param int $owner_guid The entity guid
 * @param int $site_guid  The GUID of the site (default: current site).
 *
 * @return array|false
 */
function get_user_label_collections($owner_guid, $site_guid = 0) {
	global $CONFIG;
	$owner_guid = (int) $owner_guid;
	$site_guid = (int) $site_guid;

	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}

	$query = "SELECT *
	        FROM {$CONFIG->dbprefix}metastrings msv
			where exists (Select *
			              from {$CONFIG->dbprefix}entities e
			              join {$CONFIG->dbprefix}metadata md 
			              on md.entity_guid = e.guid
			              JOIN {$CONFIG->dbprefix}metastrings msn 
			              on md.name_id = msn.id
					      where md.owner_guid = {$owner_guid}
						    AND e.site_guid = {$site_guid}
							and msv.id = md.value_id
							and msn.string IN ('tags'))
			 order by string";

	$collections = get_data($query);

	return $collections;
}
/**
 * get array of collection ids that the item is already tagged as
 * 
 * @param type $user
 * @param type $friend
 * @param type $names
 * @return array
 */
function labels_get_item_collections($user, $friend, $names = false) {
/*	if (!($user instanceof \ElggUser) || !($friend instanceof \ElggUser)) {
		return array();
	}
*/
	// there's no good api for this that's scalable
	// so lets cheat and do direct queries
	$dbprefix = elgg_get_config('dbprefix');
	$q = "SELECT ac.id as id, ac.name as name FROM {$dbprefix}access_collections ac"
	  . " JOIN {$dbprefix}access_collection_membership m ON m.access_collection_id = ac.id"
	  . " WHERE ac.owner_guid = {$user->guid} AND m.user_guid = {$friend->guid}";

	$q = "SELECT ac.id as id, ac.name as name 
	      FROM {$dbprefix}access_collections ac
          JOIN {$dbprefix}access_collection_membership m 
          ON m.access_collection_id = ac.id
          WHERE ac.owner_guid = {$user->guid} 
            AND m.user_guid = {$friend->guid}";

	$data = get_data($q);
	
	// Experimental: override the above to retrieve tags instead of access collections
	$data = $friend->gettags();
//	$data['names'] = $friend->gettags();
/*
	if (!$data) {
		return array();
	}

	$friend_collections = array();
	foreach ($data as $d) {
		if ($names) {
			$friend_collections[] = $d->name;
		} else {
			$friend_collections[] = $d->id;
		}
	}
 */
//Experimental
$collections = $data;
//$collections = implode(', ', $data['names']);

	return $collections;
}
/**
 * Updates the labels of an item
 * @param type $user
 * @param type $item
 * @param type $labels_list
 * @param type $existing_labels
 */
function labels_collections_update_II($user, $item, $labels_list, $existing_labels) {
	// array of rtags submitted
	$labels_submitted = string_to_tag_array($labels_list);
	$labels_submitted = array_map('trim', $labels_submitted);

	//add in the checkbox array, and remove duplicate tags
	if (is_array($existing_labels) && count($existing_labels) > 0) {
		$labels_submitted = array_merge($labels_submitted, $existing_labels);
	}
	$labels_submitted = array_unique($labels_submitted);
	$labels_submitted = array_values($labels_submitted);

$item->tags = $labels_submitted;
$item->save(); 

}

/** Adapted from engine\lib\access.php\create_access_collection
 * Creates a new access collection.
 *
 * Access collections allow plugins and users to create granular access
 * for entities.
 *
 * Triggers plugin hook 'access:collections:addcollection', 'collection'
 *
 * @internal Access collections are stored in the access_collections table.
 * Memberships to collections are in access_collections_membership.
 *
 * @param string $name       The name of the collection.
 * @param int    $owner_guid The GUID of the owner (default: currently logged in user).
 * @param int    $site_guid  The GUID of the site (default: current site).
 *
 * @return int|false The collection ID if successful and false on failure.
 * @see update_access_collection()
 * @see delete_access_collection()
 */
function create_label_collection($name, $owner_guid = 0, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);
	if (empty($name)) {
		return false;
	}

	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}
	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}
	$name = sanitise_string($name);

// Using 
$collection = new ElggMetadata();
$collection->name       = $name;
$collection->owner_guid = $owner_guid;
$collection->site_guid  = $site_guid;
$collection->value_type = 'text';
$id = $collection->save();

/*
	$q = "INSERT INTO {$CONFIG->dbprefix}metastrings
		SET string = '{$name}',
			owner_guid = {$owner_guid},
			site_guid = {$site_guid}";
	$id = insert_data($q);
*/	if (!$id) {
		return false;
	}

	$params = array(
		'collection_id' => $id
	);

	if (!elgg_trigger_plugin_hook('access:collections:addcollection', 'collection', $params, true)) {
		return false;
	}

	return $id;
}

/** Adapted from engine\lib\access.php\add_user_to_access_collection
 * Adds a user to an access collection.
 *
 * Triggers the 'access:collections:add_user', 'collection' plugin hook.
 *
 * @param int $user_guid     The GUID of the user to add
 * @param int $collection_id The ID of the collection to add them to
 *
 * @return bool
 * @see update_access_collection()
 * @see remove_user_from_access_collection()
 */
function add_item_to_label_collection($user_guid, $collection_id) {
	global $CONFIG;

	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);

	$collection = get_access_collection($collection_id);

	if (!($user instanceof Elgguser) || !$collection) {
		return false;
	}

	$params = array(
		'collection_id' => $collection_id,
		'user_guid' => $user_guid
	);

	$result = elgg_trigger_plugin_hook('access:collections:add_user', 'collection', $params, true);
	if ($result == false) {
		return false;
	}

	// if someone tries to insert the same data twice, we do a no-op on duplicate key
	$q = "INSERT INTO {$CONFIG->dbprefix}access_collection_membership
			SET access_collection_id = $collection_id, user_guid = $user_guid
			ON DUPLICATE KEY UPDATE user_guid = user_guid";
	$result = insert_data($q);

	return $result !== false;
}

/** Adapted from engine\lib\access.php\remove_user_from_access_collection
 * Removes a user from an access collection.
 *
 * Triggers the 'access:collections:remove_user', 'collection' plugin hook.
 *
 * @param int $user_guid     The user GUID
 * @param int $collection_id The access collection ID
 *
 * @return bool
 * @see update_access_collection()
 * @see remove_user_from_access_collection()
 */
function remove_item_from_label_collection($user_guid, $collection_id) {
	global $CONFIG;

	$collection_id = (int) $collection_id;
	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);

	$collection = get_access_collection($collection_id);

	if (!($user instanceof Elgguser) || !$collection) {
		return false;
	}

	$params = array(
		'collection_id' => $collection_id,
		'user_guid' => $user_guid
	);

	if (!elgg_trigger_plugin_hook('access:collections:remove_user', 'collection', $params, true)) {
		return false;
	}

	$q = "DELETE FROM {$CONFIG->dbprefix}access_collection_membership
		WHERE access_collection_id = {$collection_id}
			AND user_guid = {$user_guid}";

	return (bool)delete_data($q);
}


/** Adapted from engine\lib\access.php\update_access_collection
 * Updates the membership in an access collection.
 *
 * @warning Expects a full list of all members that should
 * be part of the access collection
 *
 * @note This will run all hooks associated with adding or removing
 * members to access collections.
 *
 * @param int   $collection_id The ID of the collection.
 * @param array $members       Array of member GUIDs
 *
 * @return bool
 * @see add_user_to_access_collection()
 * @see remove_user_from_access_collection()
 */
function update_label_collection($collection_id, $members) {
	$acl = get_label_collection($collection_id);

	if (!$acl) {
		return false;
	}
	$members = (is_array($members)) ? $members : array();

	$cur_members = get_members_of_label_collection($collection_id, true);
	$cur_members = (is_array($cur_members)) ? $cur_members : array();

	$remove_members = array_diff($cur_members, $members);
	$add_members = array_diff($members, $cur_members);

	$result = true;

	foreach ($add_members as $guid) {
		$result = $result && add_item_to_label_collection($guid, $collection_id);
	}

	foreach ($remove_members as $guid) {
		$result = $result && remove_item_from_label_collection($guid, $collection_id);
	}

	return $result;
}

/** Adapted from engine\lib\access.php\get_access_collection
 * Get a specified access collection
 *
 * @note This doesn't return the members of an access collection,
 * just the database row of the actual collection.
 *
 * @see get_members_of_access_collection()
 *
 * @param int $collection_id The collection ID
 *
 * @return object|false
 */
function get_label_collection($collection_id) {
	global $CONFIG;
	$collection_id = (int) $collection_id;

	$query = "SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE id = {$collection_id}";
	$get_collection = get_data_row($query);

	return $get_collection;
}

/** Adapted from engine\lib\access.php\get_members_of_access_collection
 * Get all of members of an access collection
 *
 * @param int  $collection The collection's ID
 * @param bool $idonly     If set to true, will only return the members' GUIDs (default: false)
 *
 * @return array ElggUser guids or entities if successful, false if not
 * @see add_user_to_access_collection()
 */
function get_members_of_label_collection($collection, $idonly = false) {
	global $CONFIG;
	$collection = (int)$collection;

	if (!$idonly) {
		$query = "SELECT e.* FROM {$CONFIG->dbprefix}access_collection_membership m"
			. " JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid"
			. " WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query, "entity_row_to_elggstar");
	} else {
		$query = "SELECT e.guid FROM {$CONFIG->dbprefix}access_collection_membership m"
			. " JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid"
			. " WHERE m.access_collection_id = {$collection}";
		$collection_members = get_data($query);
		if (!$collection_members) {
			return false;
		}
		foreach ($collection_members as $key => $val) {
			$collection_members[$key] = $val->guid;
		}
	}

	return $collection_members;
}
