<?php
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$selected_marketcategory = $vars['entity']->marketcategory;

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'family'));
  echo elgg_view('input/hidden', array('name'=>'item[family]', 'value' => 'family'));
  $this_level = '/car';
  $next_level = '/family'; //probably not the right way to reference the next level.
}
$item_owner = get_entity($entity->owner_guid);
if (elgg_instanceof($entity, 'object','market')) {
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
}

$title = $entity['title'];
$body = $entity['description'];
//$body = $vars['marketbody'];
$tags = $entity->tags;
//$tags = $entity->labels;
$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
	
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

$components = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'component',
	'container_guid' => $entity->guid
));

$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$groups = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'shared',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => false,
	'callback' => false // because we don't need the user entity, this makes the query a bit more efficient
));
	
//CSS code for class hoverhelp:
echo '<style>';
echo 'span.hoverhelp {background: #F0F0EE;}';
//echo 'span.hoverhelp {border-bottom: thin dotted; border-top: thin dotted; background: #F0F0EE;}';
echo '</style>';

?>
<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		$("#"+cntfield).html(maxlimit - field.value.length);
	}
}
$(document).ready(function() {
	
	// clone characteristics node
	$('.clone-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.characteristics').html();
/* experimental.  Want to be able to remove the <div class="characteristics"> from the visible page.
 * Does not work.	
        var html = $("echo \'<div>\';");
		var html .=$("echo elgg_view(\'input/text\', array(");
		var html .=$("\'name\' => \'item[component_names][]\',");
		var html .=$("\'style\' => \'width: 28%;\'");
		var html .=$("));");
		var html .=$("echo elgg_view(\'input/text\', array(");
		var html .=$("\'name\' => \'item[component_values][]\',");
		var html .=$("\'style\' => \'width: 65%;\'");
		var html .=$("));");
		var html .=$("echo \'<a href=\"#\" class=\"remove-node\">remove</a>\';");
		var html .=$("echo \'</div>\';")
		
		$(html).insertBefore('.clone-characteristic-action');
*/		$(html).insertBefore('.new_characteristic');
	});

	// clone features node
	$('.clone-feature-action').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.features').html();
		$(html).insertBefore('.new_feature');
//		$(html).insertBefore('.clone-feature-action');
	});
	// clone characteristics node
	$('.clone-individual-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_characteristics').html();
		$(html).insertBefore('.new_individual_characteristic');
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
//echo $content;
//echo elgg_dump($entity);
//echo '<br>'.$vars['entity'].'<br>GUID: '.$guid.'<br>Entity: '.var_dump($entity).'<br>';
//echo '<br>Owner ID: '.$entity->owner_guid.'<br>';
//echo var_dump ($vars).'<br>';
// echo 'Time Updated: '.$entity->time_updated.'<br>';
// echo 'GUID: '.$entity->guid.'<br>';
// echo $body.'<br>';
// echo 'Entity: '.var_dump($entity).'<br>';

/*echo '<div><b><FONT FACE="arial" SIZE=3>'.elgg_view("input/text", array(
													"name" => "markettitle",
													"value" => $title,
													)).'</b>';*/
echo 'Path: mod/market/views/default/forms/market/edit/car/profile.php';
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
	echo '<tr><td>#</td><td><b>Family Members</b></td><td>Manufacturer Serial #</td><td>Owner Serial #</td></tr>';
		foreach ($individuals as $i) {
			echo '<tr><td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array(
				'text' => $i->title,
				'href' =>  'market/edit_more/'.$i->guid.$this_level.$next_level.'/individual'
	//			'href' =>  'market/edit_more/'.$i->guid.$vars['h'].$next_level
			)).'</td>';
			echo '<td>'.$i->serial01.'</td>';
			echo '<td>'.$i->serial02.'</td>'; // whatever info you want to output about the individual
			echo '<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $entity->guid))).'</td>';
			echo '</tr>';
		}
	echo '</table>';
}
/*
else {echo '<div><b><FONT FACE="arial" SIZE=3>'.elgg_view("input/text", array(
													"name" => "markettitle",
													"value" => $title,
													));
  }*/

echo '<br>';
echo '<table width = "100%">';
echo '<tr><td colspan=1 WIDTH=20%>Category:</td>';
echo '<td colspan=6  WIDTH=80%>'. elgg_echo("market:category:{$category}").'</td>';
echo '</tr>';
echo '<tr><td>Owner:</td>';
echo '<td colspan=6>';
		echo elgg_view('output/url', array(
			'text' => $item_owner->name,
			'href' =>  'profile/'.$item_owner->username
		));
