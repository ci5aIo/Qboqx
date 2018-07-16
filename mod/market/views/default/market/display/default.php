<?php
$entity = $vars['entity'];
$item_guid = $entity->guid;
/*
$fields = array();
$fields[] = array(
    'label'=> 'Manufacturer',
    'value'=> $entity->manufacturer
);
$fields[] = array(
    'label'=> 'Brand',
    'value'=> $entity->brand
);
$fields[] = array(
    'label'=> 'Model',
    'value'=> $entity->model
);
$fields[] = array(
    'label'=> 'Part',
    'value'=> $entity->part
);

$fields[] = array(
    'label'=> 'SKU',
    'value'=> $entity->sku
);

$fields[] = array(
    'label'=> 'Warranty',
    'value'=> $entity->warranty
);
*/
$fields = market_prepare_detailed_view_vars($entity);

/**/
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$tasks = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'task',
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
$containers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$drivers = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'driver',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
$shoes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'market',
	'relationship' => 'car_shoes',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));
$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
));

echo 'view: \default\market\display\default.php</p>';

// section 1
/*
$section1 = '';
foreach ($fields as $field) {
	if ($field['value'] === '') {
		// don't show empty values
		continue;
	}
    $section1 .= "<tr><td><b>{$field['label']}<b></td><td style='padding-left: 15px;'>{$field['value']}</td></tr>";
}
if ($section1) {
	echo '<table>' . $section1 . '</table>';
}
*/
// section 1
$section1 = '';
foreach ($fields as $key => $value) {
	if ($fields['value'] === '') {
		// don't show empty values
		continue;
	}
    $section1 .= "<tr><td><b>$key<b></td><td style='padding-left: 15px;'>$value</td></tr>";
}

if ($section1) {
	echo '<table>' . $section1 . '</table>';
}