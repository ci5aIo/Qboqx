<?php

echo 'Family Level';

$guid = $vars['guid'];
$entity = get_entity($guid);
if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level (in this case family)
  echo elgg_view('input/hidden', array('name'=> 'guid', 'value' => $guid));
  echo elgg_view('input/hidden', array('name'=> 'h', 'value' => $vars['h']));
  echo elgg_view('input/hidden', array('name'=>'level', 'value' => 'family'));
  
  // there is only one family here, but there can be multiple if you look at the shoes example with a dropdown selection
  // this relates to the next form view, which will now be foudn in edit/car/car.php
  echo elgg_view('input/hidden', array('name'=>'item[family]', 'value' => 'family'));
}
echo '<br><br>';

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
echo '<br><br>';
echo '<label>Components</label><br>';
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
		$('.car-components-clone-action').live('click', function(e){
			e.preventDefault();
			
			// clone the node
			var html = $('.car-components-clone').html();
			
			$(html).insertBefore('.car-components-clone-action');
		});
		
		// remove a node
		$('.car-components-remove').live('click', function(e){
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
	//'name' => 'upload'
));

// add a submit button

echo '<br><br>';
echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => 'Submit'));
echo '</div>';