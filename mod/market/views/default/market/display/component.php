<?php
$marketpost = $vars['entity'];
$fields = array();
$fields[] = array(
    'label'=> 'Manufacturer',
    'value'=> $marketpost->manufacturer
);
$fields[] = array(
    'label'=> 'Brand',
    'value'=> $marketpost->brand
);
$fields[] = array(
    'label'=> 'Model',
    'value'=> $marketpost->model
);
$fields[] = array(
    'label'=> 'Part',
    'value'=> $marketpost->part
);

// section 1
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
/*original code for section1 above
foreach ($fields as $field) {
  echo "<b>{$field['label']}:</b> {$field['value']}<br>";
}*/

// components/values
$names = $marketpost->component_names;
//$names .= $marketpost->characteristic_names;
$values = $marketpost->component_values;
//$values .= $marketpost->characteristic_values;

$html = '';
foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
	$html .= '<tr><td>' . $name . '</td><td style="padding-left: 15px;">' . $values[$key] . '</td></tr>';
}

if ($html) {
	echo "<br><b>Characteristics:</b>";
	echo '<table>' . $html . '</table>';
}
// components
$components = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'component',
	'container_guid' => $marketpost->guid,
	'limit' => false,
));

if ($components) {
	echo '<label>Components</label><br><ul style="list-style-type:square">';
	foreach ($components as $component) {
		echo '<li>'.elgg_view('output/url', array(
			'text' => $component->title,
			'href' => 'market/view/'.$component->guid
//			'href' => 'market/edit/'.$component->guid
//			'href' => 'market/edit_element/'.$component->guid
		));
		echo '</li>';
	}
	echo '</ul>';
}

// accessories
$accessories = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'accessory',
	'container_guid' => $marketpost->guid,
	'limit' => false,
));

if ($accessories) {
	echo '<label>Accessories</label><br><ul style="list-style-type:square">';
	foreach ($accessories as $accessory) {
		echo '<li>'.elgg_view('output/url', array(
			'text' => $accessory->title,
			'href' => 'market/edit/'.$accessory->guid
		));
		echo '</li>';
	}
	echo '</ul>';
}

// issues
$issues = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'issue',
	'relationship_guid' => $marketpost->guid,
	'inverse_relationship' => true,
	'limit' => false,
));

if ($issues) {
	echo '<br><br>';
	echo '<label>Issues</label><br>';
	foreach ($issues as $issue) {
		echo elgg_view('output/url', array(
			'text' => $issue->name,
			'href' => $issue->getURL()
		));
		echo '<br>';
	}
}

// drivers
$drivers = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'driver',
	'relationship_guid' => $marketpost->guid,
	'inverse_relationship' => true,
	'limit' => false,
));

if ($drivers) {
	echo '<br><br>';
	echo '<label>Drivers</label><br>';
	foreach ($drivers as $driver) {
		echo elgg_view('output/url', array(
			'text' => $driver->name,
			'href' => $driver->getURL()
		));
		echo '<br>';
	}
}


// same with shoes
$shoes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'market',
	'relationship' => 'car_shoes',
	'relationship_guid' => $marketpost->guid,
	'inverse_relationship' => true,
	'limit' => false,
));

if ($shoes) {
	echo '<br><br>';
	echo '<label>Shoes</label><br>';
	foreach ($shoes as $shoe) {
		echo elgg_view('output/url', array(
			'text' => $shoe->title,
			'href' => $shoe->getURL()
		));
		echo '<br>';
	}
}

// output our uploaded file
if ($marketpost->upload) {
	echo elgg_view('output/url', array(
		'text' => 'Uploaded Document',
		'href' => 'market/file/' . $marketpost->upload
	));
}