echo '</td>';
echo '</tr>';
echo '<tr><td colspan=1 WIDTH=20%>Manufacturer:</td>';
//echo '<td colspan=3  WIDTH=80%>'.elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer)).'</td>';
echo '<td colspan=6  WIDTH=80%>'.elgg_view('input/text', array('name' => 'item[manufacturer]', 
                                            'value' => $entity->manufacturer,
											//'match_on' => 'manufacturer',
										   )).'</td>';
echo '</tr>';
echo '<tr><td colspan=1 WIDTH=20%>Brand:</td>';
echo '<td colspan=6  WIDTH=80%>'.elgg_view('input/text', array('name' => 'item[brand]', 'value' => $entity->brand)).'</td>';
echo '</tr>';
echo '<tr><td colspan=1 WIDTH=20%>Model #*:&nbsp;';
echo '<span class="hoverhelp">[?]';
echo '<span style="width:500px;">* Provide a unique Model # or SKU to manage inventory levels.</span>';
echo '</span>';
echo '</td>';
echo '<td colspan=1 WIDTH=25%>'.elgg_view('input/text', array('name' => 'item[model]', 'value' => $entity->model)).'</td>';
echo '<td colspan=1 WIDTH=0% nowrap>SKU*:&nbsp;';
echo '<span class="hoverhelp">[?]';
echo '<span style="width:500px;">* Provide a unique Model # or SKU to manage inventory levels.</span>';
echo '</span>';
echo '</td>';
echo '<td colspan=1 WIDTH=25%>'.elgg_view('input/text', array('name' => 'item[sku]', 'value' => $entity->sku)).'</td>';
echo '<td colspan=1 WIDTH=0% nowrap>Part #</td>';
echo '<td colspan=2 WIDTH=25%>'.elgg_view('input/text', array('name' => 'item[part]', 'value' => $entity->part)).'</td>';
echo '</tr>';
echo '<tr><td  WIDTH=20%>Description:</td>';
echo '<td colspan=6  WIDTH=80%>';
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='description' class='mceNoEditor' rows='6' cols='40'
  onKeyDown='textCounter(document.marketForm.description,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.description,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "description", "value" => $body));
//	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $entity->description));
}
echo '</td></tr>';
echo '<tr><td></td>';
echo '<td colspan=6>';
echo elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'large', 'tu' => $entity->time_updated));
echo '</td></tr>';
echo '<tr><td>'.elgg_echo("market:uploadimages") . '&nbsp;';
echo '<span class="hoverhelp">[?]';
echo '<span style="width:500px;">' . elgg_echo("market:imagelimitation") . '</span>';
echo '</span>';
echo '<td colspan=6>';
echo elgg_view("input/file",array('name' => 'upload'));
echo elgg_view('input/gallery/filedrop', array(
	'entity' => $entity,
	'batch_upload_time' => $time
));
echo '</td></tr>';
echo '<tr><td>Access</td>';
echo '<td colspan=6>';
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));	
echo '</td></tr>';
echo '<tr><td>Group Visibility ';
echo '<span class="hoverhelp">[?]';
echo '<span style="width:500px;">Select one or more groups to feature this item.  Begin typing to show matching groups.</span>';
echo '</span>';

echo '</td>';
echo '<td colspan=6>';

$guids = array();
foreach ($groups as $e) {
	$guids[] = $e->guid;
}

echo elgg_view('input/tokeninput', array(
	'value' => $guids,
	'name' => 'groups',
	'callback' => 'group_picker_callback', // this is a php function that searches groups in given criteria - see start.php
	'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
	'multiple' => true,
));
echo '</td></tr>';


echo '<tr><td WIDTH=31%>' . elgg_echo("market:tags").'&nbsp;';  //. '&nbsp;<small><small>' . elgg_echo("market:tags:help");

echo '<span class="hoverhelp">[?]';
echo '<span style="width:500px;">Labels tag items to place them into quebs (tidy little collections).  Separate queb labels with commas.</span>';
echo '</span>';

echo '</td>';
echo '<td colspan=6  WIDTH=69%>';
echo elgg_view("input/tags", array(
				"name" => "item[tags]",
//				"name" => "item[labels]",
				"value" => $tags,
				));
