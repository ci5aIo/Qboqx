<?php
/**
 * Elgg places widget edit
 *
 * @package Elggplaces
 */

// set default value
if (!isset($vars['entity']->places_num)) {
	$vars['entity']->places_num = 4;
}

$params = array(
	'name' => 'params[places_num]',
	'value' => $vars['entity']->places_num,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
);
$dropdown = elgg_view('input/select', $params);

?>
<div>
	<?php echo elgg_echo('places:num'); ?>:
	<?php echo $dropdown; ?>
</div>
