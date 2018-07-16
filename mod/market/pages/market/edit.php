<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}

$guid = (int) get_input('guid');
$marketpost = get_entity($guid);
$owner_guid = $marketpost->owner_guid;
$parent_guid = $marketpost->container_guid;
$parent_item = get_entity($parent_guid);
$owner_item  = get_entity($owner_guid);

if(elgg_instanceof($marketpost,'object','market')    ||
   elgg_instanceof($marketpost,'object','item')) {
$title = elgg_echo('market:edit');
//$form_version = 'market/edit_old';
//$form_version = 'market/profile';
}
$view_menu[] = ElggMenuItem::factory(['name' => '01receipt',
									  'text' => 'Add receipt ...', 
						              'href' => "jot/edit/$marketpost->guid/receipt"]);
elgg_register_menu_item('page', $view_menu[0]);

$form_version = 'market/profile';
if(elgg_instanceof($marketpost,'object','accessory') || 
      elgg_instanceof($marketpost,'object','component'))  {
$title = 'Edit '.$marketpost->getSubtype();

/*
Switch ($marketpost->marketcategory){
	case 'car':
		$form_version = "market/edit_more/$guid/car/profile";
		break;
	default:
		$form_version = 'market/edit';
		break;
}
*/

}
if ($owner_item->getType() == 'user'){
	  elgg_push_breadcrumb($owner_item->name, "queb?z=".$owner_guid);
//	  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->username);
}
if ($owner_item->getType() == 'group' ){
	  elgg_push_breadcrumb($owner_item->name, "queb?z=".$owner_guid);
//	  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->guid);
}
if((elgg_instanceof($marketpost,'object','market')    ||
   elgg_instanceof($marketpost,'object','item')       ||
   elgg_instanceof($marketpost,'object','accessory')  || 
   elgg_instanceof($marketpost,'object','contents')  || 
   elgg_instanceof($marketpost,'object','component')) && $marketpost->canEdit()) {
          
   elgg_push_breadcrumb(elgg_echo('market:title'), "queb");
//   elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
  //elgg_push_breadcrumb(elgg_echo("market:{$category}"), "market/category/{$category}");
//  elgg_push_breadcrumb(elgg_echo('market:edit'));
   $category_set = hypeJunction\Categories\get_hierarchy($marketpost->category, true, true);
//   $category_set = hypeJunction\Categories\get_entity_categories($marketpost->guid, array(), true);
   foreach ($category_set as $category_guid){
//   foreach (array_reverse($category_set) as $category_guid){
        $this_category = get_entity($category_guid);
        elgg_push_breadcrumb($this_category->title, $this_category->getURL());
    }
  
if ($parent_item->type == 'object' ){
	  if ($parent_item->getSubtype() == 'place'){
	  	elgg_push_breadcrumb($parent_item->title, "places/view/".$parent_guid);
	  }
	  else {
	  	elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
	  }
}
//  elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid.'#'.$bookmark);
  elgg_push_breadcrumb($marketpost->title, "market/view/".$guid);
  elgg_push_breadcrumb($title);

  $form_vars = array('name' => 'marketForm', 'enctype' => 'multipart/form-data', 'action' => 'action/market/edit');
  //$form_vars = array('name' => 'marketForm', 'enctype' => 'multipart/form-data', 'action' => 'action/market/edit_old');
  //$form_vars = array('name' => 'marketForm', 'enctype' => 'multipart/form-data', 'action' => 'action/market/edit_more');
  // set up sticky form

  $body_vars = array(
      'entity' => $marketpost,
      'parent_guid' => $parent_guid,
      'markettitle' => $marketpost->title,
      'marketbody' => $marketpost->description,
      'markettags' => $marketpost->tags,
      'access_id' => $marketpost->access_id,
      'marketcategory' => $marketpost->marketcategory,
  	  'guid' => $guid, 
  	  'space'=> 'market',
  	  'h'=>$h
  );
  if (elgg_is_sticky_form('market')) {
  		$sticky_values = elgg_get_sticky_values('market');
  		foreach ($sticky_values as $key => $value) {
  			$body_vars[$key] = $value;
  		}
  }

  elgg_clear_sticky_form('market');

  $content = elgg_view_form($form_version, $form_vars, $body_vars);
//  $content = elgg_view_form("market/edit", $form_vars, $body_vars);

} else {
	$content = elgg_view("market/error");
}
$content = '<!--Form: '.$form_version.'-->'.$content;
$content = '<!--Action: action/market/edit-->'.$content;
$content = '<!--Page: market\pages\market\edit-->'.$content;

// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
