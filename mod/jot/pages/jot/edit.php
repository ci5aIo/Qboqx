
<?php
/**
 * Elgg jot Plugin
 * version 02_scratch
 * @package jot
 */
/*
$input_1 = get_input('guid');
$section = get_input('section');

if (elgg_entity_exists($input_1)){
    $item_guid = $input_1;
    $solo      = true;
    $item      = get_entity($item_guid);
}
else {
    $command = $input_1;
}*/

if (isset($page[1])) {
    $input = $page[1];
    if (elgg_entity_exists($input)){
        $item_guid        = (int) $input;
        $solo = true;
        $item = get_entity($item_guid);
    }
    else {                              //assume command
        $command = $input;
    }    
}

if (isset($page[2])) {
    $section = $page[2];
}
else {
	$section = $item->aspect ?: 'receipt';
}
if (elgg_instanceof($item, 'object', 'market') || $input == 0){
    $action = 'add';
    $asset  = $item;
}
else {
    $jot = $item;
    $action = 'edit';
}

$origin = get_input('origin', $vars);

Switch ($section){
	case 'receive':
	case 'receipt':
    	$subtype = 'transfer';
    	Switch ($action){
    	    case 'add':
    	        $title = "New Receipt";
    	        break;
    	    case 'edit':
    	        $title = ($jot->aspect == 'receipt' ? 'Edit Receipt' : ($jot->aspect == 'receive' ? 'Edit Shipment Receipt': 'Edit'));
    	        break;
    	}
    	
    	$form_version = "transfers/elements/receipt";
        $form_vars    = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit_v4',
    						);
        $referrer            = '';
        $description         = null;
        $body_vars           = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);
        $body_vars['action'] = $action;
        $body_vars['asset']  = $asset;
        $body_vars['origin']=  $origin;                                                                                    $display .= '74 $body_vars: '.print_r($body_vars, true).'<br>';
    	$content             = elgg_view('output/div',['content'=> elgg_view_form($form_version, $form_vars, $body_vars),
    			                                       'class'  => 'qbox',
    			                                       'options'=> ['id' => 'q'.$item_guid,
    			                                                    'style' => 'padding:5px']]);
    	$params = array(
    			'content' => $content,
    			'title' => $title,
    //	        'header' => $header,
    			);
    	
    	$body = elgg_view_layout('one_sidebar_no_footer', $params);
    	break;
    case 'return':
    	$subtype = 'transfer';
    	$title   = "Return Receipt";
    	$form_version = "transfers/elements/return";
        $form_vars    = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit',
    						);
        $referrer    = '';
        $description = null;
        $body_vars    = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);	  
    	$content      = elgg_view_form($form_version, $form_vars, $body_vars);
    	$params = array(
    			'content' => $content,
    			'title' => $title,
    			);
    	
    	$body = elgg_view_layout('one_sidebar_no_footer', $params);
    	break;
    default:
        $subtype = $item->getSubtype();
    //	$title   = "Edit $subtype";
    	$title   = "Edit $section";
    /*	$form_version = "{$subtype}s/edit";
        $form_vars    = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit',
    						);
        $referrer    = '';
        $description = null;
        $body_vars    = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);
        $body_vars['action'] = 'edit';
    */    
        elgg_load_library('elgg:market');
        $content = market_render_section(array(
                                         'action'       => 'edit', 
                                         'section'      => 'experience',
                                         'presentation' => 'full',
                                         'entity'       => $jot,
                                         'selected'     => $section,
                                         'owner_guid'   => elgg_get_logged_in_user_guid(),
                                         ));		  
    //	$content      = elgg_view_form($form_version, $form_vars, $body_vars);
    	$params = array(
    			'content' => $content,
    			'title' => $title,
    			);
    	
    	$body = elgg_view_layout('one_sidebar_no_footer', $params);
}

echo elgg_view_page($title, $body);
//register_error($display);