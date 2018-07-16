<?php
/**
 * Elgg QuebX Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Scott Jenkins
 * @copyright Smarter Network, Inc. 2010-2015
 * @link www.QuebX.com
 * @version 1.8
 */

elgg_register_event_handler('init','system','quebx_init');
//elgg_register_event_handler('login', 'user', 'login_init');

function quebx_init() {
	ini_set('display_errors', '0');     # don't show any errors...
	error_reporting(E_ALL | E_STRICT);  # ...but do log them
	
	elgg_register_ajax_view('forms/experiences/edit');
	
     // Register libraries of helper functions
     elgg_register_library('elgg:quebx', elgg_get_plugins_path() . 'quebx/lib/quebx.php');
     elgg_register_library('elgg:quebx:navigation', elgg_get_plugins_path() . 'quebx/lib/navigation.php');
     elgg_register_library('elgg:quebx:views', elgg_get_plugins_path() . 'quebx/lib/views.php');
     elgg_register_library('elgg:quebx:output', elgg_get_plugins_path() . 'quebx/lib/output.php');
	// Add a site navigation items
	$item = new ElggMenuItem('quebx', elgg_echo('quebx:title'), 'quebx/queb');
	elgg_register_menu_item('site', $item);

    elgg_register_plugin_hook_handler('index', 'system', 'quebx_indexhandler', 0);
//	elgg_register_plugin_hook_handler("route", "livesearch2", "quebx_route_livesearch_handler");
	/* Extend owner block menu
	 * @param string   $hook     The name of the hook
	 * @param string   $type     The type of the hook
	 * @param callback $callback The name of a valid function or an array with object and method
	 * @param int      $priority The priority - 500 is default, lower numbers called first
	 *
	 * @return bool
	 */
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'quebx_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', 'quebx_page_menu');
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'quebx_entity_menu');

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg','css/quebx/css');
//	elgg_extend_view('css/elgg','css/quebx/from_pivotal.css');
	elgg_extend_view('css/admin','css/quebx/admincss');

     // Register javascript needed for sidebar menu
     // Stolen from Pages plugin
     $js_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.min.js';
     elgg_register_js('jquery-treeview', $js_url);
     $css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
     elgg_register_css('jquery-treeview', $css_url);
	 
//define
     //js
     $quebx_js_framework     = elgg_get_simplecache_url('js' , 'quebx/framework');
     $quebx_js_slide_menu    = elgg_get_simplecache_url('js' , 'quebx/slide_menu');
     $pivotal_js_framework   = elgg_get_simplecache_url('js' , 'from_pivotal.js');
     $autosize               = elgg_get_simplecache_url('js' , 'autosize.js');
     $parsley_validation     = elgg_get_simplecache_url('js' , 'parsley.js');
     $moment_src             = elgg_get_simplecache_url('js' , 'moment.js');
     elgg_define_js('moment_js', ['src' => $moment_src,'exports' => 'moment',]);
     $jquery_dropdown_js     = 'mod/quebx/vendors/jquery/dropdown/jquery.dropdown.js';
     $jquery_inputmask       = 'https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js';
     $infinite_scroll        = 'https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js';
     //css
     $parsley_validation_css = 'mod/quebx/vendors/parsley/src/parsley.css';
     $quebx_css_framework    = elgg_get_simplecache_url('css', 'quebx/framework');
     $quebx_css_slide_menu   = elgg_get_simplecache_url('css', 'quebx/slide_menu');
     $quebx_icons            = elgg_get_simplecache_url('css', 'quebx/icomoon_sprites_16');
     $quebx_css_from_pivotal = elgg_get_simplecache_url('css', 'quebx/from_pivotal.css');
     $jquery_dropdown_css    = 'mod/quebx/vendors/jquery/dropdown/jquery.dropdown.css';
