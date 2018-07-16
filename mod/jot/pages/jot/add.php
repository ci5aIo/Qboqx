<?php
/**
 * Further proceesing for jots.  
 */

// Get input data
$jot       = elgg_get_sticky_values('jot');
//$item_guid = (int) get_input('item_guid');
//$aspect    = get_input('aspect');

/* From jot/start:
	case 'add':
		gatekeeper();
		set_input('item_guid', $page[1]);
		set_input('aspect', $page[2]);
		set_input('asset', $page[3]);
		include "$pages/jot/add.php";
		break;
*/

$item_guid  = $page[1];
if (isset($page[2])) {
    $aspect = $page[2];
}
if (isset($page[3])) {
    $view_type = $page[3];
}

/*
if ($item_guid){
    $referrer = elgg_get_site_url()."market/view/{$item_guid}";
}*/

//Extract variables to pass to *prepare_form_vars()
if ($jot){
//	$item_guid   = $jot['entity_guid'];
//	$item_guid   = $jot['item_guid'];
	$description = $jot['description'];
	$referrer    = $jot['referrer'];
	$aspect      = $jot['aspect'];
}
//echo 'guid:'.$jot['item_guid'];

//Acquire values of the jot item
$item = get_entity($item_guid);
if (!$item){
	$aspect = $item_guid;
}
$item_owner  = $item->owner_guid;
$parent_guid = $item->container_guid;
$category    = $item->marketcategory;
$parent_item = get_entity($parent_guid);

if(!$referrer){
	if ($item->getSubtype() == 'market' || $item->getSubtype() == 'item') {
		$referrer   = elgg_get_site_url()."market/view/$item_guid";
	}
	else {
		$referrer   = elgg_get_site_url()."jot/view/$item_guid";
	}
}

$jotactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'jot');
$title = elgg_echo("jot:title:{$aspect}");

/*
elgg_push_breadcrumb(elgg_echo('jot:title'), "jot/home");
if ($parent_item->type == 'user' ){
	  elgg_push_breadcrumb($parent_item->name, "jot/owned/".$parent_item->username);
}
//elgg_push_breadcrumb(elgg_echo("jot:category:{$aspect}"), "jot/category/{$aspect}");
if ($item->type == 'object' ){
	  elgg_push_breadcrumb($item->title, $referrer);
}
elgg_push_breadcrumb('new '.elgg_echo($aspect));
*/
if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}
elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
if ($parent_item->type == 'user' ){
	  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->username);
}
elgg_push_breadcrumb(elgg_echo("market:category:{$category}"), "market/category/{$category}");

if ($parent_item->type == 'object' ){
	  if ($parent_item->getSubtype() == 'place'){
	  	elgg_push_breadcrumb($parent_item->title, "places/view/".$parent_guid);
	  }
	  else {
	  	elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
	  }
}
//  elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid.'#'.$bookmark);
  elgg_push_breadcrumb($item->title, "market/view/".$item_guid);

$form_vars = array('name' => 'jotForm', 
                   'enctype' => 'multipart/form-data',
                   'action'  => 'action/jot/add',
			);

if ($aspect == 'issue'){
	$body_vars = issues_prepare_form_vars(null, $item_guid, $referrer, $description); 
}
if ($aspect == 'observation'){
	$body_vars = observations_prepare_form_vars(null, $item_guid, $referrer, $description); 
}
if ($aspect == 'insight'){
	$body_vars = insights_prepare_form_vars(null, $item_guid, $referrer, $description); 
}
// Show jot sidebar
$sidebar = elgg_view("jot/sidebar");
$content = elgg_view_form("{$aspect}s/add", $form_vars, $body_vars);

$params = array(
		'content' => $content,
		'title'   => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);
$form = elgg_view_page($title, $body);

if ($aspect == 'transfer'){
		// Decide
		// Is the transfer associated with an item?
		if ($item_guid){
			//Yes - The transfer is associated with an item
			// Is the item owned by the logged-in user?
			if($item_owner == elgg_get_logged_in_user_guid()){
				//Yes - The item is owned by the logged-in user.
				$decision_state = 1; // Transfer to new owner
				$title          = 'Transfer to new owner';
			}
				//No - The item is not owned by the logged-in user.
			else {
				$decision_state = 2; // Make an Offer
				$title          = 'Make an Offer';
			}
			$section        = 'ownership';
		}
		else {
			//No - The transfer is not associated with an item
			// Does the logged-in user have permission to acquire items from outside of QuebX?
			if($acquire_rights_phantom){
				$decision_state = 3; // Reciept Purchase
				$title          = 'Reciept Purchase';
			}
			else {
				$decision_state = 4; // Request Purchase
				$title          = 'Request Purchase';
			}
			$section        = 'receipt';
		}
	$body_vars = jot_prepare_form_vars($aspect, NULL, $item_guid, $referrer, $description, $section); 
	$body_vars['title']          = $description;
	$body_vars['decision_state'] = $decision_state;
	$body_vars['container_guid'] = $item_guid;
	$body_vars['referrer']       = $referrer;
	$content                     = elgg_view_form("{$aspect}s/add", $form_vars, $body_vars);
	$params  = array(
		'content' => $content,
		'title' => $item->title,
		'filter' => '',
		);
	$body = elgg_view_layout('action', $params);
	$module_type = 'popup';
	
	$form = elgg_view_module($module_type, $title, $body);

	if ($view_type == 'page') {
		$params = array(
			'content' => $content,
			'title'   => $title,
			'subtitye'=> $item->title,
			'sidebar' => $sidebar,
			);			
		$body = elgg_view_layout('one_sidebar', $params);
		$form = elgg_view_page($title, $body);
	}
}

//echo elgg_dump($vars);

echo $form;
//echo elgg_view_page($title, $body);
