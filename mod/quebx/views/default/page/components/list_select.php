<?php
/**
 * View a list of items as a select list
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page. Only used as input to pagination.
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 * @uses $vars['no_results']  Message to display if no results
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if (!$items && $no_results) {
	echo "<p>$no_results</p>";
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

$list_class = 'elgg-list';
if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$html = "";
$nav = "";

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}
	$select_type = elgg_extract('select_type', $vars, false);

Switch ($select_type){
    case 'checkbox':
        $view = 'input/checkbox';
        break;
    case 'radio':
        $view = 'input/radio';
        break;
    default:
        break;
}

$html .= "<ul class=\"$list_class\">";
foreach ($items as $item) {
	$li = elgg_view_list_item($item, $vars);
	if ($li) {
		$item_classes = array($item_class);
		$selector     = elgg_view($view, array('name'=>'jot[images][]', 'value'=>$item->guid));

		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";

			$item_classes[] = "elgg-item-" . $item->getType();
			$subtype = $item->getSubType();
			if ($subtype) {
				$item_classes[] = "elgg-item-" . $item->getType() . "-" . $subtype;
			}
		} else {
			$id = "item-{$item->getType()}-{$item->id}";
		}

		$item_classes = implode(" ", $item_classes);

		$html .= "<li id=\"$id\" class=\"$item_classes\">
		              <div class='rTableRow'>
                    		<div class='rTableCell' style='width:0%;padding:0px;vertical-align:middle'>$selector</div>
                    		<div class='rTableCell' style='width:100%;padding:0px'>$li</div>
                       </div>
		          </li>";
	}
}
$html .= '</ul>';

if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;