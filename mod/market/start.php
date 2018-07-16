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


elgg_register_event_handler('init','system','market_init');
elgg_register_plugin_hook_handler('init', 'system', function() {
//   elgg_register_ajax_view('partials/form_elements');
//    $form_elements    = elgg_get_simplecache_url('js' , 'form_elements.js');
//    elgg_register_js('form_elements', $form_elements);
//    elgg_load_js('form_elements');
//   elgg_require_js('js/form_elements');
});
require_once __DIR__ . '/lib/profiles.php';

function market_init() {
	   elgg_register_ajax_view('partials/form_elements');
// 	   $form_elements    = elgg_get_simplecache_url('js' , 'form_elements.js');
// 	   elgg_register_js('form_elements', $form_elements);
// 	   elgg_load_js('form_elements');
	   elgg_require_js('js/form_elements');
	   

     // Register libraries of helper functions
     elgg_register_library('elgg:market', elgg_get_plugins_path() . 'market/lib/market.php');

	// Add a site navigation items
    $list_type = get_input('list_type', 'list'); 
    if (!empty($list_type)){
        $list_type_filter = "?list_type=$list_type";
    }
         
	$item = new ElggMenuItem('market', elgg_echo('market:title'), "queb".$list_type_filter);
//    $item = new ElggMenuItem('market', elgg_echo('market:title'), 'market/category');
	elgg_register_menu_item('site', $item);
	
	
	/* Extend owner block menu
	 * @param string   $hook     The name of the hook
	 * @param string   $type     The type of the hook
	 * @param callback $callback The name of a valid function or an array with object and method
	 * @param int      $priority The priority - 500 is default, lower numbers called first
	 *
	 * @return bool
	 */
	
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'market_owner_block_menu');
//@EDIT 2016-03-18 - SAJ
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'market_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', 'market_page_menu', 600);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'market_entity_menu2');
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'market_entity_menu');
	elgg_register_plugin_hook_handler('register', 'menu:image', 'market_image_menu');
	
	// Extend system CSS with our own styles
//	elgg_extend_view('css/elgg','market/css');
//	elgg_extend_view('css/admin','market/admincss');

     // Register javascript needed for sidebar menu
     // Stolen from Pages plugin
	elgg_define_js('jquery.treeview', array(
		'src' => '/mod/pages/vendors/jquery-treeview/jquery.treeview.min.js',
		'exports' => 'jQuery.fn.treeview',
		'deps' => array('jquery'),
	));
     $js_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.min.js';
     elgg_register_js('jquery-treeview', $js_url);

     $css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
     elgg_register_css('jquery-treeview', $css_url);


	// Add a new widget
	elgg_register_widget_type('market',elgg_echo('market:widget'),elgg_echo('market:widget:description'));

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('market','market_page_handler');

	// Initialize a pagesetup for menus
	elgg_register_event_handler('pagesetup','system','market_pagesetup');

	// Override the default url to view a market post
	elgg_register_plugin_hook_handler('entity:url'      , 'object', 'market_url_handler');
	elgg_register_plugin_hook_handler('entity:icon:url' , 'object', 'market_set_icon_url');
	elgg_register_plugin_hook_handler('entity:icon:file', 'object', 'market_set_icon_file');

	// Register an icon handler for groups
	elgg_register_page_handler('groupicon', 'groups_icon_handler');
	
	// Register entity type and subtypes
	elgg_register_entity_type('object','market');

	// Register actions - call actions with a trailing '/' to prevent 301 redirects
	$action_url = elgg_get_plugins_path() . "market/actions/";
	elgg_register_action("market/add/item/", "{$action_url}add/item.php");
	elgg_register_action("market/add_now", "{$action_url}add/now.php");
	elgg_register_action("market/add/element/", "{$action_url}add/element.php");
	elgg_register_action("market/quick/", "{$action_url}quick.php");
	elgg_register_action("market/edit/", "{$action_url}edit.php");
	elgg_register_action("market/edit_more/", "{$action_url}edit_more.php");
	elgg_register_action("market/edit_old/", "{$action_url}edit_old.php");
	elgg_register_action("market/edit/element/", "{$action_url}edit/element.php");
	elgg_register_action("market/inventory/", "{$action_url}inventory.php");
	elgg_register_action("market/delete/", "{$action_url}delete.php");
	elgg_register_action("market/clone/", "{$action_url}clone.php");
	elgg_register_action("market/pack/", "{$action_url}pack.php");
	elgg_register_action("market/unpack/", "{$action_url}unpack.php");
	elgg_register_action("market/materialize/", "{$action_url}materialize.php");
	elgg_register_action("pick/", "{$action_url}pick.php");
	elgg_register_action("pick_test/", "{$action_url}pick_test.php");

	elgg_register_action("tasks/edit/", "{$action_url}tasks/edit.php");
	elgg_register_action("comments/add/", "{$action_url}comments/add.php");
	elgg_register_action("comment/save/", "{$action_url}comment/save.php");
	elgg_register_action("create/album", elgg_get_plugins_path() . "hypegallery/edit/object/hjalbum");

	// add to groups
	add_group_tool_option('items', elgg_echo('groups:enableitems'), true);
	elgg_extend_view('groups/tool_latest', 'market/group_module');
	
	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'market_icon_url_override');

	
}

