<?php
/**
 * Elgg checkbox input
 * Displays a switchbox input tag.  Adapted from checkbox.
 * 
 * @package Elgg
 * @subpackage Core
 *
 *
 * Pass input tag attributes as key value pairs. For a list of allowable
 * attributes, see http://www.w3schools.com/tags/tag_input.asp
 * 
 * @uses $vars['name']        Name of the checkbox
 * @uses $vars['value']       Value of the checkbox
 * @uses $vars['default']     The default value to submit if not checked.
 *                            Optional, defaults to 0. Set to false for no default.
 * @uses $vars['checked']     Whether this checkbox is checked
 * @uses $vars['label']       Optional label string
 * @uses $vars['class']       Additional CSS class
 * @uses $vars['label_class'] Optional class for the label
 * @uses $vars['label_style'] Optional style for the label
 * @uses $vars['label_tag']   HTML tag that wraps concatinated label and input. Defaults to 'label'.
 */

$defaults = array(
	'default' => 0,
	'disabled' => false,
	'type' => 'checkbox'
);

$vars = array_merge($defaults, $vars);

$default = $vars['default'];
unset($vars['default']);

if (isset($vars['name']) && $default !== false) {
	echo elgg_view('input/hidden', ['name' => $vars['name'], 'value' => $default]);
}

$label = elgg_extract('label', $vars, false);
$label_class = (array) elgg_extract('label_class', $vars, []);
$label_class[] = 'switch';
$label_style = (array) elgg_extract('label_style', $vars, []);
unset($vars['label'], $vars['label_class'], $vars['label_style']);

$input = elgg_format_element('input', $vars).'<span class="slider"></span>';

$html_tag = elgg_extract('label_tag', $vars, 'label', false);
$input = elgg_format_element($html_tag, ['class' => $label_class, 'style'=>$label_style], "$input $label");

echo $input;