echo "</label>	";
echo '</td></tr>';
echo '<tr><td  WIDTH=20%>Warranty:</td>';
echo '<td colspan=6>'.elgg_view('input/text', array('name' => 'item[warranty]', 'value' => $entity->warranty)).'</td>';
echo '</tr>';
echo '<tr><td>Warrantor:</td>';
echo '<td colspan=6>'.elgg_view('input/text', array('name' => 'item[warrantor]', 'value' => $entity->warrantor)).'</td>';
echo '</tr>';
echo '<tr><td>Lifecycle Type:</td>';
echo '<td colspan=3>';
echo elgg_view('input/radio', array(
			   'name' => 'item[lifecycle]',
			   'align' => 'horizontal',
				'id' => 'lifecycle',
				'options' => array('Depleting' => '1', 'Depreciating' => '2'),
				'default' => 2,
				'value' => $entity->lifecycle
				));
echo '</td>';
echo '<td width=35% nowrap>Qty on Hand: ';
	echo '<span class="hoverhelp">[?]';
	echo '<span style="width:500px;"><p>How many identical ' . elgg_echo($entity->title) . ' items are available?</p><p>Click [add +] to clone this item or to increase the quantity on hand.</span>';
	echo '</span>';
echo '</td>';
if (count($individuals)>$value) {
   $value = count($individuals);
};

echo '<td>'.$value.'</td>';
echo '<td nowrap>';
	echo '<span class="hoverhelp">';
echo elgg_view('output/url', array(
			'text' => '[add +]',
//			'href' =>  'market/edit_more/'.$guid.'/'.$category.$next_level
			'href' =>  'market/inventory/'.$guid.'/'.$category.$next_level
		));
	echo '<span style="width:500px;">Clone this item or increase the quantity on hand.</span>';
	echo '</span>';
echo '</td>';
echo '</tr>';
echo '</table>';

echo '<br>';

echo '<table width=100%>';
echo '<tr><td width = 100%><b>Family Characteristics</b>&nbsp;';
	echo '<span class="hoverhelp">[?]';
	echo '<span style="width:500px;"><p>Family Characteristics are characteristics that are common to all members of this family of '. elgg_echo("market:category:{$category}").'.  Examples include Height, Weight, Fuel Type, etc.</p><p>Click [add characteristic] to add another.</span>';
	echo '</span>';
echo '</td>';
echo '<td nowrap>';
// add a button to clone the blank node
	echo '<span class="hoverhelp">';
	echo elgg_view('output/url', array(
		'text' => '[add characteristic]',
		'href' => '#',
	//	'class' => 'elgg-button elgg-button-action car-components-clone-action' // unique class for jquery
		'class' => 'clone-characteristic-action' // unique class for jquery
	));
	echo '<span style="width:500px;">Add another characteristic.</span>';
	echo '</span>';
echo '</td>';
echo '<tr><td colspan=2>';
/**
 * 
 *		CLONE NODE ARRAY DATA
 *	
 */
// text field pair with node cloning
// eg. component name => component value
// these will be parallel arrays, so $name[0] pairs with $value[0]
// these are also saved automagically
$names = $entity->component_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->component_values;
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
		'name' => 'item[component_names][]',
		'value' => $name,
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'value' => $values[$key],
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
	echo '</div>';
}
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';

	echo '<div class="new_characteristic"></div>';

echo '<td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';

echo '<table width="100%">';
echo '<tr><td colspan=3 width=85%><b>Components</b>&nbsp;';
	echo '<span class="hoverhelp">[?]';
	echo '<span style="width:500px;"><p>Components are distinct, manageable items that are part of this  ' . elgg_echo($entity->title) . ' item. Examples include Wheels, Engine or Body.  Components can have components themselves.  Any work done to a component rolls up to its parent.</p><p>Click [add new component] to add a new component item.</span>';
	echo '</span>';
echo '</td>';
echo '<td colspan=1></td><td nowrap>'.
	elgg_view('output/url', array(
		'text' => '[add new component]', 
		'href' => elgg_add_action_tokens_to_url("action/market/add/element?element_type=component&guid=" . $entity->guid))).'</td>';
