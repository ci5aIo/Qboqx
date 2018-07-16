<?php 
$selected       = elgg_extract('selected'      , $vars, false);
$selected_state = elgg_extract('selected_state', $vars, 'selected');
$entity         = elgg_extract('entity'        , $vars, false);
$owner_guid     = elgg_extract('owner_guid'    , $vars, false);
$container_guid = elgg_extract('container_guid', $vars, false);
$action         = elgg_extract('action'        , $vars);
$panel          = elgg_extract('panel'         , $vars);
$aspect         = elgg_extract('aspect'        , $vars, false);
$title          = elgg_extract('title'         , $vars, false);
$presentation   = elgg_extract('presentation'  , $vars);
$guid           = $entity ? $entity->getGUID() : $owner_guid;

if (!empty($entity->assets)){
    if (is_array($entity->assets)){
        $things_count   = count($entity->assets);}
    else {
        $things_count = 1;}}
if (!empty($entity->documents)){
    if (is_array($entity->documents)){
        $documents_count = count($entity->documents);}
    else {
        $gallery_count = 1;}}

if (!empty($entity->images)){
    if (is_array($entity->images)){
        $gallery_count = count($entity->images);}
    else {
        $gallery_count = 1;}
}
//echo '$panel: '.$panel.'<br>$action: '.$action.'<br>';
Switch($panel){
    case 'experiences':
        if (!entity){
            $this_section = $selected;
        }
        elseif ($entity->aspect == 'instruction'){
                $this_section = 'Instructions';
            }
        else {
                $this_section = ucfirst($selected) ?: ucfirst($entity->aspect);
            }
        // Input form
        $form_action      = "action/jot/edit_v4";
        Switch ($action){
            case 'add':
                $view       = 'forms/experiences/edit';
                $form       = 'experiences/edit';
                $form_name  = "add_experience_form";
                break;
            case 'edit':
                $view       = 'forms/experiences/edit';
                $form       = 'experiences/edit';
                break;
            case 'view':
                $view       = 'jot/display/experience/view';
            	//$view       = 'forms/experiences/edit';
                break;
        }
        $things_panel     = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Things',
                                'guid'           => $guid,
                                'container_guid' => $container_guid,
                                'section'        => 'things',));
        $documents_panel  = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Documents',
                                'guid'           => $guid,
                                'container_guid' => $container_guid,
                                'section'        => 'documents',));
        $gallery_panel    = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Gallery',
                                'guid'           => $guid,
                                'container_guid' => $container_guid,
                                'section'        => 'gallery',));
        $expand_panel   = elgg_view($view,array(
                                'action'         => $action,
                                'selected'       => $selected == 'Expand',
                                'selected_state' => $selected_state,
                                'guid'           => $guid,
                                'container_guid' => $container_guid,
                                'section'        => 'expand',));
        $tabs             = elgg_view('quebx/menu', array(
                                'subtype'      => $aspect,
                                'this_section' => $this_section,
                                'state'        => $selected_state,
                                'action'       => $action,
                                'guid'         => $guid,
                                'ul_style'     => 'border-bottom: 0px solid #cccccc',
                                'ul_aspect'    => 'attachments',
                                'attachments'  => array('things'=>$things_count, 'documents'=>$documents_count, 'gallery'=>$gallery_count),
//                                'li_style'     => 'border-radius: 9px',
//                                'link_style'   => 'height:18px;padding:0px 10px 0px 10px;border-radius: 9px 9px 9px 9px'
                
        ));
        $tabs            .= "<div>
                                $things_panel
                                $documents_panel
                                $gallery_panel
                                $expand_panel                        
                             </div>";
        $form_vars        = array('action'           => $form_action, 
                                  'id'               => $form_name, 
                                  'class'            => $form_class,
//                                  'data-parsley-validate' => '',
        );
        $body_vars        = array('action'           => $action,
                                  'guid'             => $entity->guid,
                                  'owner_guid'       => $owner_guid,
                                  'container_guid'   => $container_guid,
                                  'presentation'     => $presentation,
                                  'tabs'             => $tabs, 
                                  'section'          => 'main',
                                  'title'            => $title,);
        Switch ($action){
            case 'add':
                $main_panel = elgg_view_form($form, $form_vars, $body_vars);
/*                $main_panel .= "<script>
								require(['elgg', 'jquery'], function (elgg, $) {
                                  $('#add_experience_form').parsley();
                                autosize(document.querySelectorAll('textarea'))});
                                </script>";*/
/* 2018-08-31 - SJ validate.js and require.js seem to conflict.  
 *                 $main_panel .= "<script>
                                        // just for testing, avoids form submit
                                        jQuery.validator.setDefaults({
                                          debug: true,
                                          success: \"valid\"
                                        });
                                        $(\"#add_experience_form\").validate({
                                        		debug: false,
                                        		rules: {
                                        			\"jot[title]\": {
                                        				required: true
                                        				,alphanumeric: true
                                        				,maxlength: 63
                                        				,minlength: 3
                                        			}
                                        		}
                                            });
                                         </script>";;
*/
                break;
            case 'edit':
                $main_panel = elgg_view_form($form, $form_vars, $body_vars);
                break;
            case 'view':
                $main_panel = elgg_view($view, $body_vars);
                break;
        }
        break;
    case 'suppliesxxx':
        $main_panel = 'Supplies (placeholder)';
        
        break;
    case 'partsxxx':
        $main_panel = 'Parts (placeholder)';
        
        break;
    case 'componentsxxx':
        $main_panel = 'Components (placeholder)';
        
        break;
    case 'accessories':
        $main_panel = 'Accessories (placeholder)';
        
        break;
    case 'documents':
        $main_panel = 'Documents (placeholder)';
        
        break;
    case 'insights':
        $main_panel = 'Insights (placeholder)';
        
        break;
    case 'support_groups':
        $main_panel = 'Support Groups (placeholder)';
        
        break;
    case 'receipts':
        $subtype    = 'transfer';
    	$form       = "transfers/elements/receipt";
        $form_vars  = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit_v4',
    						);
        Switch ($action){
            case 'add':
                $view       = 'forms/transfers/edit';
                $form       = 'transfers/edit';
                break;
            case 'edit':
                $view       = 'forms/transfers/edit';
                $form       = 'transfers/edit';
                break;
            case 'view':
                $view       = 'jot/display/transfer';
            	//$view       = 'forms/experiences/edit';
                break;
        }
        elgg_load_library('elgg:jot');
        //$body_vars           = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);
        $body_vars           = array(
    		'aspect'          => $aspect,
            'entity'          => $entity,
    		'item_guid'       => $guid,
            'action'          => $action,
            'asset'           => $asset,
            'origin'          => $origin,
            'presentation'    => $presentation,
    	); 
    	Switch ($action){
            case 'add':
                $main_panel = elgg_view_form($form, $form_vars, $body_vars);
                break;
            case 'edit':
                $main_panel = elgg_view_form($form, $form_vars, $body_vars);
                break;
            case 'view':
                $list_body  = elgg_view($view, $body_vars);
                $main_panel = elgg_view_image_block($icon, $list_body);
                break;
        }
        break;
    default:
		$asset     = $entity->guid;
        $form      = 'jot/add';
		$form_vars = array('name'    => 'jotForm', 
                           'enctype' => 'multipart/form-data',
                           'action'  => 'action/jot/add/element',
        				  );
		//$body_vars = jot_prepare_form_vars($aspect, $jot, $jot_guid, $referrer, $description, $section);
		$body_vars           = array(
    		'aspect'          => $aspect,
            'entity'          => $entity,
    		'item_guid'       => $guid,
            'action'          => $action,
            'asset'           => $asset,
            'origin'          => $origin,
            'presentation'    => $presentation,
    	); 
		$body_vars['view'] = $view;
        $body_vars['jot'] = $jot;
        $content = elgg_view_form($form, $form_vars, $body_vars);
        $main_panel = elgg_view_layout('action', array(
        	'content' => $content,
        	'title'   => $title,	
        	'filter'  => '',
        ));
        		
		break;
}

echo $main_panel;
