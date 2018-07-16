Form: jot/views/default/forms/transfers/edit.php

<?php
/**
 * transfers edit form body
 *
 * @package ElggPages	
 */
/* // Values populated from jot\pages\jot\edit.php => transfers_prepare_form_vars()
$title            = $vars['title'];
$body             = $vars['body'];
$tags             = $vars['tags'];
$aspect           = $vars['aspect'];
$access_id        = $vars['access_id'];
$transfer_guid = $vars['guid'];
*/
$section   = get_input('section', 'ownership');
$aspect    = 'transfer';
$variables = elgg_get_config("[$aspect]_[$section]");
/*
$variables = jot_prepare_form_vars($aspect      = $aspect,
	                               $jot         = null, 
		                           $item_guid   = $guid, 
		                           $referrer    = $referrer, 
		                           $description = null,
								   $section     = $section);
*/
// 10/31/2014 - Added by scottj
	$element_type = get_input('element_type');
	echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
//
//echo '<br>$item: '.$item;
echo '<br>guid: '         . $vars['guid'];
echo '<br>element type: ' .$element_type;
echo '<br>aspect: '       .$aspect;
echo '<br>section: '      .$section;
echo elgg_dump($variables);

foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("transfers:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'transfer_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}
echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';