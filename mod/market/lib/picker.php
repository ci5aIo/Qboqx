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
