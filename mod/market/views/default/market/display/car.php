<?php
$entity = $vars['entity'];
$item_guid = $entity->guid;

$fields = market_prepare_detailed_view_vars($entity);

echo 'view: \default\market\display\car.php</p>';

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
/*original code for section1 above
foreach ($fields as $field) {
  echo "<b>{$field['label']}:</b> {$field['value']}<br>";
}*/

