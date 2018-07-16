<?php
	/**
	 * Elgg jot Plugin
	 * @package jot (forked from webgalli's Classifieds Plugin)
	 * ***12/13/2014 - Doesn't work.  Shows value="Request => Request" instead of value="Request" ***
	 */

	$selected_aspect = 'comment';
	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		$selected_aspect = $vars['entity']->jotaspect;
	}
	$aspects = string_to_tag_array(elgg_get_plugin_setting('jot_aspects', 'jot'));

	if (!empty($aspects)) {
		if (!is_array($aspects)) $aspects = array($aspects);
		$options = array();
		foreach ($aspects as $option) {
			$options[$option] = $option.' => '.elgg_echo("jot:aspect:{$option}");
		}

//		echo "<label>" . elgg_echo('jot:categories:choose') . "&nbsp;</label>";
		echo elgg_view('input/radio',array(
							'name' => 'aspect',
							'align' => 'horizontal',
							'value' => $selected_aspect,
							'options' => $options
							));
//		echo "</label>";

	}