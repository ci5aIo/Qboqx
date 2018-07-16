<!--Page jot\pages\jot\box.php -->
<?php
/*
 * Used by
	 * 
 * 
 * 
 */

// Get input data
$jot_guid  = $page[1];
if (isset($page[2])) {
    $aspect = $page[2];
}
if (isset($page[3])) {
    $action = $page[3];
}

//Acquire values of the jot item
if ($action != 'add'){
	$jot = get_entity($jot_guid);
}
if ($jot){
	$subtype = $jot->getsubtype();
}
else {
	$subtype = 'market';
}

if (empty($action)){$action = 'add';}

if(!$referrer){
	if ($subtype == 'market' && !empty($jot)) {
		$referrer = elgg_get_site_url()."market/view/$jot_guid";
	}
	else {
		$referrer = elgg_get_site_url()."jot/view/$jot_guid";
	}
}

$jotactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'jot');
$title = elgg_echo("jot:title:{$aspect}");

if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}
$form_vars = array('name'    => 'jotForm', 
                   'enctype' => 'multipart/form-data',
                   'action'  => 'action/jot/add',
				  );

if (elgg_view_exists("object/$subtype")) {$view = "object/$subtype";}
else                                     {$view = "object/jot";} 

$form      = 'jot/add';

switch ($aspect){
	case 'item':
		$form_vars['action'] = 'action/market/edit';
		$body_vars = jot_prepare_form_vars($aspect, null, $jot_guid, $referrer, $description); 
		break;
	case 'issue':
		$form      = 'issues/add';
		$body_vars = issues_prepare_form_vars($aspect, null, $jot_guid, $referrer, $description); 
		break;
	case 'observation':
		$form_vars['action'] = 'action/jot/add/element';
		$body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
		break;
	case 'experience':
		$form_vars['action'] = 'action/jot/add/element';
		$body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
		break;
	case 'insight':
		$form_vars['action'] = 'action/jot/add/element';
		$body_vars = insights_prepare_form_vars(null, $jot_guid, $referrer, $description); 
		break;
	case 'cause':
		$form      = 'causes/add';
		$body_vars = causes_prepare_form_vars(null, $jot_guid, $referrer, $description);
		break;
	case 'receipt':
		switch ($action){
			case 'add':
				$form_vars['action'] = 'action/jot/edit';
				$referrer = $referrer.'/inventory';
		        $body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
				break;
			case 'edit':
				$form      = 'jot/edit';
				$section = $aspect;
				$section = 'return';
				$aspect  = $subtype;
				$body_vars = jot_prepare_form_vars($aspect, $jot, $jot_guid, $referrer, $description, $section);
				break;
			case 'return':
				$form                = 'transfers/return';
				$title               = elgg_echo("jot:title:{$action}");
				$form_vars['action'] = 'action/transactions/return';
				break;
		}
		break;
	case 'component':
		$form_vars['action'] = 'action/jot/add/element';
		$body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
		break;
	case 'schedule':
		$form_vars['action'] = 'action/jot/add/element';
		switch ($action){
			case 'add':
				$body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
				break;
			case 'edit':
				$body_vars = jot_prepare_form_vars($aspect, $jot, $jot_guid, $referrer, $description, $section);
				break;
		}
		break;
	case 'scheduled':
		switch ($action){
			case 'view':
				$form      = 'jot/view';
				$body_vars = jot_prepare_form_vars($aspect, $jot, $jot_guid, $referrer, $description, $section);
				break;
		}
		break;
	default:
		$form_vars['action'] = 'action/jot/add/element';
		$body_vars = jot_prepare_form_vars($aspect, NULL, $jot_guid, $referrer, $description, $section);
		break;
 }
$body_vars['view'] = $view;
$body_vars['jot'] = $jot;
$body_vars['presentation'] = 'box';
$content = elgg_view_form($form, $form_vars, $body_vars);
$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => $title,	
	'filter' => '',
));

$page_shell = 'box';
 
echo elgg_view_page($title, $body, $page_shell);
//$module_type = 'popup';
//echo elgg_view_module($module_type, $title, $body);