//register
     //js
     elgg_register_js('quebx.slide_menu.js' , $quebx_js_slide_menu);
     elgg_register_js('autosize.js'         , $autosize);
     elgg_register_js('validation.js'       , $parsley_validation);
     elgg_register_js('dropdown.js'         , $jquery_dropdown_js);
     elgg_register_js('inputmask.js'        , $jquery_inputmask);
//     elgg_register_js('infinite_scroll.js'  , $infinite_scroll, 'head', 500);
     //css 
     elgg_register_css('quebx.framework.css'    , $quebx_css_framework, 8);
     elgg_register_css('quebx.slide_menu.css'   , $quebx_css_slide_menu);
     elgg_register_css('quebx.from_pivotal.css' , $quebx_css_from_pivotal);
     elgg_register_css('parsley.validation.css' , $parsley_validation_css);
     elgg_register_css('quebx.icons'            , $quebx_icons);
     elgg_register_css('dropdown.css'           , $jquery_dropdown_css);
//load
     //js
//     elgg_load_js('infinite_scroll.js');
     elgg_load_js('quebx.slide_menu.js');
     elgg_load_js('autosize.js');
     elgg_load_js('validation.js');
     elgg_load_js('dropdown.js');
     elgg_load_js('inputmask.js');
     //css
     elgg_load_css('quebx.framework.css');
     elgg_load_css('quebx.slide_menu.css');
     elgg_load_css('quebx.icons');
     elgg_load_css('dropdown.css');
     elgg_load_css('parsley.validation.css');
//require
     elgg_require_js($infinite_scroll);
     elgg_require_js($quebx_js_framework);
//     elgg_require_js('moment_js');
    
    // Added to set page header position to 'fixed';
//	elgg_extend_view('page/elements/head', 'landr/css'); 
//	elgg_require_js('landr/landr');
	
	// Add a new widget
	elgg_register_widget_type('quebx',elgg_echo('quebx:widget'),elgg_echo('quebx:widget:description'));

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('quebx','quebx_page_handler');
	elgg_register_page_handler('queb' ,'queb_page_handler');
	
	// extend page handlers
	elgg_register_plugin_hook_handler("route", "livesearch", "quebx_route_livesearch_handler");
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'q_menu');
	
	// Initialize a pagesetup for menus
	elgg_register_event_handler('pagesetup','system','quebx_pagesetup');

	// Register entity type and subtypes
	elgg_register_entity_type('object','item');
	add_subtype ('object', 'component', 'item_component'); 	
	add_subtype ('object', 'accessory', 'item_accessory'); 	

	// Register actions - call actions with a trailing '/' to prevent 301 redirects
	$action_url = elgg_get_plugins_path() . "quebx/actions/";
	elgg_register_action("quebx/add/item/", "{$action_url}add/item.php");
	elgg_register_action("quebx/edit/", "{$action_url}edit.php");
	elgg_register_action("quebx/delete/", "{$action_url}delete.php");
	
	elgg_load_library('elgg:quebx');
    elgg_load_library('elgg:quebx:navigation');
    elgg_load_library('elgg:quebx:views');
    elgg_load_library('elgg:quebx:output');
//    elgg_load_library('elgg:quebx:picker');
    
}

// quebx page handler; allows the use of fancy URLs
function quebx_page_handler($page) {

     // add the jquery treeview files for navigation
     elgg_load_js('jquery-treeview');
     elgg_load_css('jquery-treeview');

	$pages = dirname(__FILE__) . '/pages/quebx';
	$pages_tasks = dirname(__FILE__) . '/pages/tasks';

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'add':
			gatekeeper();
			include "$pages/add.php";
			break;
		case 'edit':
			gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;
		case 'view':
			set_input('container_guid', $page[1]);
			set_input('guid', $page[1]);
			include "$pages/view.php";
			break;
		case 'file':
			set_input('guid', $page[1]);
			set_input('time', $page[2]);
			set_input('name', $page[3]);
			include "$pages/file.php";
			return true;
			break;/*
		case 'task':
			set_input('guid', $page[1]);
			include "$pages_tasks/view.php";
			break;*/
		default:
			include "$pages/items.php";
			break;
	}

}
function queb_page_handler($page) {

	if(!get_input('x')) {
		set_input('x', $page[0]);
	}
	if (!isset($page[0])) {
		$page[0] = 'queb';
	}

	$page_type = $page[0];
	$base_dir  = elgg_get_plugins_path() . 'quebx/pages';
	
	Switch ($page_type){
	    case 'queb':
	        market_register_toggle();
	        include_once("$base_dir/queb.php");
	        return true;	        
	        break;
	    default:
	        market_register_toggle();
	        include_once("$base_dir/default.php");
	        return true;	        
	        break;
	}	
}

// Populates the ->getURL() method for quebx objects
function quebx_url_handler($entity) {

	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "quebx/view/{$entity->guid}/{$friendly_title}";

}

// Add to the user block menu
function quebx_owner_block_menu($hook, $type, $return, $params) {

	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "quebx/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('quebx', elgg_echo('quebx'), $url);
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
function quebx_page_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		// only show buttons in quebx pages
		if (elgg_in_context('quebx')) {
			$user = elgg_get_logged_in_user_entity();
			$page_owner = elgg_get_page_owner_entity();
			if (!$page_owner) {
				$page_owner = elgg_get_logged_in_user_entity();
			}

			if ($page_owner != $user) {
				$usertitle = elgg_echo('quebx:user', array($page_owner->name));
				$return[] = new ElggMenuItem('1user', $usertitle, 'quebx/owned/' . $page_owner->username);
				$friendstitle = elgg_echo('quebx:user:friends', array($page_owner->name));
				$return[] = new ElggMenuItem('2userfriends', $friendstitle, 'quebx/friends/' . $page_owner->username);
			}

			$return[] = new ElggMenuItem('1all', elgg_echo('quebx:everyone'), 'quebx');
			$return[] = new ElggMenuItem('2shared', elgg_echo('quebx:shared'), 'quebx/shared/' . $user->username);
			$return[] = new ElggMenuItem('3mine', elgg_echo('quebx:mine'), 'quebx/owned/' . $user->username);
			$return[] = new ElggMenuItem('4friends', elgg_echo('quebx:friends'), 'quebx/friends/' . $user->username);
		}
	}

	return $return;
}
function q_menu($hook, $type, $return, $params) {
	if (
		(!elgg_instanceof($params['entity'], 'object', 'market') 
      && !elgg_instanceof($params['entity'], 'object', 'item') 
      && !elgg_instanceof($params['entity'], 'object', 'transfer')
      && !elgg_instanceof($params['entity'], 'object', 'hjalbumimage')
		) 
	  || !$params['entity']->canEdit()
	   ) {
		return $return;
	}	
	$guid = $params['entity']->getGUID();	// 'Q' menu
	$params['guid'] = $params['entity']->getGUID();
	$params['menu'] = 'q';
	
		$item = new ElggMenuItem('q', elgg_view('jot/menu',$params), false);
		$return[] = $item;
	return $return;
}

/**
 * Placeholder pagesetup obtained from messages/start.php.
 */
function quebx_pagesetup() {
}
function quebx_indexhandler($hook, $type, $return, $params){
  
	if ($return == true) {
		// another hook has already replaced the front page
		// so we won't do anything
		return $return;
	}
  
	if (elgg_is_logged_in()){
	    $url = elgg_get_site_url()."dashboard/";
	    forward($url);
	}
	// if we made it this far we are in control of the index
	// attempt to get our index page
	if (!include_once(elgg_get_plugins_path(). "landr/index.php")) {
	    // something went wrong, our page can't be found
	    // so return false so that the user either gets a 404
	    // or another plugin hook can attempt something after us
        return false;
	}
  
  // we've successfully included our page
  // so we're returning true to let other plugin hooks know that the index has been taken care of
  return TRUE;
  
}
/*
function login_init() {
    $url = elgg_get_site_url()."dashboard/";
    forward($url);
}*/