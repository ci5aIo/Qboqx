<?php
echo 'views/default/forms/market/edit/family/individual.php<br>';
echo 'Individual Level';

$selected_marketcategory = $vars['entity']->marketcategory;

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level (in this case 'individual')
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'individual'));
  
  // there is only one family here, but there can be multiple if you look at the shoes example with a dropdown selection
  // this relates to the next form view, which will now be found in edit/car/car.php
  echo elgg_view('input/hidden', array('name'=>'item[individual]', 'value' => 'individual'));
  $this_level = '/car';
  $next_level = '/family'; //probably not the right way to reference the level.
}

$item_owner = get_entity($entity->owner_guid);
if (elgg_instanceof($entity, 'object','market')) {
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
}

$title = $entity['title'];
$body = $vars['marketbody'];
$tags = $vars['markettags'];
$category = $entity->marketcategory;
$access_id = $vars['access_id']; 

$selected_category = $category;
$num_items = 16;  // 0 = Unlimited
$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
	'list_type' => 'list', // options are list, gallery.  Seems to not allow other list types. See views.php in core.
	'list_type_toggle' => true,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token)
);

// $options['metadata_name'] = "marketcategory";
// $options['metadata_value'] = $selected_category;
$content = elgg_list_entities_from_metadata($options);
// get related items
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));

?>
<script type="text/javascript">
$(document).ready(function() {
	
	// clone a node
	$('.clone-this-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.this_characteristics').html();
		$(html).insertBefore('.clone-this-characteristic-action');
	});
	
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').eq(0).remove();
	});
});
</script>
<?php
if (count($individuals)>1) {
	echo '<table width=100%>';
	echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
	echo '<td>Qty on Hand: ';
	if (count($individuals)>$value) {
	   $value = count($individuals);
	};
	echo $value.'</td>';
	echo '<td>'.elgg_view('output/url', array(
				'text' => 'add',
				'href' =>  'market/edit_more/'.$guid.'/'.$category.'/family'
			)).'</td>';
	echo '</tr>';
	echo '<tr><td>#</td><td><label>Contents</label></td><td>Manufacturer Serial #</td><td>Owner Serial #</td></tr>';
	
	if ($individuals) {
		foreach ($individuals as $i) {
			echo '<tr><td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array(
				'text' => $i->title,
				'href' =>  'market/edit_more/'.$i->guid.'/'.$category.'/profile'
			)).'</td>';
			echo '<td>'.$i->serial01.'</td>';
			echo '<td>'.$i->serial02.'</td>'; // whatever info you want to output about the individual
			echo '<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $entity->guid))).'</td>';
			echo '</tr>';
		}
	}	
	
	echo '</table>';

}
else {echo '<div><b><FONT FACE="arial" SIZE=3>'.elgg_view("input/text", array(
													"name" => "markettitle",
													"value" => $title,
													));
  }

echo '<br><br>';
echo '<div><label>'.$title.'</label></div>';
echo '<br>';
echo '<table width = "100%">';
echo '<tr><td  WIDTH=20%>Category:</td>';
echo '<td colspan=4  WIDTH=80%>'. elgg_echo("market:category:{$category}").'</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Manufacturer:</td>';
echo '<td colspan=4  WIDTH=80%>'.elgg_echo($entity->manufacturer).'</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Brand:</td>';
echo '<td colspan=4  WIDTH=80%>'.elgg_echo($entity->brand).'</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Model #:</td>';
echo '<td  WIDTH=55%>'.elgg_echo($entity->model).'</td>';
echo '<td>Part #</td>';
echo '<td colspan=2 WIDTH=25%>'.elgg_echo($entity->part).'</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Description:</td>';
echo '<td colspan=4  WIDTH=80%>'.elgg_echo($entity->description);
echo '</td>';
echo '</tr>';
echo '<tr><td colspan=5>';
echo elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'large', 'tu' => $entity->time_updated));
echo '</td></tr>';
echo '<tr><td colspan=5>';
echo elgg_echo("market:uploadimages") . "<small><small>&nbsp;" . elgg_echo("market:imagelimitation") . "</small></small>";
echo '</td></tr>';
echo '<tr><td colspan=5>';
echo elgg_view("input/file",array('name' => 'upload'));
echo '</td></tr>';
echo '<tr><td WIDTH=31%>' . elgg_echo("market:tags"); //. '&nbsp;<small><small>' . elgg_echo("market:tags:help") . '</td>';
echo '<td colspan=4  WIDTH=69%>';
echo elgg_view("input/tags", array(
				"name" => "markettags",
				"value" => $tags,
				));
echo "</label>	";
echo '</td></tr>';
echo '<tr><td  WIDTH=20%>Collection:</td>';
echo '<td colspan=4 WIDTH=80%>[Group or system to which this item is a component]</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Warranty:</td>';
echo '<td colspan=4>'.elgg_echo($entity->warranty).'</td>';
echo '</tr>';
echo '<tr><td>Warrantor:</td>';
echo '<td colspan=4>'.elgg_echo($entity->warrantor).'</td>';
echo '</tr>';
echo '<tr><td>Owner:</td>';
echo '<td colspan=4>'.elgg_view('output/url', array(
			'text' => $item_owner->name,
			'href' =>  'profile/'.$item_owner->username
		));
echo '</td>';
echo '</tr>';
echo '<tr><td>Lifecycle Type:</td>';
echo '<td>';
echo elgg_view('input/radio', array(
			   'name' => 'item[lifecycle]',
			   'align' => 'horizontal',
				'id' => 'lifecycle',
				'options' => array('Depleting' => '1', 'Depreciating' => '2'),
				'default' => 2,
				'value' => $entity->lifecycle
				));
echo '</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';
echo '</table>';

