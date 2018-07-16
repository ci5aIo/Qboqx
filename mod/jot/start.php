<?php
/**
 * Elgg Jot Plugin
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Scott Jenkins
 * @copyright Smarter Network, Inc.
 * @link www.SmarterNetwork.com
 * @version 1.8
 */

elgg_register_event_handler('init','system','jot_init');

function jot_init() {
	elgg_register_ajax_view('forms/experiences/edit');
	elgg_register_ajax_view('partials/jot_form_elements');
//	elgg_register_js('panels', elgg_get_plugins_path() . 'jot/views/default/js/panels.js');
	
	// Register a url handler
	elgg_register_plugin_hook_handler('entity:url', 'object', 'jot_set_url');
	
    // Register libraries of helper functions
    elgg_register_library('elgg:jot', elgg_get_plugins_path() . 'jot/lib/jot.php');
    elgg_register_library('elgg:jot:navigation', elgg_get_plugins_path() . 'jot/lib/navigation.php');
    elgg_register_library('elgg:jot:issues', elgg_get_plugins_path() . 'jot/lib/issues.php');
    elgg_register_library('elgg:jot:observations', elgg_get_plugins_path() . 'jot/lib/observations.php');
    elgg_register_library('elgg:jot:insights', elgg_get_plugins_path() . 'jot/lib/insights.php');
    elgg_register_library('elgg:jot:causes', elgg_get_plugins_path() . 'jot/lib/causes.php');
    //     elgg_register_library('elgg:jot:picker', elgg_get_plugins_path() . 'jot/lib/picker.php');
    $jot_js                 = elgg_get_simplecache_url('js' , 'jot');
    $jot_ajax_js            = elgg_get_simplecache_url('js' , 'jot_form_elements');
    $jot_css                = elgg_get_simplecache_url('css', 'jot');
    
    elgg_register_js('jot.js', $jot_js);
    elgg_register_css('jot.css', $jot_css);
    
    elgg_load_js('jot.js');
//    elgg_load_js('panels');
    elgg_load_css('jot.css');
	
    elgg_require_js($jot_ajax_js);
    // Add a site navigation items
	$item = new ElggMenuItem('jot', elgg_echo('jot:title'), 'jot/home');
	elgg_register_menu_item('site', $item);
	
	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'jot_icon_url_override');
	
	// Language short codes must be of the form "issues:key"
	// where key is the array key below
	elgg_set_config('issues', array(
		'title'              => 'text',
		'description'        => 'longtext',
		'asset'              => 'asset_id',
		'status'             => 'text',
		'issue_type'         => 'text',
		'issue_number'       => 'text',
		'discovery_date'     => 'date',
		'start_date'         => 'date',
		'end_date'           => 'date',
		'assigned_to'        => 'assign_to',
		'tags'               => 'tags',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('observations', array(
		'title'              => 'text',
		'description'        => 'longtext',
		'asset'              => 'asset_id',
		'number'             => 'text',
		'state'              => 'text',
		'moment'   => 'date',
		'observation_type'   => 'text',
		'observer'           => 'assign_to',
		'assigned_to'        => 'assign_to',
		'tags'               => 'tags',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('insights', array(
		'title'              => 'text',
		'description'        => 'longtext',
		'asset'              => 'asset_id',
		'number'             => 'text',
		'insight_date'       => 'date',
		'insight_type'       => 'text',
		'observer'           => 'assign_to',
		'tags'               => 'tags',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('causes', array(
		'title'              => 'text',
		'description'        => 'longtext',
		'asset'              => 'asset_id',
		'status'             => 'text',
		'number'             => 'text',
		'discovery_date'     => 'date',
		'start_date'         => 'date',
		'end_date'           => 'date',
		'cause_type'         => 'text',
		'assigned_to'        => 'assign_to',
		'tags'               => 'tags',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
		'root_cause'         => 'checkbox',
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('efforts', array(
		'title'              => 'text',
		'diagnosis'          => 'text',
		'provider'           => 'assign_to',
		'asset'              => 'asset_id',
		'state'              => 'text',
		'number'             => 'text',
		'cost'               => 'text',
		'open_date'          => 'date',
		'target_date'        => 'date',
		'start_date'         => 'date',
		'end_date'           => 'date',
		'cause_type'         => 'text',
		'assigned_to'        => 'assign_to',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
		'root_cause'         => 'checkbox',
		'cycle_in'           => 'text',
		'cycle_out'          => 'text',
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
/*	elgg_set_config('parts', array(
		'title'              => 'text',
		'qty'                => 'text',
		'number'             => 'text',
		'cost'               => 'text',
		'asset'              => 'asset_id',
		'state'              => 'text',
		'assigned_to'        => 'assign_to',
		'access_id'          => 'access',
		'write_access_id'    => 'write_access',
	));
*/	elgg_set_config('items', array(
		'title'              => 'text',
		'description'        => 'text',
		'category'           => 'text',
		'tags'               => 'tags',
		'aspect'             => 'text',
		'access_id'          => ACCESS_DEFAULT,
		'write_access_id'    => ACCESS_DEFAULT,
		'container_guid'     => elgg_get_page_owner_guid(),
		'guid'               => null,
		'parent_guid'        => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('transfer_receipt', array(
		'title'           => 'text',
		'merchant'        => 'text',
		'cashier'         => 'text',
		'moment'   => 'date',
		'total_cost'      => 'text',
		'shipping_cost'   => 'text',
		'taxable'         => 'text',
		'sales_tax'       => 'text',
		'payment_account' => 'dropdown',
		'receipt_no'      => 'text',
		'document_no'     => 'text',
		'purchased_by'    => 'text',
		'tags'            => 'tags',
		'aspect'          => 'text',
		'access_id'       => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'item_guid'       => 'text',
		'merchant_guid'   => 'text',
		'subtype'         => 'text',
		'referrer'        => 'url',
	)); 
	elgg_set_config('transfer_ownership', array(
		'title'              => 'text',
		'transfer_to'        => 'text',
		'value_received'     => 'text',
		'offer_date'         => 'date',
		'request_date'       => 'date',
		'acceptance_date'    => 'date',
		'delivery_date'      => 'date',
		'received_date'      => 'date',
		'selling_cost'       => 'text',
		'deposit_account'    => 'dropdown',
		'transaction_number' => 'text',
		'tags'               => 'tags',
		'aspect'             => 'text',
		'access_id'          => ACCESS_DEFAULT,
		'write_access_id'    => ACCESS_DEFAULT,
		'container_guid'     => elgg_get_page_owner_guid(),
		'guid'               => null,
		'item_guid'          => 'text',
		'subtype'            => 'text',
		'referrer'           => 'url',
	));
	elgg_set_config('pick', array(
		
	));
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'jot_owner_block_menu');
//@EDIT 2016-03-18 - SAJ
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'jot_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', 'jot_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'jot_entity_menu');

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg'           , 'jot/css/jot');
	elgg_extend_view('css/admin'           ,'jot/css/admincss');
	elgg_extend_view('css/elements/modules','jot/css/elements/actions');

     // Register javascript needed for sidebar menu
     // Stolen from Pages plugin
     $js_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.min.js';
     elgg_register_js('jquery-treeview', $js_url);
     $css_url = 'mod/pages/vendors/jquery-treeview/jquery.treeview.css';
     elgg_register_css('jquery-treeview', $css_url);

	// Add a new widget
	elgg_register_widget_type('jot',elgg_echo('jot:widget'),elgg_echo('jot:widget:description'));
	elgg_register_widget_type('transfer','Transfers','Documents such as receipts, offers and bills of sale');
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('jot', 'jot_page_handler');

	// Initialize a pagesetup for menus
	elgg_register_event_handler('pagesetup','system','jot_pagesetup');

	// Register entity type and subtypes
	elgg_register_entity_type ('object'     , 'jot');

	// Register actions - call actions with a trailing '/' to prevent 301 redirects
	$action_url = elgg_get_plugins_path() . "jot/actions/";
	elgg_register_action("jot/add/"           , "{$action_url}jot/add.php");
	elgg_register_action("jot/add/element/"   , "{$action_url}add/element.php");
	elgg_register_action("jot/add/element_v2/", "{$action_url}add/element_v2.php");
	elgg_register_action("jot/add/elements/"  , "{$action_url}add/elements.php");
	elgg_register_action("jot/upload_file/"   , "{$action_url}jot/attach.php");
	elgg_register_action("jot/attach/"        , "{$action_url}jot/attach.php");
	elgg_register_action("jot/attach2/"       , "{$action_url}jot/attach2.php");
	elgg_register_action("jot/detach/"        , "{$action_url}jot/detach.php");
	elgg_register_action("jot/edit/"          , "{$action_url}jot/edit.php");
	// temporary 
	elgg_register_action("jot/edit_v4/"       , "{$action_url}jot/edit_v4.php");
	elgg_register_action("jot/delete/"        , "{$action_url}jot/delete.php");
	elgg_register_action("jot/route/"         , "{$action_url}jot/route.php");
	elgg_register_action("jot/tag/"           , "{$action_url}jot/tag.php");
	elgg_register_action("issue/add/"         , "{$action_url}/issues/add.php");
	elgg_register_action("issues/add/"        , "{$action_url}/issues/add.php");
	elgg_register_action("issue/edit/"        , "{$action_url}/issues/edit.php");
	elgg_register_action("issues/edit/"       , "{$action_url}/issues/edit.php");
	elgg_register_action("observations/add/"  , "{$action_url}/observations/add.php");
	elgg_register_action("observations/edit/" , "{$action_url}/observations/edit.php");
	elgg_register_action("insights/add/"      , "{$action_url}/insights/add.php");
	elgg_register_action("insights/edit/"     , "{$action_url}/insights/edit.php");
	elgg_register_action("causes/edit/"       , "{$action_url}/causes/edit.php");
	elgg_register_action("efforts/edit/"      , "{$action_url}/efforts/edit.php");
	elgg_register_action("transactions/return/", "{$action_url}transactions/return.php");
	elgg_register_action("jot/issue/delete/"  , "{$action_url}jot/delete.php");
}


// jot page handler; allows the use of fancy URLs
function jot_page_handler($page) {

     elgg_load_library('elgg:jot');
     elgg_load_library('elgg:jot:navigation');
     elgg_load_library('elgg:jot:issues');
     elgg_load_library('elgg:jot:observations');
     elgg_load_library('elgg:jot:insights');
     elgg_load_library('elgg:jot:causes');

     // add the jquery treeview files for navigation
     elgg_load_js('jquery-treeview');
     elgg_load_css('jquery-treeview');

	$pages = dirname(__FILE__) . '/pages';
	
	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'add':
			elgg_gatekeeper();
			set_input('item_guid', $page[1]);
			set_input('aspect'   , $page[2]);
			set_input('view_type', $page[3]);
			include "$pages/jot/add.php";
			break;
		case 'box_experimental':
			elgg_gatekeeper();
			set_input('item_guid', $page[1]);
			set_input('aspect'   , $page[2]);
			set_input('action'    , $page[3]);
			include "$pages/jot/box_experimental.php";
			break;
		case 'box':
			elgg_gatekeeper();
			set_input('item_guid', $page[1]);
			set_input('aspect'   , $page[2]);
			set_input('action'    , $page[3]);
			include "$pages/jot/box.php";
			break;
		case 'edit':
			elgg_gatekeeper();
			set_input('guid'   , $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/edit.php";
			break;
		case 'view':
//			elgg_gatekeeper();
			set_input('guid'   , $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'attach':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/jot/attach.php";
			break;
		case 'jot':
		    elgg_gatekeeper();
		    set_input('guid'   , $page[1]);
		    set_input('type'   , $page[2]);
		    set_input('subtype', $page[3]);
		    include "$pages/jot/jot.php";
		    break;
		case 'show':
		  elgg_gatekeeper();
		  set_input('aspect', $page[1]);
		  include "$pages/home.php";
		  break;
		case 'show_more':
		  elgg_gatekeeper();
		  set_input('relationship', $page[1]);
		  set_input('item', $page[2]);
		  include "$pages/jot/show_more.php";
		  break;
		case 'issue/add':
			elgg_gatekeeper();
			set_input('item_guid', $page[1]);
			include "$pages/issues/add.php";
			break;
		case 'issue/edit':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/issues/edit.php";
			break;
		case 'issue':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
//			set_input('item_guid', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'observation':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'experience':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'insight':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'cause':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		case 'transfer':
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			set_input('section', $page[2]);
			include "$pages/jot/view.php";
			break;
		default:
			include "$pages/home.php";
		 break;
	}
}

// Populates the ->getURL() method for jot objects
// Deprecated:  replaced by jot_set_url()
/*function jot_url_handler($entity) {
	$friendly_title = elgg_get_friendly_title($entity->title);
	return "jot/view/$entity->guid";
}*/

// Add to the user block menu
function jot_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "jot/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('jot', elgg_echo('jot'), $url);
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
function jot_page_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		// only show buttons in jot pages
		if (elgg_in_context('jot')) {
			$user = elgg_get_logged_in_user_entity();
			$page_owner = elgg_get_page_owner_entity();
			if (!$page_owner) {
				$page_owner = elgg_get_logged_in_user_entity();
			}
/*
			if ($page_owner != $user) {
				$usertitle = elgg_echo('jot:user', array($page_owner->name));
				$return[] = new ElggMenuItem('1user', $usertitle, 'jot/owned/' . $page_owner->username);
				$friendstitle = elgg_echo('jot:user:friends', array($page_owner->name));
				$return[] = new ElggMenuItem('2userfriends', $friendstitle, 'jot/friends/' . $page_owner->username);
			}

			$return[] = new ElggMenuItem('1all', elgg_echo('jot:everyone'), 'jot');
			$return[] = new ElggMenuItem('2shared', elgg_echo('jot:shared'), 'jot/shared/' . $user->username);
			$return[] = new ElggMenuItem('3mine', elgg_echo('jot:mine'), 'jot/owned/' . $user->username);
			$return[] = new ElggMenuItem('4friends', elgg_echo('jot:friends'), 'jot/friends/' . $user->username);
*/		}
	}

	return $return;
}

/**
 * 
 * Changes the destination of the 'edit' link for a jot
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 */
function jot_entity_menu($hook, $type, $return, $params) {
    $jot = $params['entity'];
	if (!elgg_instanceof($jot, 'object') || !$jot->canEdit()) {
		return $return;
	}
/*	foreach ($return as $key => $link) {
		if ($link->getName() != 'edit') {
			continue;
		}
		
		// $link is our menu item for editing
		// urls may be different depending on subtype, so we'll do a switch here
		switch ($jot->getSubtype()) {
			case 'issue':
				$return[$key]->setHref('issue_edit'. $jot->getGUID() );
				break;
			case 'observation':
				$return[$key]->setHref('observations/edit/'. $jot->getGUID() );
//				$return[$key]->setHref('observation_edit'. $params['entity']->guid );
				break;
			case 'insight':
				$return[$key]->setHref('insight/edit/'. $jot->getGUID() );
				break;
			case 'cause':
				$return[$key]->setHref('cause_edit/'. $jot->getGUID() );
				break;
			case 'experience':
			case 'transfer':
			    $return[$key]->setHref('jot/edit/'. $jot->getGUID() );
				break;
		}
	}*/
	// Remove the 'edit' and 'delete' menus for all items.  Replaced by the 'Que' menu options
	foreach ($return as $key => $link) {
		if ($link->getName() != 'edit' && $link->getName() != 'delete') {continue;}
		    unset($return[$key]);
	}

	return $return;
}
/**
 * Placeholder pagesetukp obtained from messages/start.php.
 */
function jot_pagesetup() {
}
/**
 * Override the page url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function jot_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (is_jot($entity)) {
		return "jot/view/$entity->guid";
	}
}
/**
 * Is the given value a jot object?
 *
 * @param mixed $value
 *
 * @return bool
 * @access private
 */
function is_jot($value) {
	return ($value instanceof ElggObject) && 
	        in_array($value->getSubtype(), 
	                array('jot', 
	                      'cause', 
	                      'experience', 
	                      'insight',
	                      'issue', 
	                      'observation',
	                      'transfer'));
}
/**
 * Override the default entity icon for jots
 *
 * @return string Relative URL
 */

function jot_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
//	if (jot_is_jot($entity)) {
		switch ($params['size']) {
			case 'topbar':
			case 'tiny':
			case 'small':
				return 'mod/jot/images/jot.gif';
				break;
			default:
				return 'mod/jot/images/jot_lrg.gif';
				break;
		}
//	}
}