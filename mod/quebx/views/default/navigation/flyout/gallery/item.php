<?php
$page_owner    = elgg_get_page_owner_entity();
$menu_item     = elgg_extract('item'       , $vars);
$collapse      = elgg_extract('collapse'   , $vars, false);
$show_toggle   = elgg_extract('show_toggle', $vars, true);
$selected      = elgg_extract('selected'   , $vars);
$display_state = elgg_extract('display'    , $vars, 'flyout'); 
$item_guid     = $menu_item['guid'];
$item          = get_entity($item_guid);
$item_class    = ($collapse) ? 'elgg-menu-closed' : 'elgg-menu-open';
$item_count    = elgg_get_entities(array('container_guids' => array($item_guid), 
                                         'count'           => true));
$selected_queb     = elgg_extract('label'         , $vars);
$selected_owner    = elgg_extract('selected_owner', $vars);
$list_type         = elgg_extract('list_type'     , $vars);
$user              = elgg_get_logged_in_user_entity();
$url               = elgg_get_site_url(). "queb?";
if (!empty($list_type)){
    $list_type_filter = "&list_type=$list_type";
}

if ($item_count){$item_count_label = " ($item_count)";}
if ($item_guid  == $selected){$item_class .= ' elgg-state-selected';}
if ($display_state == 'flyout'){
    echo "<li class=\"$item_class\">".
          elgg_view('output/url', array('text' =>$item->title.$item_count_label, 'href'=>$url."x=$selected_queb&y=$item_guid&z=$selected_owner".$list_type_filter,)).
         "</li>";
}
if ($display_state == 'inline'){
    echo "<span>";
    echo elgg_view('output/url', array('text' =>'&nbsp;&nbsp;-&nbsp;'.$item->title.$item_count_label, 
                                       'href'=>$url."x=$selected_queb&y=$item_guid&z=$selected_owner".$list_type_filter,));
//                                        'href'=>$item->getURL(),));
    echo "</span>";
}