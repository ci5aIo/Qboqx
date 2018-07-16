<?php
$section     = lcfirst(elgg_extract('this_section', $vars, 'summary'));
$destination = "jot/display/transfer/$section";

if (elgg_view_exists($destination)){
	echo elgg_view($destination, $vars);
}
if (!elgg_view_exists($destination)){
   	register_error("$destination<b> not found</b>");
}