// market page handler; allows the use of fancy URLs
function market_page_handler($page) {

     elgg_load_library('elgg:market');
     elgg_load_library('elgg:quebx:navigation');
     
//     elgg_load_library('elgg:market:picker');

     // add the jquery treeview files for navigation
     elgg_load_js('jquery-treeview');
     elgg_load_css('jquery-treeview');

	$pages = dirname(__FILE__) . '/pages/market';
	$file_dir = elgg_get_plugins_path() . 'quebx/pages/file';
		
	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'owned':
			market_register_toggle();
			set_input('username', $page[1]);
			include "$pages/owned.php";
			break;
		case 'shared':
			market_register_toggle();
			set_input('username', $page[1]);
			include "$pages/shared.php";
			break;
		case 'friends':
			market_register_toggle();
			set_input('username' , $page[1]);
			include "$pages/friends.php";
			break;
		case 'view':
			set_input('item'   , $page[1]);
			set_input('compartment', $page[2]);
			include "$pages/view.php";
			break;
		case 'add':
			gatekeeper();
			include "$pages/add/item.php";
			break;
		case 'edit_more':
		  gatekeeper();
		  include "$pages/edit_more.php";
		  break;
		case 'inventory':
		  gatekeeper();
		  include "$pages/inventory.php";
		  break;
		case 'quick':
			gatekeeper();
			include "$pages/quick.php";
			break;
		case 'edit':
			gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;
		case 'pack':
			gatekeeper();
			set_input('element_type'  , $page[1]);
			set_input('container_guid', $page[2]);
		    include "$pages/pack.php";
			break;
		case 'pick':
			gatekeeper();
			set_input('element_type'  , $page[1]);
			set_input('container_guid', $page[2]);
		    include "$pages/pick.php";
			break;
		case 'pick_test':
			gatekeeper();
			set_input('element_type'  , $page[1]);
			set_input('container_guid', $page[2]);
		    include "$pages/pick_test.php";
			break;
		case 'groups':
			gatekeeper();
			set_input('group_type'     , $page[1]);
			set_input('item_guid'      , $page[2]);
			set_input('selection_type' , $page[3]);
			set_input('group_subtype'  , $page[4]);
			set_input('container_guid' , $page[5]);
		    include "$pages/groups.php";
			break;
		case 'category':
			market_register_toggle();
			set_input('cat', $page[1]);
			include "$pages/category.php";
			break;
		case 'file':
			market_register_toggle();
			set_input('guid', $page[1]);
			set_input('time', $page[2]);
			set_input('name', $page[3]);
			include "$pages/file.php";
			return true;
			break;
		case 'file/view':
			set_input('guid', $page[1]);
			include "$file_dir/view.php";
			break;
		case 'move':
			gatekeeper();
			set_input('guid'  , $page[1]);
			set_input('aspect', $page[2]);
			set_input('asset' , $page[3]);
			include "$pages/move.php";
			break;
		case 'edit_gallery':
			set_input('guid'  , $page[1]);
			include "$pages/edit_gallery.php";
			break;
		case 'edit_library':
			set_input('guid'  , $page[1]);
			include "$pages/edit_library.php";
			break;
		case 'testing':
			set_input('guid'  , $page[1]);
			include "$pages/testing.php";
			break;
		case 'viewimage':
			set_input('guid'  , $page[1]);
			include "$pages/viewimage.php";
			break;
		default:
			market_register_toggle();
			include "$pages/category.php";
			break;
	}
}


