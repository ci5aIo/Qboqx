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
$item_owner = get_entity($entity->owner_guid);
if (elgg_instanceof($entity, 'object','market')) {
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
}

$title = $entity['title'];
$body = $vars['marketbody'];
$tags = $vars['markettags'];
$category = $vars['marketcategory'];
$access_id = $vars['access_id']; 

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
</script>

<?php
echo '<br><br>';
//echo var_dump ($item_owner);
//echo '<br>'.$vars['entity'].'<br>GUID: '.$guid.'<br>Entity: '.var_dump($entity).'<br>';
//echo '<br>Owner ID: '.$entity->owner_guid.'<br>';
echo '<br>Owner Name: '.$item_owner->name.'<br>';
echo "<label>";
echo elgg_echo("title");
echo "&nbsp;<small><small>" . elgg_echo("market:title:help") . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "markettitle",
				"value" => $title,
				));
echo "</label></p>";

echo '<br>Category: '.$entity->marketcategory.'<br>';

echo "<p><label>" . elgg_echo("market:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $entity->description));
}
echo "</label></p>";

/**
 * 
 *		SINGLE NODE, SINGLE DATA
 * 
 */
// add in a simple text field
// for single point data use the name 'item' in an array
// such that item[manufacturer] will translate to the saved attribute $entity->manufacturer
// this save action is handled automagically
echo '<label>Manufacturer</label>';
echo elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer));

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
 echo '<br><br>';
echo '<label>Components</label>';
	echo ':'.count($names).'<br>';
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
		'style' => 'width: 200px;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'value' => $values[$key],
		'style' => 'width: 200px;'
	));
	echo '<a href="#" class="car-components-remove">remove</a>';
	echo '</div>';
}

// now we add a new empty set we can clone
// wrap it in a div with a unique class we can identify

echo '<div class="car-components-clone">';
	echo '<div>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'style' => 'width: 200px;'
	));
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'style' => 'width: 200px;'
	));
	echo '<a href="#" class="car-components-remove">remove</a>';
   	echo '</div>';
echo '</div>'; // end of car-components-clone

// add a button to clone the node
echo elgg_view('output/url', array(
	'text' => 'Add Another',
	'href' => '#',
	'class' => 'elgg-button elgg-button-action car-components-clone-action' // unique class for jquery
));
echo '<br><br>';

// now we add some js to clone the node
?>

<script>
	$(document).ready(function() {
		
		// clone a node
		$('.car-components-clone-action').on('click', function(e){
			e.preventDefault();
			
			// clone the node
			var html = $('.car-components-clone').html();
			
			$(html).insertBefore('.car-components-clone-action');
		});
		
		// remove a node
		$('.car-components-remove').on('click', function(e){
			e.preventDefault();
			
			// remove the node
			$(this).parents('div').eq(0).remove();
		});
	});
</script>

<?php

/**
 * 
 *		ENTITY PICKER
 * (uses plugin - elgg_tokeninput)
 * 
 * note - this requires a custom action
 * defined in actions/custom/{type}/{level}.php
 * in this case actions/custom/car/family.php
 * 
 */

$drivers = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'driver',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => false,
	'callback' => false // because we don't need the user entity, this makes the query a bit more efficient
));

$guids = array();
foreach ($drivers as $d) {
	$guids[] = $d->guid;
}

echo '<label>Drivers</label><br>';

echo elgg_view('input/tokeninput', array(
	'value' => $guids,
	'name' => 'drivers',
	'callback' => 'cars_user_picker_callback', // this is a php function that searches users in given criteria - see start.php
	'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
	'multiple' => true,
));
echo '<br><br>';

// lets do another one for fun
// lets attach shoes
$shoes = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'market',
	'relationship' => 'car_shoes',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => false,
	'callback' => false // because we don't need the user entity, this makes the query a bit more efficient
));

$guids = array();
foreach ($shoes as $s) {
	$guids[] = $s->guid;
}

echo '<label>Shoes</label><br>';

echo elgg_view('input/tokeninput', array(
	'value' => $guids,
	'name' => 'shoes',
	'callback' => 'cars_shoe_picker_callback', // this is a php function that searches shoes in given criteria - see start.php
	'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
	'multiple' => true,
));




/**
 * 
 *		FILE UPLOAD
 * 
 */

echo '<br><br>';
echo '<label>FILE UPLOAD</label>';
echo elgg_view('input/file', array(
	'name' => 'upload'
));

// add a submit button

echo '<br><br>';
echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => 'Save'));
echo '</div>';
echo '<div><b><FONT FACE="arial" SIZE=3>'.elgg_view("input/text", array(
													"name" => "markettitle",
													"value" => $title,
													)).'</FONT></b></div>';
