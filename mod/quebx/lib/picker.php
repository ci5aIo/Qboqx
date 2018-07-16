<?php
//Copied from start.php.  Do not work as copied.  Comment out until higher priority. 

function cars_user_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'user',
		'joins' => array(
			"JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid",
		),
		'wheres' => array(
			"ue.username LIKE '%{$q}%' OR ue.name LIKE '%{$q}%'",
		),
		'order_by' => 'ue.name ASC'
	));
}



function cars_shoe_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$shoes_id = elgg_get_metastring_id('shoes');
	$marketcat_id = elgg_get_metastring_id('marketcategory');
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('market'),
		'joins' => array(
			"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
			"JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid AND md.name_id = {$marketcat_id} AND md.value_id = {$shoes_id}"
		),
		'wheres' => array(
			"oe.title LIKE '%{$q}%' OR oe.description LIKE '%{$q}%'",
		),
		'order_by' => 'oe.title ASC'
	));
}
/**
 * Take over the livesearch pagehandler in case of asset search
 *
 * @param string $hook         'route'
 * @param string $type         'livessearch'
 * @param array  $return_value the current params for the pagehandler
 * @param null   $params       null
 *
 * @return bool|void
 */
function quebx_route_livesearch_handler($hook, $type, $return_value, $params) {
	
	// only return results to logged in users.
	if (!$user = elgg_get_logged_in_user_entity()) {
		exit;
	}
	
	if (!$q = get_input("term", get_input("q"))) {
		exit;
	}
	
	$input_name = get_input("name", "assets");
	
	$q = sanitise_string($q);
	
	// replace mysql vars with escaped strings
	$q = str_replace(array("_", "%"), array("\_", "\%"), $q);
	
	$match_on = get_input("match_on", "all");
	
	if (!is_array($match_on)) {
		$match_on = array($match_on);
	}
	
	// only take over assets search
	if (count($match_on) > 1 || !in_array("assets", $match_on)) {
		return $return_value;
	}
	
	if (get_input("match_owner", false)) {
		$owner_guid = $user->getGUID();
	} else {
		$owner_guid = ELGG_ENTITIES_ANY_VALUE;
	}
	
	$limit = sanitise_int(get_input("limit", 10));
	
	// grab a list of entities and send them in json.
	$results = array();
	
	$options = array(
		"type" => "object",
		"limit" => $limit,
		"owner_guid" => $owner_guid,
		"joins" => array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid"),
		"wheres" => array("(oe.name LIKE '%" . $q . "%' OR oe.description LIKE '%" . $q . "%')")
	);
	
	$entities = elgg_get_entities($options);
	if (!empty($entities)) {
		foreach ($entities as $entity) {
			$output = elgg_view_list_item($entity, array(
				"use_hover" => false,
				"class" => "elgg-autocomplete-item",
				"full_view" => false,
			));
			
			$icon = elgg_view_entity_icon($entity, "tiny", array(
				"use_hover" => false,
			));
			
			$result = array(
				"type" => "group",
				"name" => $entity->name,
				"desc" => $entity->description,
				"guid" => $entity->getGUID(),
				"label" => $output,
				"value" => $entity->getGUID(),
				"icon" => $icon,
				"url" => $entity->getURL(),
				"html" => elgg_view("input/assetpicker/item", array(
					"entity" => $entity,
					"input_name" => $input_name,
				)),
			);
			
			$results[$entity->name . rand(1, 100)] = $result;
		}
	}
	
	ksort($results);
	header("Content-Type: application/json");
	echo json_encode(array_values($results));
	exit;
}