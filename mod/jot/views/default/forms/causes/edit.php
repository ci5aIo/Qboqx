Form: jot/views/default/forms/causes/edit.php

<?php
/**
 * causes edit form body
 *
 * @package ElggPages	
 */

$title      = $vars['title'];
$body       = $vars['body'];
$tags       = $vars['tags'];
$aspect     = $vars['aspect'];
$access_id  = $vars['access_id'];
$cause_guid = $vars['guid'];
$section    = get_input('section');

//$variables = causes_prepare_form_vars();
$variables = elgg_get_config('causes');

$element_type = get_input('element_type');
echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
//
echo '<br>guid: '. $cause_guid;
echo "<br>aspect: $aspect";
echo '<br>element type: '.$element_type;
echo '<br>section:       '.$section;
// echo elgg_dump($variables);
// echo elgg_dump($vars['root_cause']);
// echo elgg_dump($vars);

foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("causes:$name") ?></label>
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
		'name' => 'cause_guid',
		'value' => $cause_guid,
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