echo '<FONT FACE="arial" SIZE=2></FONT><div><b><FONT FACE="arial" SIZE=2>Description</FONT></b></div>';
echo '<FONT FACE="arial" SIZE=2></FONT><div><b><FONT FACE="arial" SIZE=2>Family Data</FONT></b></div>';
echo '<div align="center">';
echo '<table BORDER="1" width = "100%"><tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Manufacturer:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">'.elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer)).'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Brand:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>'.elgg_view('input/text', array('name' => 'item[brand]', 'value' => $entity->brand)).'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Model #:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=34%><FONT FACE="arial" SIZE=2>'.elgg_view('input/text', array('name' => 'item[model]', 'value' => $entity->model)).'</FONT></td>';
echo '<td align=right VALIGN=bottom WIDTH=17%><FONT FACE="arial" SIZE=2>Part #</FONT></td>';
echo '<td VALIGN=bottom WIDTH=17%><FONT FACE="arial" SIZE=2>'.elgg_view('input/text', array('name' => 'item[part]', 'value' => $entity->part)).'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Description:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>';
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='6' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
HTML;
	echo "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $entity->description));
}
echo '</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Category:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">'.$entity->marketcategory.'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Collection:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[Group or system to which this item is a component]</FONT></td>';
echo '</tr>';
echo '</table></div>';
echo '<div align="center">';
echo '<table BORDER="0" width = "100%"><tr><td colspan=4 VALIGN=bottom WIDTH=100% ALIGN="CENTER"><FONT FACE="arial" SIZE=2>[image]<br>';
echo '</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Warranty:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>'.elgg_view('input/text', array('name' => 'item[warranty]', 'value' => $entity->warranty)).'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Warrantor:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>'.elgg_view('input/text', array('name' => 'item[warrantor]', 'value' => $entity->warrantor)).'</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Owner:</FONT></td>';
echo '<td colspan=3 VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>';
		echo elgg_view('output/url', array(
			'text' => $item_owner->name,
			'href' =>  'profile/'.$item_owner->username
		));
echo '</td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Lifecycle Type:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=34%><FONT FACE="arial" SIZE=2>';
echo elgg_view('input/radio', array(
			   'name' => 'lifecycle',
				'id' => 'lifecycle',
				'options' => array('Depleting' => '1', 'Depreciating' => '2')
				));
echo '</FONT></td>';
echo '<td VALIGN=bottom WIDTH=20%><FONT FACE="arial" SIZE=2>Quantity on Hand </FONT><a href="7F0EBFDD8C87A28943B4D94BCA51288193232902.html"><FONT FACE="arial" SIZE=2>[..]</FONT></A></td>';
echo '<td VALIGN=bottom WIDTH=14%><FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '</table></div>';
echo '<div align="center">';
echo '<table BORDER="1" width = "100%"><tr><td colspan=3 VALIGN=bottom WIDTH=100%><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><b><FONT FACE="arial" SIZE=2>Family Characteristics</FONT></b></td>';
echo '</tr>';
/**
 * 
 *		CLONE NODE ARRAY DATA
 *	
 */
// text field pair with node cloning
// eg. component name => component value
// these will be parallel arrays, so $name[0] pairs with $value[0]
// these are also saved automagically
// now we add some js to clone the node
?>

<script>
	$(document).ready(function() {
		
		// clone a node
		$('.car-components-clone-action').on('click', function(e){
			e.preventDefault();
			
			// clone the node
			var html = $('.car-components-clone').html();
			
			$(html).insertBefore('.car-components-clone-action');
		});
		
		// remove a node
		$('.car-components-remove').on('click', function(e){
			e.preventDefault();
			
			// remove the node
			$(this).parents('div').eq(0).remove();
		});
	});
</script>

<?php
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
    echo '<tr><td VALIGN=bottom WIDTH=21%><FONT FACE="arial" SIZE=2>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'value' => $name
	));
    echo '</FONT></td>';
    echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'value' => $values[$key]
	));
	echo '</FONT></td>';
    echo '<td VALIGN=bottom WIDTH=10%><FONT FACE="arial" SIZE=2>';
	echo '<a href="#" class="car-components-remove">remove</a>';
	echo '</FONT></td>';
	echo '</tr>';
	echo '</div>';
}

// now we add a new empty set we can clone
// wrap it in a div with a unique class we can identify

echo '<div class="car-components-clone">';
//	echo '<div>';
    echo '<tr><td VALIGN=bottom WIDTH=21%><FONT FACE="arial" SIZE=2>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_names][]',
		'value' => '[Characteristic]'
	));
    echo '</FONT></td>';
    echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>';
	echo elgg_view('input/text', array(
		'name' => 'item[component_values][]',
		'value' => '[Value]'
	));
	echo '</FONT></td>';
    echo '<td VALIGN=bottom WIDTH=10%><FONT FACE="arial" SIZE=2>';
	echo '<a href="#" class="car-components-remove">remove</a>';
	echo '</FONT></td>';
	echo '</tr>';