// Populates the ->getURL() method for market objects
function market_url_handler($hook, $type, $url, $params) {
    $entity = $params['entity'];

    // Check that the entity is a market object
    if ($entity->getSubtype() !== 'market' && $entity->getSubtype() !== 'item') {
        // This is not a market object, so there's no need to go further
        return;
    }

    return "market/view/{$entity->guid}";
}
/*function market_url_handler($entity) {

	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "market/view/$entity->guid";

}*/

// Add to the user block menu
function market_owner_block_menu($hook, $type, $return, $params) {

	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "market/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('market', elgg_echo('market'), $url);
		$return[] = $item;
	}

	return $return;

}
/**
 * Add a page sidebar menu.
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 */
function market_page_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		// only show buttons in market pages
//		if (elgg_pop_context == 'market') {
//		if (elgg_in_context('market')) {
		if (elgg_get_context() == 'market') {
			$user = elgg_get_logged_in_user_entity();
			$page_owner = elgg_get_page_owner_entity();
			if (!$page_owner) {
				$page_owner = elgg_get_logged_in_user_entity();
			}

/*			if ($page_owner != $user) {
				$usertitle = elgg_echo('market:user', array($page_owner->name));
				$return[] = new ElggMenuItem('1user', $usertitle, 'market/owned/' . $page_owner->username);
				$friendstitle = elgg_echo('market:user:friends', array($page_owner->name));
				$return[] = new ElggMenuItem('2userfriends', $friendstitle, 'market/friends/' . $page_owner->username);
			}
*/
//			$return[] = new ElggMenuItem('01receipt', 'Add receipt ...', 'jot/edit/0/receipt');
// 			$return[] = new ElggMenuItem('02all', elgg_echo('market:everyone'), 'market');
// 			$return[] = new ElggMenuItem('03shared', elgg_echo('market:shared'), 'market/shared/' . $user->username);
// 			$return[] = new ElggMenuItem('04mine', elgg_echo('market:mine'), 'market/owned/' . $user->username);
// 			$return[] = new ElggMenuItem('05friends', elgg_echo('market:friends'), 'market/friends/' . $user->username);
/*			
			$return[] = ElggMenuItem::factory(array('name'   => '00receipt',
											'text'               => 'Add receipt ...', 
										    'class'              => 'elgg-lightbox',
										    'data-colorbox-opts' => '{"width":900, "height":525}',
							                'href'               => "jot/edit/0/receipt"));
*/
//	        elgg_register_menu_item('page', $view_menu[2]);
		
		}
		if (elgg_get_context == 'view_item') {
			
			$return[] = new ElggMenuItem("0inventory", "Manage Inventory", "market/edit_more/{$item_guid}/{$category}/family");
			$return[] = new ElggMenuItem('1component', 'Add component', elgg_add_action_tokens_to_url("action/market/add/element?element_type=component&guid=" . $item_guid));

		}


	}

	return $return;
}

/* TODO: move the picker functions to lib/picker.php
 * Don't want to clutter start.php
 */
 function cars_user_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'user',
		'joins' => array(
			"JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid",
		),
		'wheres' => array(
			"ue.username LIKE '%{$q}%' OR ue.name LIKE '%{$q}%'",
		),
		'order_by' => 'ue.name ASC'
	));
}


function cars_shoe_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$shoes_id = elgg_get_metastring_id('shoes');
	$marketcat_id = elgg_get_metastring_id('marketcategory');
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('market'),
		'joins' => array(
			"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
			"JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid AND md.name_id = {$marketcat_id} AND md.value_id = {$shoes_id}"
		),
		'wheres' => array(
			"oe.title LIKE '%{$q}%' OR oe.description LIKE '%{$q}%'",
		),
		'order_by' => 'oe.title ASC'
	));
}