echo '</tr>';
//echo '<tr><td colspan=3>';
	if ($components) {
	foreach ($components as $i) {
		echo '<tr>';
			echo '<td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/edit/'.$i->guid)).'</td>';
			echo '<td>'.$i->serial01.'</td>';
			echo '<td>'.$i->serial02.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $i->guid))).'</td>';
		echo '</tr>';
	}
}	

/*echo '</td></tr>';
echo '<tr><td colspan=3>'.elgg_view('input/text', array('name' => 'item[component]', 'value' => $entity->component)).'</td>';
echo '</tr>';*/
echo '<tr><td colspan=5>&nbsp;</td></tr>';
echo '</table>';

echo '<table width = "100%">';
echo '<tr><td width=85% colspan=4><b>Features</b></td>';
	echo '<td>';
	// add a button to clone the blank node
		echo elgg_view('output/url', array(
			'text' => '[add feature]',
			'href' => '#',
			'class' => 'clone-feature-action' // unique class for jquery
		));
	echo '</td>';
echo '</tr>';
echo '<tr><td colspan=5><ul style="list-style-type:square">';
$features = $entity->features;
if ($features && !is_array($features)) {
	$features = array($features);
}

// iterate throught the names/values to populate what we have
foreach ($features as $key => $feature) {
	if ($feature === '') {
		// don't show empty values
		continue;
	}
	
//	echo '<div>';
	echo '<li>';
	echo elgg_view('input/text', array(
		'name' => 'item[features][]',
		'value' => $feature,
		'style' => 'width: 90%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
	echo '</li>';
//	echo '</div>';
}
//	echo '<div>';
	echo '<li>';
	echo elgg_view('input/text', array(
		'name' => 'item[feature_names][]',
		'style' => 'width: 90%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</li>';
//   	echo '</div>';

	echo '<div class="new_feature">';
echo '</ul></td></tr>';
echo '<tr><td colspan=5>&nbsp;</td></tr>';
echo '</table>';

echo '<table width="100%">';
echo '<tr><td colspan=2 id="accessories" width=85%><b>Accessories</b>&nbsp;';
	echo '<span class="hoverhelp">[?]';
	echo '<span style="width:500px;"><p>Accessories are separate, manageable items that enhance this  ' . elgg_echo($entity->title) . ' item. Examples include Trailer, Bike Rack or GPS device.</p><p>Click [add new accessory] to add a new accessory item.</span>';
	echo '</span>';
echo '</td>';
echo '<td colspan=1></td><td nowrap>'.
	elgg_view('output/url', array(
		'text' => '[add new accessory]', 
		'href' => elgg_add_action_tokens_to_url("action/market/add/element?element_type=accessory&guid=" . $entity->guid))).'</td>';
echo '</tr>';
//echo '<tr><td colspan=3>';
	if ($accessories) {
	foreach ($accessories as $i) {
		echo '<tr>';
			echo '<td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'market/edit/'.$i->guid)).'</td>';
			echo '<td>'.$i->location.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => 'Delete', 'href' => elgg_add_action_tokens_to_url('action/market/delete?guid=' . $i->guid))).'</td>';
		echo '</tr>';
	}
}	
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>';

$guids = array();
foreach ($accessories as $s) {
	$guids[] = $s->guid;
}

echo '<label>Select Accessories</label><br>';

echo elgg_view('input/tokeninput', array(
	'value' => $guids,
	'name' => 'accessories',
	'callback' => 'accessory_picker_callback', // this is a php function that searches accessories in given criteria - see start.php
	'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
	'multiple' => true,
));


echo '<table width = "100%">';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=2><b>Documentation</b></td></tr>';
echo '<tr><td colspan=2 nowrap>'.
      elgg_view('output/url', array(
		'text' => '[add new document]', 
		'href' => "file/add?element_type=document&container_guid=" . $entity->guid)).'</td>';
echo '</tr>';
//echo '<tr><td colspan=3>';
	if ($documents) {
	foreach ($documents as $i) {
		echo '<tr>';
			echo '<td>'.$i->guid.'</td>';
			echo '<td>'.elgg_view('output/url', array('text' => $i->title,'href' =>  'file/view/'.$i->guid)).'</td>';
		echo '</tr>';
	}
}	
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>';


echo '<table width = "100%">';
echo '<tr><td colspan=2><b>Supplies</b></td>';
echo '<td colspan=3></td>';
echo '</tr>';
echo '<tr><td colspan=5>[supply for asset]</td>';
echo '</tr>';
echo '<tr><td colspan=5>&nbsp;</td></tr>';
echo '</table>';

echo '<table width = "100%">';
echo '<tr><td colspan=5><b>Operating Expenses</b></td>';
echo '</tr>';
echo '<tr><td>Fuel Cost'./*.elgg_view('input/radio', array(
						   'name' => 'item[power]',
						   'align' => 'vertical',
							'id' => 'power',
							'options' => array('Electricity' => 'Electricity', 'Fuel' => 'Fuel'),
							'value' => $entity->power
							)).*/'
</td>';
echo '<td  colspan=4 nowrap>$'.elgg_view('input/text', array('name' => 'item[pow_cost]', 'style' => 'width: 30%','value' => $entity->pow_cost));
echo ' per '.elgg_view('input/text', array('name' => 'item[pow_cost_units]','style' => 'width: 20%','value' => $entity->pow_cost_units));
echo '</td></tr>';
echo '<tr><td>Maintenance Cost</td>';
echo '<td  colspan=4 nowrap>$'.elgg_view('input/text', array('name' => 'item[maint_cost]', 'style' => 'width: 30%','value' => $entity->maint_cost));
echo ' per '.elgg_view('input/text', array('name' => 'item[maint_cost_units]','style' => 'width: 20%','value' => $entity->maint_cost_units));
echo '</td></tr>';
echo '<tr><td>License Fees</td>';
echo '<td  colspan=4 nowrap>$'.elgg_view('input/text', array('name' => 'item[lic_cost]', 'style' => 'width: 30%','value' => $entity->lic_cost));
echo ' per '.elgg_view('input/text', array('name' => 'item[lic_cost_units]','style' => 'width: 20%','value' => $entity->lic_cost_units));
echo '</tr></table>';

	if (count($individuals)==1) {
			echo '<br>';
			echo '<table width=100%>';
			echo '<tr><td colspan = 3><b>Individual Characteristics</b></td></tr>';
			echo '<tr><td colspan = 3>';
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
					'style' => 'width: 25%;'
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
					'style' => 'width: 25%;'
				));
				echo elgg_view('input/text', array(
					'name' => 'item[this_component_values][]',
					'style' => 'width: 65%;'
				));
				echo '<a href="#" class="remove-node">remove</a>';
			   	echo '</div>';
			// included a blank node at end of page. Did not included it here to prevent accidental deletion.  Consider placing it inside the calling jscript (above).
	            echo '<div class="new_individual_characteristic"></div>';
						// add a button to clone the blank node
				echo elgg_view('output/url', array(
					'text' => 'Add Characteristic',
					'href' => '#',
				//	'class' => 'elgg-button elgg-button-action car-components-clone-action' // unique class for jquery
					'class' => 'clone-individual-characteristic-action' // unique class for jquery
				));
			echo '</td></tr></table>';

			echo '<table width=100%><tr><td colspan=3>&nbsp;</td></tr>';
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
			}

