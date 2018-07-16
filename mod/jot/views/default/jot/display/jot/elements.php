<?php
$panel          = elgg_extract('panel'         , $vars);
$action         = elgg_extract('action'        , $vars);
$selected       = elgg_extract('selected'      , $vars, false);
$entity         = elgg_extract('entity'        , $vars, false);
$owner_guid     = elgg_extract('owner_guid'    , $vars, false);
$container_guid = elgg_extract('container_guid', $vars, false);
$subtype        = elgg_extract('aspect'        , $vars, false);
$title          = elgg_extract('title'         , $vars, false);

Switch($panel){
    case 'experiences':
        $view = 'jot/display/experience/view';
        
        $things_panel     = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Things',
                                'container_guid' => $container_guid,
                                'section'        => 'things',));
        $documents_panel  = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Documents',
                                'container_guid' => $container_guid,
                                'section'        => 'documents',));
        $gallery_panel    = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Gallery',
                                'container_guid' => $container_guid,
                                'section'        => 'gallery',));
        $expand_panel   = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Expand',
                                'container_guid' => $container_guid,
                                'section'        => 'expand',));
        $experimental_panel   = elgg_view($view,array(
                                'action'         => $action,
                                'container_guid' => $container_guid,
                                'section'        => 'experimental',));
        $tabs             = elgg_view('quebx/menu', array(
                                'subtype'      => $subtype,
                                'this_section' => $this_section, 
                                'action'       => $action,
                                'guid'         => $entity->guid,
                                'ul_style'     => 'border-bottom: 0px solid #cccccc',
//                                'li_style'     => 'border-radius: 9px',
//                                'link_style'   => 'height:18px;padding:0px 10px 0px 10px;border-radius: 9px 9px 9px 9px'
                
        ));
        $tabs            .= "<div style='min-height:150px'>
                                $things_panel
                                $documents_panel
                                $gallery_panel
                                $expand_panel                        
                             </div>";
        $body_vars        = array('action'           => $action,
                                  'owner_guid'       => $owner_guid,
                                  'container_guid'   => $container_guid,
                                  'tabs'             => $tabs, 
                                  'section'          => 'main',);
        $main_panel       = elgg_view($view, $body_vars);
        
        echo $main_panel;
        
        break;
    case 'observations':
        
        break;
}