function accessory_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$marketcat_id = elgg_get_metastring_id('marketcategory');
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('market'),
		'joins' => array(
			"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
			"JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid AND md.name_id = {$marketcat_id}"
		),
		'wheres' => array(
			"oe.title LIKE '%{$q}%' OR oe.description LIKE '%{$q}%'",
		),
		'order_by' => 'oe.title ASC'
	));
}

 function group_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'group',
		'joins' => array(
			"JOIN {$dbprefix}groups_entity ge ON ge.guid = e.guid",
		),
		'wheres' => array(
			"ge.name LIKE '%{$q}%' OR ge.description LIKE '%{$q}%'",
		),
		'order_by' => 'ge.name ASC'
	));
}
 
/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function market_register_toggle() {
	$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');
	$toggle_value = get_input('list_type', 'list'); 

	if ($toggle_value == 'list') {
		$list_type = "gallery";
		$icon = elgg_view_icon('grid');
	}
	if ($toggle_value == 'gallery') {
		$list_type = "select";
		$icon = elgg_view_icon('checkmark');
	}
	if ($toggle_value == 'select') {
		$list_type = "list";
		$icon = elgg_view_icon('list');
	}
	
	if (substr_count($url, '?')) {
		$url .= "&list_type=" . $list_type;
	} else {
		$url .= "?list_type=" . $list_type;
	}


	elgg_register_menu_item('extras', array(
		'name' => 'item_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("market:list:$list_type"),
		'priority' => 1,
	));
}

/**
 * 
 * Changes the destination of the 'edit' link for an item
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 */
//Disabled above
function market_entity_menu($hook, $type, $return, $params) {
	if (
		(!elgg_instanceof($params['entity'], 'object', 'market') 
      && !elgg_instanceof($params['entity'], 'object', 'item')
		)){
		return $return;
	}
	// Find the 'edit' menu item
	foreach ($return as $key => $link) {
		if ($link->getName() != 'edit') {
			continue;
		}
    	if (!$params['entity']->canEdit()){
    	    $return[$key]->SetHref('');
    	    break;
    	}
    	$return[$key]->SetName('foo');
    	
	// $link is our menu item for editing			
	// urls may be different depending on category, so we'll do a switch here
	switch ($params['entity']->marketcategory) {
		case 'car':
//			$return[$key]->setHref('market/edit/'. $params['entity']->guid . '/car/profile/fake');
//			$return[$key]->setHref('market/edit_more/'. $params['entity']->guid . '/car/profile');
			break;
		case 'auto':
			$return[$key]->setHref('market/edit/'. $params['entity']->guid . '/auto/profile');
			break;
		case '166':
			$return[$key]->setHref('market/edit_more/'. $params['entity']->guid . '/'. $params['entity']->marketcategory.'/profile');
			break;
	
		// add new cases for new categories
	      }
	}
	// Create the 'Clone' menu item
	$CloneMenuItem = new ElggMenuItem('clone', 'Clone', elgg_add_action_tokens_to_url('action/market/clone?guid=' . $params['entity']->guid));
	$return[] = $CloneMenuItem;
	
	$OpenMenuItem = new ElggMenuItem('open', elgg_view('shelf/open', array('entity'=> $params['entity'])));
	$return[] = $OpenMenuItem;
	
	return $return;
}