echo '<b>History</b>';
echo '<table width = "100%">';
echo '<tr>';
echo '<td WIDTH=11%><b>Date</b></td>';
echo '<td  WIDTH=54%><b>Event</b></td>';
echo '<td  WIDTH=19%><b>Provider</b></td>';
echo '<td  WIDTH=17% ALIGN="RIGHT"><b>Value</b></td>';
echo '</tr>';
echo '<tr>';
echo '<td  WIDTH=11%></td>';
echo '<td  WIDTH=54%></td>';
echo '<td  WIDTH=19%></td>';
echo '<td  WIDTH=17% ALIGN="RIGHT"></td>';
echo '</tr>';
echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '</table>';

// add a submit button
echo '<br><br>';
echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => 'Save'));
echo '</div>';

// now we add a new empty set we can clone
// wrap it in a div with a unique class we can identify

// Characteristics clone
echo '<div class="characteristics">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
echo '</div>'; // end of Characteristics clone

//Feature clone
echo '<div class="features">';
	echo '<div><li>';
	echo elgg_view('input/text', array(
		'name' => 'item[feature_names][]',
		'style' => 'width: 90%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</li></div>';
echo '</div>'; // end of Feature clone

echo '<div class="individual_characteristics">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_names][]',
		'style' => 'width: 25%;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[this_component_values][]',
		'style' => 'width: 65%;'
	));
	echo '<a href="#" class="remove-node">remove</a>';
   	echo '</div>';
echo '</div>';