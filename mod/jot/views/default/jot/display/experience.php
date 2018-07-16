<?php
$entity  = $vars['entity'];
$section = lcfirst($vars['this_section']);
$item    = $vars['item'];
echo "<!--view: mod-jot-views-default-jot-display-experience.php<br> -->";
if (empty($section)) {
	 $section = 'summary';
}

$destination = "jot/display/experience/$section";
$view_vars = array('entity'=>$entity, 'this_section'=>$section, 'item'=>$item);
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $view_vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    }