function market_entity_menu2($hook, $type, $return, $params) {
	if (
		(!elgg_instanceof($params['entity'], 'object', 'market') 
      && !elgg_instanceof($params['entity'], 'object', 'item')
		) 
	  || !$params['entity']->canEdit()
	   ) {
		return $return;
	}
	
	// Remove the ACL menu item
	foreach ($return as $key => $link) {
		if ($link->getName() != 'access') {
			continue;
		}
		unset($return[$key]);
	}
	// Remove the 'edit' menu item for non-editors
	foreach ($return as $key => $link) {
		if ($link->getName() != 'edit') {continue;}
		//if (!$params['entity']->canEdit()){
		// Remove the 'edit' menu for all items.  Replaced by the 'Que' menu options
		    unset($return[$key]);
		//}
	}
	// Remove the 'delete' menu for all items.  Replaced by the 'Que' menu options
// 	foreach ($return as $key=>$link){
// 		if ($link->getName() != 'delete') {continue;}
// 		unset($return[$key]);
// 	}
	
	
//	$return[] = new ElggMenuItem('open', elgg_view('shelf/open', array('entity'=> $params['entity'])), false);
//	$return[] = new ElggMenuItem('pack', elgg_view('shelf/pack', array('entity'=> $params['entity'])), false);
	
	//return false;
	
	return $return;
}

function market_image_menu($hook, $type, $return, $params) {
	if (!elgg_instanceof($params['entity'], 'object', 'image')
	  || !$params['entity']->canEdit()
	   ) {
		return $return;
	}
	// Find the 'edit' menu item
	foreach ($return as $key => $link) {
		if ($link->getName() != 'edit') {
			continue;
		}
	// $link is our menu item for editing			
	// urls may be different depending on category, so we'll do a switch here
	switch ($params['entity']->getSubtype()) {
		case 'image':
			$return[$key]->setHref('market/edit_image/'. $params['entity']->guid);
			break;
		// add new cases for new categories
	      }
	}
}

/**
 * Display notification of new messages in topbar
 * Placeholder obtained from messages/start.php.
 */
function market_pagesetup() {
/*	if (elgg_is_logged_in()) {
		$text = elgg_view_icon("mail");
		$tooltip = elgg_echo("messages");

		// get unread messages
		$num_messages = (int)messages_count_unread();
		if ($num_messages != 0) {
			$text .= "<span class=\"messages-new\">$num_messages</span>";
			$tooltip .= " (" . elgg_echo("messages:unreadcount", array($num_messages)) . ")";
		}

		elgg_register_menu_item('topbar', array(
			'name' => 'messages',
			'href' => 'messages/inbox/' . elgg_get_logged_in_user_entity()->username,
			'text' => $text,
			'priority' => 600,
			'title' => $tooltip,
		));
	}
*/}

/**
 * Override the default entity icon URL for market items
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string Relative URL
 */
function market_set_icon_url($hook, $type, $url, $params) {
	
	/* @var $entity \ElggObject */
	$entity = elgg_extract('entity', $params);
	
	$size = elgg_extract('size', $params, 'medium');
	
	$icontime = $entity->icontime;
	if (null === $icontime) {
		// handle missing metadata (pre 1.7 installations)
		$icon = $entity->getIcon('large');
		$icontime = $icon->exists() ? time() : 0;
		create_metadata($entity->guid, 'icontime', $icontime, 'integer', $entity->owner_guid, ACCESS_PUBLIC);
	}
	
	$icon = $entity->getIcon($size);
	if ($icon->exists()) {
		// user uploaded group icon
		$url = elgg_get_inline_url($icon, true); // binding to session due to complexity in group access controls
	} else {
		// show default market icon
		$url = elgg_get_simplecache_url("market/graphics/noimage{$size}.png");
	}
	
	return $url;
}

/**
 * Override the default entity icon file for market items
 *
 * @param string    $hook   "entity:icon:file"
 * @param string    $type   "object"
 * @param \ElggIcon $icon   Icon file
 * @param array     $params Hook params
 * @return \ElggIcon
 */
function market_set_icon_file($hook, $type, $icon, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'medium');

	$icon->owner_guid = $entity->owner_guid;
	$icon->setFilename("groups/{$entity->guid}{$size}.jpg");

	return $icon;
}

/**
 * Is the given value a market object?
 *
 * @param mixed $value
 *
 * @return bool
 * @access private
 */
function pages_is_market($value) {
	return ($value instanceof ElggObject) && in_array($value->getSubtype(), array('market', 'item'));
}

elgg_register_event_handler('login', 'user', 'login_init');

function login_init() {
//    $url = elgg_get_site_url()."activity/";
    $url = elgg_get_site_url()."dashboard/";
    forward($url);
}