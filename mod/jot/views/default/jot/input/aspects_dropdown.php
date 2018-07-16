<?php
	/**
	 * Elgg jot Plugin
	 * @package jot (forked from webgalli's Classifieds Plugin)
	 */

	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		$selected_aspect = $vars['entity']->jotaspect;
	}
	$aspects = string_to_tag_array(elgg_get_plugin_setting('jot_aspects', 'jot'));

	if (!empty($aspects)) {
		if (!is_array($aspects)) $aspects = array($aspects);
		$options = array();
		foreach ($aspects as $option) {
			$options[$option] = elgg_echo("jot:aspect:{$option}");
		}

		echo "<label>" . elgg_echo('jot:categories:choose') . "&nbsp;</label>";
		echo elgg_view('input/dropdown',array(
							'options_values' => $options,
							'value' => $selected_aspect,
							'name' => 'aspect'
							));
//		echo "</label>";

	}
