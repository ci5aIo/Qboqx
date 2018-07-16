<?php
/** D R A F T
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to ul
 * @uses string $vars['cascade'] => bool, Flag to cycle through to children
 * @uses array $vars['root'] A multi-dimensional array of tab entries
 */
$options        = $vars;
//$page_owner     = elgg_get_page_owner_entity();
$page_owner     = elgg_get_logged_in_user_entity();
$root           = elgg_extract('root'       , $vars);
$show_empty     = elgg_extract('show_empty' , $vars, false);
$menu           = elgg_extract('menu'       , $vars, 'categories');
$selected       = elgg_extract('selected'   , $vars);
$selected_owner = elgg_extract('owner'      , $vars);
$orientation    = elgg_extract('orientation', $vars, 'horizontal');
$list_type      = elgg_extract('list_type'  , $vars);


if ($orientation == 'horizontal') {
	$options['class'] = "elgg-tabs elgg-htabs";
} else {
	$options['class'] = "elgg-tabs elgg-vtabs";
}
if (isset($vars['class'])) {
	$options['class'] = "{$options['class']} {$vars['class']}";
}
Switch ($menu){
    case 'gallery':
        $menu_items['guid'] = $page_owner->getguid();
        break;
    default:
        $menu_items['guid'] = $root->getguid();
        break;
}
$attributes = elgg_format_attributes($options);
echo '<div id="cssmenu">';
echo elgg_view("navigation/flyout/$menu", array(
		'menu'                 => $menu_items,
        'selected'             => $selected,
        'owner'                => $selected_owner,
		'class'                => $vars['class'],
		'name'                 => $menu,
		'show_section_headers' => $headers,
		'show_empty'           => $show_empty,
		'show_tally'           => $vars['show_tally'],
        'list_type'            => $list_type,
	));
echo '</div>';