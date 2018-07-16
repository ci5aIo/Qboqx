*D*R*A*F*T*

<?php
// Get input data
$guid  = $page[1];
if (isset($page[2])) {
    $aspect = $page[2];
}
if (isset($page[3])) {
    $asset = $page[3];
}

//Acquire values of the jot item
$item = get_entity($guid);
$subtype=$item->getsubtype();
if (!$asset){$asset = $guid;}

if(!$referrer){
	$referrer   = elgg_get_site_url()."market/view/$guid";
}

$jotactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'jot');
$title = elgg_echo("jot:title:{$aspect}");

if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}
$form_vars = array('name'    => 'jotForm', 
                   'enctype' => 'multipart/form-data',
                   'action'  => 'action/market/move',
				  );

switch ($aspect){
	case 'component':
		$body_vars = jot_prepare_form_vars($aspect, NULL, $guid, $referrer, $description, $section);
		// Path: market\views\default\forms\component\add.php
		$form      = 'move';
		$form_vars['action'] = 'action/jot/add/element';
		break;
	default:
		$body_vars = jot_prepare_form_vars($aspect, NULL, $guid, $referrer, $description, $section);
		$form      = 'jot/add';
		break;
 }
$content = elgg_view_form($form, $form_vars, $body_vars);
$body = elgg_view_layout('action', array(
	'content' => $content,
	'title' => FALSE,	
	'filter' => '',
));

$module_type = 'popup';
 
echo elgg_view_module($module_type, $title, $body);
