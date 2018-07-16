<?php

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
	$custom_selected = $vars['entity']->custom;
}

$custom_choices = string_to_tag_array(elgg_get_plugin_setting('quebx_custom_choices', 'quebx'));
if (empty($custom_choices)) $custom_choices = array();
if (empty($custom_selected)) $custom_selected = array(); 

if (!empty($custom_choices)) {
	if (!is_array($custom_choices)) $custom_choices = array($custom_choices);

	echo "<label>" . elgg_echo('quebx:custom:select') . "&nbsp;";
	echo elgg_view('quebx/input/pulldown',array(
						'options' => $custom_choices,
						'value' => $custom_selected,
						'name' => 'quebxcustom'
						));
	echo "</label>";

}

