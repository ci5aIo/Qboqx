<?php

$headers        = elgg_extract('show_section_headers', $vars, false);
$class          = elgg_extract('class'               , $vars, '');
$item_class     = elgg_extract('item_class'          , $vars, '');
$items          = elgg_extract('items'               , $vars, false);
$id             = elgg_extract('id'                  , $vars);
$collapse       = elgg_extract('collapse'            , $vars, true);
$show_toggle    = elgg_extract('show_toggle'         , $vars, true);
$section_guid   = (int) $vars['section']->guid;
$selected       = elgg_extract('selected'            , $vars);
$selected_owner = elgg_extract('selected_owner'      , $vars);
$list_type      = elgg_extract('list_type'           , $vars);

if ($headers) {
	$name = elgg_extract('name', $vars);
	$section = elgg_extract('section', $vars);
	echo '<h2>' . elgg_echo("menu:$name:header:$section") . '</h2>';
}
echo "<ul id=\"$id\" class=\"$class\">";
if ($items){
    foreach ($items as $menu_item) {
        echo elgg_view('navigation/flyout/gallery/item', array(
    		'item'           => $menu_item,
            'selected'       => $selected,
            'selected_owner' => $selected_owner,
    		'item_class'     => $item_class,
    		'collapse'       => $collapse,
    	    'show_toggle'    => $show_toggle,
            'display'        => 'flyout',
	        'list_type'      => $list_type,
    	));
    }
}
echo '</ul>';
