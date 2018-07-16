<?php
$section = lcfirst($vars['this_section']);

if (empty($section)) {
	 $section = 'summary';
}

	$destination = "jot/display/issue/$section";
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    }