<?php

echo 'Manage Inventory (Parent Level)';

$guid = $vars['guid'];
$entity = get_entity($guid);
$owner = $entity->owner;
$model_no = $entity->model;
$sku = $entity->sku;

//$subtype = $entity->getSubtype();
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'parent'));
  echo elgg_view('input/hidden', array('name'=>'item[parent]', 'value' => 'individual'));
  $next_level = '/individual'; //probably not the right way to reference the next level.
}

$dbprefix = elgg_get_config('dbprefix');
$q = "SELECT guid FROM {$dbprefix}entities WHERE container_guid = {$entity->guid}";
$data = get_data($q);

// get related items
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
//	'owner' => $owner,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));
$same_model = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'owner' => $owner,
	'model' => $model_no,
	));

$individual_qty = array_sum(($individuals->qty ==='') ? 1 : $individuals->qty);
$models_qty = array_sum(($same_model->qty === '') ? 1 : $same_model->qty);

$options = array(
	'types' => 'object',
//	'subtype' => $subtype,
	'subtypes' => 'market',
	'owner' => $owner,
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
	'count' => true,
	'list_type' => 'list',
	'list_type_toggle' => true,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token)
);
$value = ($entity->qty === '') ? 1 : $entity->qty;
if (count($individuals)>$value) {
   $value = count($individuals);
};

/* compose a link to our clone function
 * note that the clone function is non-descriminatory
 * it will clone everything but add unique guid
 * function moved to start.php.  Included in entity menu
*/$add_another = elgg_view('output/url', array(
	'text' => 'Duplicate',
	'href' => elgg_add_action_tokens_to_url('action/market/clone?guid=' . $entity->guid)
));

$num_items = 16;  // 0 = Unlimited
echo '<br>Model Qty:'. $models_qty.'<br><br>';
echo '<br>Individual Qty:'. $individual_qty.'<br><br>';
echo '<br><br>';
echo '<table width=100%>';
echo '<tr><td><label>Count: </label></td><td>&nbsp;</td><td>';

	if (count($individuals) > 1){
		echo $value;
	}
	else {
//		echo elgg_view('input/text', array('name' => 'qty', 'value' => $value));
		echo elgg_view('input/text', array('name' => 'item[qty]', 'value' => $value));
	}
	
echo '</td><td width=75%></td>';
echo '<td nowrap>'.$add_another.'</td>';
echo '</tr></table>';

echo '<br>'.elgg_list_entities_from_metadata($options).'<br>';

echo '<table width=100%>';
echo '<tr><td>#</td><td><label>Contents</label></td><td>Manufacturer Serial #</td><td>Owner Serial #</td></tr>';

if ($individuals) {
	foreach ($individuals as $i) {
		echo '<tr><td>'.$i->guid.'</td>';
		echo '<td>'.elgg_view('output/url', array(
			'text' => $i->title,
			'href' =>  'market/edit_more/'.$i->guid.$vars['h'].$next_level
		)).'</td>';
		echo '<td>'.$i->serial01.'</td>';
		echo '<td>'.$i->serial02.'</td>'; // whatever info you want to output about the individual
		echo '<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $i->guid))).'</td>';
		echo '</tr>';
	}
}	

echo '</table>';

echo '<br><br>' . $add_another. '<br><br>';


// next
echo elgg_view('input/submit', array('value' => 'save'));