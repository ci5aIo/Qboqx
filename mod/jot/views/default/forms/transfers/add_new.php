<!--Form: jot\views\default\forms\purchases\add.php-->
<?php

//$variables = elgg_get_config('purchase');
$guid        = $vars['guid'];
$referrer    = $vars['referrer'];
$ts          = time();
$title       = elgg_extract('title', $vars, 'New purchase');
$tags        = "";
$entity      = get_entity($guid);

//echo elgg_dump($vars);
echo "<!--referrer: $referrer-->";
echo elgg_view('input/hidden', array('name' => 'referrer' , 'value' => $referrer));
echo elgg_view('input/hidden', array('name' => 'subtype'  , 'value' => 'purchase'));
echo elgg_view('input/hidden', array('name' => 'jot_type' , 'value' => 'purchase'));
echo elgg_view('input/hidden', array('name' => 'state'    , 'value' => '1'));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars  = elgg_get_plugin_setting('jot_numchars' , 'jot');
if($numchars == ''){
	$numchars = '250';
}

if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

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
	$('.clone-individual_item-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_items').html();
		$(html).insertBefore('.new_individual_item');
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
echo '<table width = "100%">
      	<tr>
	      <td><label>Purchase Title</label></td>
	      <td colspan=2>'.elgg_view("input/text", array(
									"name" => "title",
									"value" => $title,
									)).
		  '</td>
      </tr>';

echo '<tr>
      	<td colspan = 3><b>Items</b>
      	</td>
      </tr>
      <tr>
        <td colspan = 3>';

$names = $entity->this_item_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->this_item_values;
if ($values && !is_array($values)) {
	$values = array($values);
}

// iterate throught the names/values to populate what we have
foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
//	echo '<div>'.
	echo elgg_view('input/text', array(
			'name' => 'jot[this_item_names][]',
			'value' => $name,
			'style' => 'width: 65%;'
		)).
	    elgg_view('input/text', array(
			'name' => 'jot[this_item_values][]',
			'value' => $values[$key],
			'style' => 'width: 25%;'
		)).
	    '<a href="#" class="remove-node">remove</a>
	    ';
//	    </div>';
}
//	echo '<div>'.
	echo elgg_view('input/text', array(
			'name' => 'jot[this_item_names][]',
			'style' => 'width: 65%;'
		)).
		elgg_view('input/text', array(
			'name' => 'jot[this_item_values][]',
			'style' => 'width: 25%;'
		)).
		'<a href="#" class="remove-node">remove</a>
		<div class="new_individual_item"></div>';
//       </div>'
	echo '</td></tr></table>';

	// add a button to clone the blank node
    echo elgg_view('output/url', array(
			'text' => 'Add item row',
			'href' => '#',
			'class' => 'clone-individual_item-action' // unique class for jquery
		));
	
echo '<p><p>'.elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:purchase')));

echo '<div class="individual_items">
      	<div>'.
      	elgg_view('input/text', array(
			'name' => 'jot[this_item_names][]',
			'style' => 'width: 65%;'
		)).
		elgg_view('input/text', array(
			'name' => 'jot[this_item_values][]',
			'style' => 'width: 25%;'
		)).
		'<a href="#" class="remove-node">remove</a>
		</div>
	 </div>';