//   	echo '</div>';
echo '</div>'; // end of car-components-clone
	echo '<td colspan=3 align=right>';
	
	// add a button to clone the node
	echo elgg_view('output/url', array(
		'text' => 'Add Characteristic',
		'href' => '#',
	//	'class' => 'elgg-button elgg-button-action car-components-clone-action' // unique class for jquery
		'class' => 'elgg-button-action car-components-clone-action' // unique class for jquery
	));
	
	echo '</td>';
	echo '</tr>';
  
echo '</table>';


echo '<table>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Individual Characteristics</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=6%><b><FONT FACE="arial" SIZE=2>Item</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=94%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=6%><FONT FACE="arial" SIZE=2>[#]</FONT></td>';
echo '<td VALIGN=bottom WIDTH=25%><FONT FACE="arial" SIZE=2>[Characteristic]</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2>[Value]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Components</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69% ALIGN="RIGHT"><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><a href="072EF0C5A9E0417D844AFB41479B5DBC21A07089.html"><FONT FACE="arial" SIZE=2>[add new component]</FONT></A></td>';
echo '</tr>';
echo '</table></div>';
echo '<div align="center">';
echo '<table BORDER="1" width = "100%"><tr><td VALIGN=bottom WIDTH=100%><FONT FACE="arial" SIZE=2>[component of asset]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><b><FONT FACE="arial" SIZE=2>Features</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '</table></div>';
echo '<div align="center">';
echo '<table BORDER="1" width = "100%"><tr><td VALIGN=bottom WIDTH=100%><FONT FACE="arial" SIZE=2>&#164; </FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Accessories</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=34%><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><b><FONT FACE="arial" SIZE=2>Location</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=35% ALIGN="RIGHT"><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><a href="072EF0C5A9E0417D844AFB41479B5DBC21A07089.html"><FONT FACE="arial" SIZE=2>[add new accessory]</FONT></A></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>[accessory for asset]</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><b><FONT FACE="arial" SIZE=2>Documentation</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Supplies</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>[supply for asset]</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2><br>';
echo '</FONT><b><FONT FACE="arial" SIZE=2>Operational Expense</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Electricity</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[Y&frasl;N]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>License Fee</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[cost]&frasl;yr</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Valuation</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Current Value:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Appraiser:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[link to appraiser]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Date Appraised</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Notes</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Manufacture</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Year of Manufacture</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Acquisition</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Condition</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[New, Used, Refurbished]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Previous Owner:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[link to vendor]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Date Acquired:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Amount Paid:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Purchase Order #:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Invoice #:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2 COLOR="#C0C0C0"></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Invoice Location:</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[link to location]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><b><FONT FACE="arial" SIZE=2><br>';
echo '</FONT></b><b><FONT FACE="arial" SIZE=2>Dispossession</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>For Sale?</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[Y&frasl;N]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Value Received</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[$, other]</FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Date Dispossessed</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%>&nbsp;<FONT FACE="arial" SIZE=2 COLOR="#C0C0C0"></FONT></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=31%><FONT FACE="arial" SIZE=2>Dispossession Reason</FONT></td>';
echo '<td VALIGN=bottom WIDTH=69%><FONT FACE="arial" SIZE=2 COLOR="#C0C0C0">[sold, given, traded, stolen, lost, scrapped]</FONT></td>';
echo '</tr>';
echo '</table></div>';
echo '<div>&nbsp;</div>';
echo '<FONT FACE="arial" SIZE=2></FONT><div><b><FONT FACE="arial" SIZE=2>History</FONT></b></div>';
echo '<div align="center">';
echo '<table BORDER="1" width = "100%"><tr><td VALIGN=bottom WIDTH=11%><b><FONT FACE="arial" SIZE=2>Date</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=54%><b><FONT FACE="arial" SIZE=2>Event</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=19%><b><FONT FACE="arial" SIZE=2>Provider</FONT></b></td>';
echo '<td VALIGN=bottom WIDTH=17% ALIGN="RIGHT"><b><FONT FACE="arial" SIZE=2>Value</FONT></b></td>';
echo '</tr>';
echo '<tr><td VALIGN=bottom WIDTH=11%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '<td VALIGN=bottom WIDTH=54%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '<td VALIGN=bottom WIDTH=19%>&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '<td VALIGN=bottom WIDTH=17% ALIGN="RIGHT">&nbsp;<FONT FACE="arial" SIZE=2></FONT></td>';
echo '</tr>';
echo '</table></div>';

