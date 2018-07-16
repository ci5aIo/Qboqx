<?php
/**
 * Elgg jot Plugin
 * @package jot
 */
//$identifiers = 'Page: jot\pages\jot\edit.php<br>Action: jot\actions\jot\edit.php<br>';

elgg_load_js('lightbox');
elgg_load_css('lightbox');

if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}

if (isset($page[1])) {
    $input_1 = $page[1];
    if (is_int($input_1)){
        $guid        = (int) $input_1;
        $solo = true;
    }
    else {                              //assume command
        $command = $input_1;
    }    
}

if (isset($page[2])) {
    $section = $page[2];
}
else {
	$section = 'receipt';
}
if ($guid == 0){
	$exists = false;
}
elseif ($guid > 0){
	$exists      = true;
    $jot         = get_entity($guid);
	$subtype     = $jot->getSubtype();
	$aspect      = $jot->aspect;
	$item_guid   = $guid;
	$parent_item = get_entity($item_guid);
	$referrer    = "jot/view/{$item_guid}";
	if(elgg_instanceof($jot,'object','jot')) {
		$title   = sprintf(elgg_echo('jot:edit'), $aspect);
		$form_version = "jot/edit";
	}
	
	$title = "Edit $aspect";
	
	if ($subtype == 'transfer' && empty($section)){
		$section = $aspect;
	}
	
	if($jot->canEdit()) {
		jot_prepare_container_breadcrumbs($jot);
	
	    $form_vars    = array('name' => 'jotForm', 
	                        'enctype' => 'multipart/form-data',
	                        'action'  => 'action/jot/edit',
							);
	    
		if (elgg_view_exists("forms/{$subtype}s/$section")){
	    	$form_version = "{$subtype}s/$section";
	    	$form_vars['action'] = 'action/transactions/return';
	    }
	    else {
	    	$form_version = "{$subtype}s/edit";
	    }
	    
	    $body_vars           = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description = null, $section);
//@TODO work into above
	    $body_vars['exists'] = $exists;
	
	  if (elgg_is_sticky_form('jotForm')) {
	  	$sticky_values = elgg_get_sticky_values('jotForm');
	  	foreach ($sticky_values as $key => $value) {
	  		$body_vars[$key] = $value;
	  	}
	  	elgg_clear_sticky_form('jotForm');
	  }
	  
	  $content      = elgg_view_form($form_version, $form_vars, $body_vars);
	
	} else {
		$content = elgg_view("jot/error");
	}
	
	// Show jot sidebar
	$sidebar = elgg_view("jot/sidebar");
	
	$params = array(
			'content' => $identifiers.$content,
			'title' => $title,
			'sidebar' => $sidebar,
			);
	
	//$body = elgg_view_layout('no_sidebar', $params);
	$body = elgg_view_layout('one_sidebar_no_footer', $params);
}

if ($guid == 0 && $section == 'receipt') {
	$subtype = 'transfer';
	$title = "New Receipt";
	$form_version = "transfers/edit";
    $form_vars    = array('name' => 'jotForm', 
                          'enctype' => 'multipart/form-data',
                          'action'  => 'action/jot/edit',
						);
    $referrer    = '';
    $description = null;
    $body_vars    = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);	  
	$content      = elgg_view_form($form_version, $form_vars, $body_vars);
	$params = array(
			'content' => $identifiers.$content,
			'title' => $title,
			);
	
	$body = elgg_view_layout('one_sidebar_no_footer', $params);
}
if ($guid == 0 && $section == 'ownership') {
	$subtype = 'transfer';
	$title = "Transfer Ownership";	
// Get current shelf data
	$owner_guid = elgg_get_logged_in_user_guid();        //$display .= '$guid: '.$guid.PHP_EOL;
	$file = new ElggFile;
	$file->owner_guid = $owner_guid;
	$file->setFilename("transfer_que.json");
	if ($file->exists()) {
		$file->open('read');
		$json = $file->grabFile();                       //$display .= '36 $json:'."$json<br>";
		$file->close();
	}

	$data = json_decode($json, true);
	
	$form_version = "transfers/edit";
    $form_vars    = array('name' => 'jotForm', 
                        'enctype' => 'multipart/form-data',
                        'action'  => 'action/jot/edit',
						);
    $referrer    = '';
    $description = null;
    $body_vars   = jot_prepare_form_vars($subtype, $jot, $item_guid, $referrer, $description, $section);
    $body_vars['shelf'] = $data;	  
	$content      = elgg_view_form($form_version, $form_vars, $body_vars);
	$params = array(
			'content' => $content,
	        //'content' => $identifiers.$content,
			'title' => $title,
			);
	
	$body = elgg_view_layout('one_sidebar_no_footer', $params);
	
}

echo elgg_view_page($title, $body);
