<?php
$entity  = $vars['entity'];
$section = lcfirst($vars['this_section']);
$item    = $vars['item'];

if (empty($section)) {
	 $section = 'summary';
}

	$destination = "jot/display/cause/$section";
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    }