echo '<table width=100%>';
echo '<tr><td colspan = 3><b>Individual Characteristics</b></td>';
echo '<tr><td colspan = 3>'.
/**
 * 
 *		CLONE NODE ARRAY DATA
 *	
 */
// text field pair with node cloning
// eg. component name => component value
// these will be parallel arrays, so $name[0] pairs with $value[0]
// these are also saved automagically
$names = $entity->this_component_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->this_component_values;
if ($values && !is_array($values)) {
	$values = array($values);
}
// iterate throught the names/values to populate what we have
foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_names][]',
		'value' => $name,
		'style' => 'width: 28%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_values][]',
		'value' => $values[$key],
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
	echo '</div>';
}
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_names][]',
		'style' => 'width: 28%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
// included a blank node at end of page. Did not included it here to prevent accidental deletion.  Consider placing it inside the calling jscript (above).
// add a button to clone the blank node
	echo elgg_view('output/url', array(
		'text' => 'Add Characteristic',
		'href' => '#',
	//	'class' => 'elgg-button elgg-button-action car-components-clone-action' // unique class for jquery
		'class' => 'clone-this-characteristic-action' // unique class for jquery
	));

//	echo '<table width=100%>';
echo '</td></tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '<tr><td colspan=3><b>Acquisition</b></td>';
echo '<tr><td >Condition</td>';
echo '<td colspan=2>'.elgg_view('input/radio', array(
					   'name' => 'item[acquisiton_condition]',
					   'align' => 'horizontal',
						'id' => 'acquisiton_condition',
						'options' => array('New' => 'new', 'Used' => 'used', 'Refurbished'=>'refurbished'),
						'value' => $entity->acquisiton_condition
						)).'</td>';
echo '</tr>';
echo '<tr><td>Previous Owner:</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[acquired_from]', 'value' => $entity->acquired_from)).'</td>';
echo '</tr>';
echo '<tr><td>Date Acquired:</td>';
echo '<td colspan=2>'.elgg_view('input/date', array('name' => 'item[acquisition_date]', 'style' => 'width: 30%', 'value' => $entity->acquisition_date)).'</td>';
echo '</tr>';
echo '<tr><td>Amount Paid:</td>';
echo '<td colspan=2>$'.elgg_view('input/text', array('name' => 'item[acquisition_cost]', 'style' => 'width: 30%', 'value' => $entity->acquisition_cost)).'</td>';
echo '</tr>';
echo '<tr><td>Purchase Order #:</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[po_no]', 'value' => $entity->po_no)).'</td>';
echo '</tr>';
echo '<tr><td>Invoice #:</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[inv_no]', 'value' => $entity->inv_no)).'</td>';
echo '</tr>';
echo '<tr><td>Invoice Location:</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[invoice_location]', 'value' => $entity->invoice_location)).'</td>';
echo '</tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '<tr><td colspan=3><b>Valuation</b></td>';
echo '<tr><td >Current Value:</td>';
echo '<td colspan=2>$'.elgg_view('input/text', array('name' => 'item[value]', 'style' => 'width: 30%', 'value' => $entity->value)).'</td>';
echo '</tr>';
echo '<tr><td>Appraiser:</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[appraiser]', 'value' => $entity->appraiser)).'</td>';
echo '</tr>';
echo '<tr><td>Date Appraised</td>';
echo '<td colspan=2>'.elgg_view('input/date', array('name' => 'item[appraisal_date]', 'style' => 'width: 30%', 'value' => $entity->appraisal_date)).'</td>';
echo '</tr>';
echo '<tr><td>Valuation Notes</td>';
echo '<td colspan=2>'.
      elgg_view('input/longtext', array('name' => 'item[valuation_notes]', 'value' => $entity->valuation_notes));
echo '</td>';
echo '</tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '<tr><td colspan=3><b>Manufacture</b></td>';
echo '</tr>';
echo '<tr><td>Year of Manufacture</td>';
echo '<td colspan=2></td>';
echo '</tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '<tr><td colspan=3><b>Dispossession</b></td>';
echo '</tr>';
echo '<tr><td>For Sale?</td>';
echo '<td colspan=2>'.elgg_view('input/radio', array(
					   'name' => 'item[for_sale]',
					   'align' => 'horizontal',
						'id' => 'for_sale',
						'options' => array('Yes' => '1', 'No' => '0'),
						'value' => $entity->for_sale
						)).'</td>';
echo '<tr><td>New Owner</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[new_owner]', 'value' => $entity->new_owner)).'</td>';
echo '</tr>';
echo '<tr><td>Value Received</td>';
echo '<td colspan=2>'.elgg_view('input/text', array('name' => 'item[value_received]', 'style' => 'width: 30%', 'value' => $entity->value_received)).'</td>';
echo '</tr>';
echo '<tr><td>Date Dispossessed</td>';
echo '<td colspan=2>'.elgg_view('input/date', array('name' => 'item[dispossessed_date]', 'style' => 'width: 30%', 'value' => $entity->dispossessed_date)).'</td>';
echo '</tr>';
echo '<tr><td>Dispossession Reason</td>';
echo '<td colspan=2>'.elgg_view('input/radio', array(
					   'name' => 'item[dispossessed_reason]',
					   'align' => 'horizontal',
						'id' => 'dispossessed_reason',
						'options' => array('Sold'=>'sold', 'Given'=>'given', 'Traded'=>'traded', 'Stolen'=>'stolen', 'Lost'=>'lost', 'Scrapped'=>'scrapped'),
						'value' => $entity->dispossessed_reason
						)).'</td>';
echo '</tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '</table>';

// add a submit button

echo '<br><br>';
echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => 'Save'));
echo '</div>';

echo '<div class="this_characteristics">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_names][]',
		'style' => 'width: 28%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
echo '</div>';