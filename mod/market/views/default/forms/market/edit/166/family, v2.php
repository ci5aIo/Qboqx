<?php

echo 'Manage Inventory (Parent Level)';

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'parent'));
  echo elgg_view('input/hidden', array('name'=>'item[parent]', 'value' => 'individual'));
  $next_level = '/individual'; //probably not the right way to reference the level.
}
// get related items
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));
$value = ($entity->qty === '') ? 1 : $entity->qty;
if (count($individuals)>$value) {
   $value = count($individuals);
};
$link = elgg_view('output/url', array(
	'text' => 'Add Another',
	'href' => elgg_add_action_tokens_to_url('action/market/clone?guid=' . $entity->guid)
));

$num_items = 16;  // 0 = Unlimited

$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
//	'view_type_toggle' => true, //depricated in 1.8
	'list_type' => 'list',
	'list_type_toggle' => true,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token)
);

//$content = elgg_list_entities_from_metadata($options);
//echo $content;

echo '<br><br>';
//echo var_dump($item);
echo '<table width=100%>';
echo '<tr><td><label>Count: </label></td><td>&nbsp;</td><td width=80%>';
/*	if (count($individuals) > 1) {
		echo count($individuals);
	}
	else {
		$value = ($entity->qty === '') ? 1 : $entity->qty;
		echo elgg_view('input/text', array('name' => 'qty', 'value' => $value));
	}*/
	if (count($individuals) > 1){
		echo $value;
	}
	else {
		echo elgg_view('input/text', array('name' => 'qty', 'value' => $value));
	}
	
echo '</td>';
echo '<td>' . $link.'</td>';
echo '</tr></table>';
echo '<br>'.elgg_list_entities_from_metadata($options).'<br>';
echo '<table width=100%>';
echo '<tr><td><label>Contents</label></td><td>Manufacturer Serial #</td><td>Owner Serial #</td></tr>';
// echo 'Family Token: '.$entity->family_token.'<br>';
// echo var_dump($individuals);
if ($individuals) {
	foreach ($individuals as $i) {
		echo '<tr><td>'.elgg_view('output/url', array(
			'text' => $i->title,
//			'href' => $i->getURL()
			'href' =>  'market/edit_more/'.$i->guid.$vars['h'].$next_level
		)).'</td>';
		echo '<td>'.$i->serial01.'</td>';
		echo '<td>'.$i->serial02.'</td>'; // whatever info you want to output about the individual
		echo '</tr>';
	}
}	

echo '</table>';

// compose a link to our clone function
// note that the clone function is non-descriminatory
// it will clone everything but add unique guid

// next
echo elgg_view('input/submit', array('value' => 'save'));