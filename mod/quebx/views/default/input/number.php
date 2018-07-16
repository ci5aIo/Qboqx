<?php
/**
 * Elgg text input
 * Displays a number input field
 *
 * @package Elgg
 * @subpackage QuebX
 *
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-text {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-text";
}

$defaults = array(
	'value' => 0,
	'min'   => 0,
	'max'   => 10,
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

if ($vars['max'] == 0){
    unset ($vars['max']);
}
?>
<input type="number" <?php echo elgg_format_attributes($vars); ?> />
