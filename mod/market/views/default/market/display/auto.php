<?php
echo 'views\default\market\display\auto.php';
$marketpost = $vars['entity'];
$items = array();
$items[] = array(
    'label'=> 'Manufacturer',
    'field'=> $marketpost->manufacturer
);

// single items
foreach ($items as $item) {
  echo "<p><b>{$item['label']}:</b> {$item['field']}</p>";
}


// components/values
$names = $marketpost->component_names;
$values = $marketpost->component_values;

$html = '';
foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
	$html .= '<tr><td>' . $name . '</td><td style="padding-left: 15px;">' . $values[$key] . '</td></tr>';
}

if ($html) {
	echo "<p><b>Components:</b><br>";
	echo '<table>' . $html . '</table>';
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

echo '<br><br>';

// output our uploaded file
if ($marketpost->upload) {
	echo elgg_view('output/url', array(
		'text' => 'Uploaded Document',
		'href' => 'market/file/' . $marketpost->upload
	));
}