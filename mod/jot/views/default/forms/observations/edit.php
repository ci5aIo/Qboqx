Form: jot/views/default/forms/observations/edit.php

<?php
/**
 * observations edit form body
 *
 * @package ElggPages	
 */
/* // Values populated from jot\pages\jot\edit.php => observations_prepare_form_vars()
$title            = $vars['title'];
$body             = $vars['body'];
$tags             = $vars['tags'];
$aspect           = $vars['aspect'];
$access_id        = $vars['access_id'];
$observation_guid = $vars['guid'];
*/
$section     = get_input('section');
$aspect      = 'observation';

$variables = elgg_get_config("{$aspect}s");

//$variables        = elgg_get_config('observations');

// 10/31/2014 - Added by scottj
	$element_type = get_input('element_type');
	echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
//
echo '<br>guid: '. $vars['guid'];
echo '<br>element type: '.$element_type;
echo '<br>aspect: '.$aspect;
echo '<br>section:       '.$section;
//echo elgg_dump($vars);

foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("observations:$name") ?></label>
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
echo '<br>title: '.$vars['title'];

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'observation_guid',
		'value' => $vars['guid'],
	));
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
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
echo elgg_view('input/hidden', array('name' => 'aspect', 'value' => $aspect));


echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';