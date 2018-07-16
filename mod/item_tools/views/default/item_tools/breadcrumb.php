<?php

$folder = elgg_extract("entity", $vars);

echo "<div id='item_tools_breadcrumbs' class='clearfix'>";
echo elgg_view_menu("item_tools_folder_breadcrumb", array(
	"entity" => $folder,
	"sort_by" => "priority",
	"class" => "elgg-menu-hz"
));

echo "</div>";

if ($folder) {
	echo elgg_view_entity($folder, array("full_view" => true));
}
