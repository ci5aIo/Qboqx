<!-- View: market\views\default\market\display\item.php<br> -->
<?php
elgg_load_library('elgg:market');
$entity = $vars['entity'];
$section = lcfirst($vars['this_section']);
$category = $entity->marketcategory;
$item_guid = $entity->guid;
$fields = market_prepare_brief_view_vars($entity);
$details = market_prepare_detailed_view_vars($entity);
$url = elgg_get_site_url() . "market";
if (empty($category)) {
	 $category = 'default';
}

echo '<!--View context: '.elgg_get_context().'--></p>';

	$destination = "market/display/$section";
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
	else {
	   	register_error("$destination<b> not found</b>");
    } 