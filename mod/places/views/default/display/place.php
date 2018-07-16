<?php
$entity  = $vars['entity'];
$section = lcfirst($vars['this_section']);
$item    = $vars['item'];
echo "<!--item = $item->title<br>";
echo "entity = $entity->title<br>-->";
if (empty($section)) {
	 $section = 'summary';
}

	$destination = "display/place/$section";
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    }