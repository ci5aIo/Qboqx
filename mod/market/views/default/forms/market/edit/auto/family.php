<?php

echo 'Parent Level';

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level (in this case family)
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'parent'));
  
  // there is only one family here, but there can be multiple if you look at the shoes example with a dropdown selection
  // this relates to the next form view, which will now be foudn in edit/car/car.php
  echo elgg_view('input/hidden', array('name'=>'item[parent]', 'value' => 'individual'));
}
echo '<br><br>';

// get related items
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));


echo '<label>Quantity:</label>';
if (count($individuals) > 1) {
	echo count($individuals);
}
else {
	$value = ($entity->qty === '') ? 1 : $entity->qty;
	echo elgg_view('input/text', array('name' => 'qty', 'value' => $value));
}

echo '<br><br>';

echo '<label>Individuals</label><br>';
if ($individuals) {
	foreach ($individuals as $i) {
		echo elgg_view('output/url', array(
			'text' => '[link to item]',
			'href' => $i->getURL()
		));
		
		echo ' ---- ';
		
		echo $i->guid; // whatever info you want to output about the individual
		
		echo '<br>';
	}
}


// compose a link to our clone function
// note that the clone function is non-descriminatory
// it will clone everything but add unique guid
$link = elgg_view('output/url', array(
	'text' => 'Add Individual',
	'href' => elgg_add_action_tokens_to_url('action/market/clone?guid=' . $entity->guid)
));

echo '<br><br>' . $link;


echo '<br><br>';

// next
echo elgg_view('input/submit', array('value' => 'Next'));