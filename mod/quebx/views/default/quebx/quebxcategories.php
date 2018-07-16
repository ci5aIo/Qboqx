<?php

	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		$selected_quebxcategory = $vars['entity']->quebxcategory;
	}
	$quebxcategories = string_to_tag_array(elgg_get_plugin_setting('quebx_categories', 'quebx'));

	if (!empty($quebxcategories)) {
		if (!is_array($quebxcategories)) $quebxcategories = array($quebxcategories);
		$options = array();
		foreach ($quebxcategories as $option) {
			$options[$option] = elgg_echo("quebx:category:{$option}");
		}

//		echo "<label>" . elgg_echo('quebx:categories:choose') . "&nbsp;";
		echo elgg_view('input/dropdown',array(
							'options_values' => $options,
							'value' => $selected_quebxcategory,
							'name' => 'quebxcategory'
							));
//		echo "</label>";

	}
