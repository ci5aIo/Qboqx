<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
setlocale(LC_MONETARY, 'en_US');

/**/

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$contents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'contents',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$contents = array_merge($contents, elgg_get_entities(array(
				'type' => 'object',
				'subtypes' => array('market', 'item'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
			)));
$components = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$parent_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$containers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));

//From 'mod\widget_manager\views\default\widgets\index_activity\content.php'
$widget = $vars["entity"];

$count = sanitise_int($widget->activity_count, false);
if (empty($count)) {
	$count = 10;
}

if ($activity_content = $widget->activity_content) {
	if (!is_array($activity_content)) {
		if ($activity_content == "all") {
			unset($activity_content);
		} else {
			$activity_content = explode(",", $activity_content);
		}
	}
}

$river_options = array(
	"pagination" => true,
	"limit" => $count,
	"object_guids" => $widget->guid,
	"type_subtype_pairs" => array()
);

if (empty($activity_content)) {
	$activity = elgg_list_river($river_options);
} else {
	foreach ($activity_content as $content) {
		list($type, $subtype) = explode(",", $content);
		if (!empty($type)) {
			$value = $subtype;
			if (array_key_exists($type, $river_options['type_subtype_pairs'])) {
				if (!is_array($river_options['type_subtype_pairs'][$type])) {
					$value = array($river_options['type_subtype_pairs'][$type]);
				} else {
					$value = $river_options['type_subtype_pairs'][$type];
				}
				
				$value[] = $subtype;
			}
			$river_options['type_subtype_pairs'][$type] = $value;
		}
	}
	
	$activity = elgg_list_river($river_options);
	$activity = elgg_get_river($river_options);
}

if (empty($activity)) {
	$activity = elgg_echo("river:none");
}

echo $activity;
