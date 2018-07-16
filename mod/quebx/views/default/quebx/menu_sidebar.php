<?php
/**
 * Market Categories pages sidebar menu
 *
 * @uses $vars['type']
 */

elgg_load_library('elgg:quebx:navigation');
$selected_category = elgg_extract('selected_category', $vars);
$dimension         = elgg_extract('dimension'        , $vars, 'market');
$page_owner        = elgg_get_page_owner_entity();
$selected_owner    = elgg_extract('selected_owner'   , $vars, $page_owner);
$list_type         = get_input('list_type', 'list'); 

Switch($dimension){
    case 'market':
        echo elgg_view('navigation/flyout', 
                        array('root'       => elgg_get_site_entity(), 
                              'menu'       => 'categories',
                              'name'       => 'things',
                              'orientation'=> 'vertical',
                              'selected'   => $selected_category,
                              'cascade'    => true,
                              'show_empty' => false,
                              'show_tally' => true,
                              'owner'      => $selected_owner, 
                              'list_type' => $list_type,
                              )
                      );
        break;
    case 'gallery':
        echo elgg_view('navigation/flyout', 
                        array('root'       => elgg_get_site_entity(), 
                              'menu'       => 'gallery',
                              'name'       => 'albums',
                              'orientation'=> 'vertical',
                              'selected'   => $selected_category,
                              'cascade'    => true,
                              'show_empty' => false,
                              'show_tally' => true,
                              'owner'      => $selected_owner, 
                              'list_type' => $list_type,
                              )
                      );
}
  eof: