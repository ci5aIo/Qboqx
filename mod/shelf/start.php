<?php

/**
 * Shelf
 *
 * forked from ...
 * @package hypeJunction
 * @subpackage cart
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */

require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'shelf_init');

/**
 * Initialize
 * @return void
 */
function shelf_init() {
    
     // Register libraries of helper functions
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'shelf_owner_block_menu');
    elgg_register_library('elgg:shelf', elgg_get_plugins_path() . 'shelf/lib/shelf.php');
    elgg_load_library('elgg:shelf');
    
	elgg_register_page_handler('shelf', 'shelf_page_handler');
	
	elgg_register_action('shelf/load', __DIR__ . '/actions/shelf/load.php', 'public');
	elgg_register_action('shelf/arrange', __DIR__ . '/actions/shelf/arrange.php', 'public');
	elgg_register_action('shelf/empty', __DIR__ . '/actions/shelf/empty.php', 'public');
	elgg_register_action('shelf/pack', __DIR__ . '/actions/shelf/pack.php', 'public');
	elgg_register_action('shelf/pick', __DIR__ . '/actions/shelf/pack.php', 'public');
	elgg_register_action('shelf/packable', __DIR__ . '/actions/shelf/packable.php', 'public');

	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'shelf_set_public_pages');
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'shelf_entity_menu1', 200);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'shelf_entity_menu2', 202);
	
	// extend CSS
	elgg_extend_view('css/elgg', 'css/shelf/shelf');
	elgg_extend_view("css/elgg", "css/shelf/site");
	elgg_extend_view("css/elgg", "css/shelf/dropbox");
	
	// extend js
	elgg_extend_view("js/elgg", "js/shelf/site");
	elgg_extend_view("js/elgg", "js/shelf/dropbox");
	
	// register JS libraries
	$vendors = elgg_get_site_url() . "mod/shelf/vendors/";
	elgg_register_js("jquery.box", $vendors . "pack/jquery.pack.min.js");
	elgg_register_simplecache_view("css/pack/pack");
	elgg_register_css("jquery.pack", elgg_get_simplecache_url("css", "pack/pack"));
	
//	elgg_extend_view('page/elements/sidebar', 'resources/shelf/show', 500);
	
/* 2016-11-11 - shelf_count_items() causes a fatal exception error when user not logged in.
 * Example log entry:
 * [11-Nov-2016 13:02:46 UTC] Exception #1478869366: exception 'InvalidParameterException' with message 'File shelf.json (file guid:) is missing an owner!' in /home/mupy4c83/public_html/quebx_test/engine/classes/ElggDiskFilestore.php:215

	elgg_register_menu_item('topbar', [
		'name' => 'shelf',
		'href' => 'shelf',
		'text' => elgg_view_icon('shop-cart'),
//		'data-cart-indicator' => shelf_count_items(),
		'priority' => 800,
	]);
 */ 
}

/**
 * Handles shelf pages
 * /shelf
 *
 * @param array $segments URL segments
 * @return boolean
 */

function shelf_page_handler($page) {
	$pages = dirname(__FILE__) . '/pages/shelf';
	
	$page_type = $page[0];
	switch ($page_type) {
		case 'show':
		    set_input('item_guid', $page[1]);
		    set_input('show', $page[2]); // specific subtype
			include "$pages/show.php";
			break;
		default :
			set_input('show',$page[1]);
			include "$pages/index.php";
			break;
	}
}

/**
 * Add shelf pages to WG public pages list
 *
 * @param string $hook   "public_pages"
 * @param string $type   "walled_garden"
 * @param array  $return Public pages
 * @param array  $params Hook params
 * @return array
 */
function shelf_set_public_pages($hook, $type, $return, $params) {
	$return[] = "shelf/.*";
	return $return;
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
function shelf_entity_menu1($hook, $type, $return, $params) {
	if (
		(!elgg_instanceof($params['entity'], 'object', 'market') 
      && !elgg_instanceof($params['entity'], 'object', 'item')
		) 
	  || !$params['entity']->canEdit()
	   ) {
		return $return;
	}
		// 'open' link
		$options = array(
			'name' => 'open',
		    'text' => 'open',
//			'text' => elgg_view_icon('round-arrow-right'),
			'title' => elgg_echo('shelf:open'),
			'href' => elgg_add_action_tokens_to_url('action/shelf/open?guid=' . $params['entity']->guid),
			'priority' => 400,
		);
		
		$return[] = ElggMenuItem::factory($options);

		
		
	return $return;
}

function shelf_entity_menu2($hook, $type, $return, $params) {
	if (
		(!elgg_instanceof($params['entity'], 'object', 'market') 
      && !elgg_instanceof($params['entity'], 'object', 'item') 
      && !elgg_instanceof($params['entity'], 'object', 'hjalbumimage')
      && !elgg_instanceof($params['entity'], 'object', 'file')
		) 
	  || !$params['entity']->canEdit()
	   ) {
		return $return;
	}
		// 'open' link
		if (elgg_instanceof($params['entity'], 'object', 'market') 
         || elgg_instanceof($params['entity'], 'object', 'item')) {
		$return[] = ElggMenuItem::factory(array(
        			'name' => 'open',
                    'text'=> elgg_view('shelf/open', array('entity'=> $params['entity'])),
        			'priority' => 300,
        		));
	   }
		$return[] = ElggMenuItem::factory(array(
        			'name' => 'pack',
                    'text'=> elgg_view('shelf/pack', array('entity'=> $params['entity'])),
        			'priority' => 300,
        		));
		
		// 'place on shelf' link
		$return[] = ElggMenuItem::factory(array(
        			'name' => 'pick',
        		    'text' => 'Pick',
        			'title' => elgg_echo('shelf:load'),
        			'href' => elgg_add_action_tokens_to_url('action/shelf/load?guid=' . $params['entity']->guid),
        			'priority' => 300,
        		));
		
	return $return;
/*	
	// Create the 'Load' menu item
	$ShelfMenuItem = new ElggMenuItem('load', 'Load', elgg_add_action_tokens_to_url('action/shelf/load?guid=' . $params['entity']->guid));
	$return[] = $ShelfMenuItem;
	return $return;*/
}
// Add to the user block menu
function shelf_owner_block_menu($hook, $type, $return, $params) {

	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "shelf/show/{$params['entity']->username}";
		$item = new ElggMenuItem('shelf', elgg_echo('transfer'), $url);
		$return[] = $item;
	}

	return $return;

}
